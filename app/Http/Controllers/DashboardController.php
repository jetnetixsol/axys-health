<?php

namespace App\Http\Controllers;

use App\Models\Buyrecord;
use App\Models\Clinic;
use App\Models\Device;
use App\Models\Doctor;
use App\Models\FixBilling;
use App\Models\Request as ModelRequest;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Product;
use App\Models\ReadyToBill;
use App\Models\Request as ModelsRequest;
use App\Models\SessionRecord;
use App\Models\StripeConfig;
use App\Models\User;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use App\Traits\StripeChargeTrait;
use Exception;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    //
    use StripeChargeTrait;

    private $role = '';
    private $user_id = null;
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->role = Auth::guard()->user()->role;
            $this->user_id = Auth::guard()->user()->id;
            return $next($request);
        });
    }

    function index()
    {
        $doctors =  array();
        $requests = array();
        $archiveRequests = array();
        $availableDeviceCount = 0;
        $readings = array();
        $noOfPatients = array();
        $totalPatientCount = array();
        $currentDate = Date("Y-m-d");

        $role = Auth::guard()->user()->role;
        if ($role === 'admin') {
            $doctors = Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at')->leftJoin('users', 'users.id', '=', 'doctors.user_id')->get();
            $requests = json_decode(ModelRequest::select(
                'requests.*',
                'users.name',
                'users.email',
                'clinics.address',
                'clinics.city',
                'clinics.state'
            )
                ->where('request_by', 'clinic')
                ->where('is_archive', 'No')
                ->leftJoin('users', 'requests.clinic_id', '=', 'users.id')
                ->leftJoin('clinics', 'clinics.user_id', '=', 'users.id')
                ->get(), true);

            $archiveRequests = ModelRequest::select('requests.*', 'users.name')
                ->where('request_by', 'clinic')
                ->where('is_archive', 'Yes')
                ->leftJoin('users', 'requests.clinic_id', '=', 'users.id')
                ->get()->toArray();

            Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at')->leftJoin('users', 'users.id', '=', 'doctors.user_id')->get();
            $readings = SessionRecord::whereDate("created_at", ">=", Date('Y-m-d', strtotime($currentDate . '-30days')))->whereDate("created_at", "<=", $currentDate)->orderBy("created_at", "asc")->get();

            //Patients data
            for ($i = 1; $i <= 12; $i++) {
                $noOfPatients[Date('Y-' . ($i < 10 ? '0' . $i : $i))] = Patient::where('created_at', 'like', '%' . Date('Y-' . ($i < 10 ? '0' . $i : $i)) . '%')->count();
            }
            $totalPatientCount = Patient::count();
        } elseif ($role === 'clinic') {
            $clinic_id = Auth::guard()->user()->id;
            $doctors = Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at')->leftJoin('users', 'users.id', '=', 'doctors.user_id')
                ->where('doctors.clinic_id', $clinic_id)->get();
            $availableDeviceCount = Device::whereNull('clinic_id')->count();
            $readings = SessionRecord::select('session_records.*')->whereDate("session_records.created_at", ">=", Date('Y-m-d', strtotime($currentDate . '-30days')))
                ->whereDate("session_records.created_at", "<=", $currentDate)->leftJoin('patients', 'patients.id', '=', 'session_records.patient_id')
                ->where('patients.clinic_id', $clinic_id)
                ->orderBy("session_records.created_at", "asc")->get();
            // $availableDevices = Device::where('clinic_id',$clinic_id)->whereNull('doctor_id')->limit(4);

            //Patients data
            for ($i = 1; $i <= 12; $i++) {
                $noOfPatients[Date('Y-' . ($i < 10 ? '0' . $i : $i))] = Patient::where('created_at', 'like', '%' . Date('Y-' . ($i < 10 ? '0' . $i : $i)) . '%')->where('clinic_id', $clinic_id)->count();
            }
            $totalPatientCount = Patient::where('clinic_id', $clinic_id)->count();
        } else if ($role === "doctor") {
            $doctor_id = Auth::guard()->user()->id;
            $clinic_id = Doctor::select('doctors.clinic_id')->where('user_id', $doctor_id)->get()->toArray();
            $readings = SessionRecord::select('session_records.*')->whereDate("session_records.created_at", ">=", Date('Y-m-d', strtotime($currentDate . '-30days')))
                ->whereDate("session_records.created_at", "<=", $currentDate)
                ->leftJoin('patients', 'patients.id', '=', 'session_records.patient_id')
                ->where('patients.doctor_id', $doctor_id)
                ->orderBy("session_records.created_at", "asc")->get();

            //Patients data
            for ($i = 1; $i <= 12; $i++) {
                $noOfPatients[Date('Y-' . ($i < 10 ? '0' . $i : $i))] = Patient::where('created_at', 'like', '%' . Date('Y-' . ($i < 10 ? '0' . $i : $i)) . '%')
                    ->where('doctor_id', $doctor_id)->count();
            }
            $totalPatientCount = Patient::where('doctor_id', $doctor_id)->count();
        }

        $clinicsCount = Clinic::count();
        $devicesCount = Device::count();
        // var_dump("Here"); die;
        return view('index', compact(
            'doctors',
            'requests',
            'archiveRequests',
            'clinicsCount',
            'devicesCount',
            'availableDeviceCount',
            'readings',
            'noOfPatients',
            'totalPatientCount',
        ));
    }

    function buyMonitor()
    {
        $clinics = json_decode(Clinic::select('users.*', 'clinics.address', 'clinics.manager_name', 'clinics.mobile_number')->orderBy('users.created_at', 'DESC')->leftJoin('users', 'users.id', '=', 'clinics.user_id')->get(), true); //If role is admin than all clinic, if only clinic than that clininc
        //Unassigned Devices
        $devices = json_decode(Device::select('devices.*')->get(), true);
        return view('buy_monitor', compact('clinics', 'devices'));
    }

    function insertMoreDevice(Request $request)
    {
        $deviceLimit = intval($request->quant) * 4;
        $data['devices'] = json_decode(Device::select('devices.*')->whereNull('clinic_id')->limit($deviceLimit)->get(), true);

        $data['exceed_limit'] = 'no';
        if (sizeof($data['devices']) < $deviceLimit) {
            $data['exceed_limit'] = 'yes';
        }

        echo json_encode($data);
    }

    function requestDevice(Request $request)
    {
        $data = $request->all();
        $user_id = Auth::guard()->user()->id; //Auth::guard()->user()->id; 
        $availableDevices = Device::whereNull('clinic_id')->count();
        if (!is_numeric($data['quantity'])) {
            return redirect()->back()->with('fail', 'Please Enter Number Only!');
        } elseif ($data['quantity'] <= 0) {
            return redirect()->back()->with('fail', 'Please Select atleast 1 device');
        } else if ($availableDevices < $data['quantity']) {
            return redirect()->back()->with('fail', $availableDevices . ' Maximum device can be selected');
        }

        unset($data['_token']);
        $data['clinic_id'] = $user_id;
        $data['request_by'] = 'clinic';
        $adminID = User::where('role', 'admin')->get();

        //Notification 
        $clinic = User::find($user_id);
        $notifArr = [
            'user_id' => $adminID[0]->id,
            'notification' => (isset($clinic->name) ? $clinic->name . ' ' : '') . "Clinic Requested Devices",
            'action' => route('index'),
            'status' => "unread",
        ];
        Notification::create($notifArr);
        ModelRequest::create($data);
        return redirect()->back()->with('success', 'Request Send Successfully!');
    }

    function assignDevice(Request $request)
    {
        $data = $request->all();
        $devicesIDs = $data['deviceIds'];
        $linked = $data['linked'];
        $data['quantity'] = $data['quantity_val'];
        $requestID = $data['request_id'];
        $data['buyer'] = $data['request_by'];

        unset(
            $data['_token'],
            $data['quantity_val'],
            $data['deviceIds'],
            $data['linked'],
            $data['request_id'],
            $data['request_by']
        );

        $notifArr = [
            'action' => route('devices'),
            'status' => "unread",
        ];

        if ($data['buyer'] === 'doctor') {
            $data['doctor_id'] = $data['clinic_id'];
            $notifArr['user_id'] = $data['doctor_id']; //Doctor get this notification
            unset($data['clinic_id']);
        } else {
            $notifArr['user_id'] = $data['clinic_id']; //Clinic With ID get this notification
        }

        if (!empty($devicesIDs)) {
            foreach ($devicesIDs as $deviceID) {
                if ($linked === "yes" && $request->request_by === 'clinic') {
                    Device::find($deviceID)->where('id', $deviceID)->update(['clinic_id' => $data['clinic_id']]);
                    $data['device_id'] = $deviceID;
                    Buyrecord::create($data);
                } elseif ($linked === "yes" && $request->request_by === 'doctor') {
                    //maybe doctori id
                    Device::find($deviceID)->where('id', $deviceID)->update(['doctor_id' => $data['doctor_id']]);
                    $data['device_id'] = $deviceID;
                    Buyrecord::create($data);
                }
            }

            ModelRequest::find($requestID)->delete();

            if ($linked === "yes") {
                if ($data['buyer'] === "doctor") { //For Doctor
                    $notifArr['notification'] = 'Clinic Linked Devices that you Request!';
                } elseif ($data['buyer'] === "clinic") { //For Clinic
                    $notifArr['notification'] = 'Admin Linked Devices that you Request!';
                }
                // Fixed Bill Start
                $billData = array(
                    "device_ids" => json_encode($devicesIDs), "price" => sizeof($devicesIDs) * 30,
                    "clinic_id" => $data['clinic_id']
                );
                FixBilling::create($billData);

                //Add Wallet 
                $deviceAmount = sizeof($devicesIDs) * 30;
                $quantity = sizeof($devicesIDs);
                $user = User::find($data["clinic_id"]);
                $clinic = Clinic::where('user_id', $data['clinic_id'])->get();
                if (isset($clinic[0]->wallet_amount) && isset($clinic[0]->user_id)) {
                    Clinic::where('user_id', $clinic[0]->user_id)
                        ->update(['wallet_amount' => ($deviceAmount + $clinic[0]->wallet_amount)]);
                }
                //Wallet Add End

                WalletHistory::create([
                    "amount" => $deviceAmount, "quantity" => $quantity,
                    "user_id" => $data["clinic_id"],
                    "description" => (isset($user->name) ? $user->name : "Clinic") . " Device Bought Credit",
                    "incr_decr" => "increment"
                ]);

                //
                $admin = User::where('role', 'admin')->get();
                if (isset($admin[0]->id)) {
                    WalletHistory::create([
                        "amount" => $deviceAmount, "quantity" => $quantity,
                        "user_id" => $admin[0]->id,
                        "description" => (isset($user->name) ? $user->name : "Clinic") . " Device Bought Credit",
                        "incr_decr" => "decrement"
                    ]);
                }


                // Fixed Bill End
                Notification::create($notifArr);
                return redirect()->back()->with('success', 'Device Assigned Successfully!');
            } else {
                if ($data['buyer'] === "doctor") { //For Doctor
                    $notifArr['notification'] = 'Clinic Not Accept your Device Request!';
                } elseif ($data['buyer'] === "clinic") { //For clinic
                    $notifArr['notification'] = 'Admin Not Accept your Device Request!';
                }
                Notification::create($notifArr);
                return redirect()->back()->with('success', 'Device Request Deleted!');
            }
        } else {
            return redirect()->back()->with('fail', 'Device Assign Failed!');
        }
    }

    function buyDevice(Request $request)
    {
        $data = $request->all();

        if (empty($data['deviceIds'])) {
            return redirect()->route('buy.monitor')->with('fail', 'No Device Available!');
        }

        $devicesIDs = $data['deviceIds'];
        $data['clinic_id'] = $data['clinic_name'];
        $data['quantity'] = count($devicesIDs);
        $data['total_price'] = count($devicesIDs) * 30;
        $data['buyer'] = 'clinic';

        unset(
            $data['_token'],
            $data['clinic_name'],
            $data['deviceIds'],
        );

        foreach ($devicesIDs as $deviceID) {
            Device::find($deviceID)->where('id', $deviceID)->update(['clinic_id' => $data['clinic_id']]);
            $data['device_id'] = $deviceID;
            Buyrecord::create($data);
        }

        FixBilling::create([
            'device_ids' => json_encode($devicesIDs),
            'clinic_id' => $data['clinic_id'],
            'price' => (sizeof($devicesIDs) * 30),
            'status' => 'unpaid'
        ]);

        $notifArr = [
            'user_id' => $data['clinic_id'],
            'notification' => "Admin Assign You New Devices",
            'action' => route('devices'),
            'status' => "unread",
        ];
        Notification::create($notifArr);


        //Add Wallet 
        $deviceAmount = sizeof($devicesIDs) * 30;
        $quantity = sizeof($devicesIDs);
        $user = User::find($data["clinic_id"]);
        $clinic = Clinic::where('user_id', $data['clinic_id'])->get();
        if (isset($clinic[0]->wallet_amount) && isset($clinic[0]->user_id)) {
            Clinic::where('user_id', $clinic[0]->user_id)
                ->update(['wallet_amount' => ($deviceAmount + $clinic[0]->wallet_amount)]);
        }
        //Wallet Add End

        WalletHistory::create([
            "amount" => $deviceAmount, "quantity" => $quantity,
            "user_id" => $data["clinic_id"],
            "description" => (isset($user->name) ? $user->name : "Clinic") . " Device Bought Credit",
            "incr_decr" => "increment"
        ]);

        //
        $admin = User::where('role', 'admin')->get();
        if (isset($admin[0]->id)) {
            WalletHistory::create([
                "amount" => $deviceAmount, "quantity" => $quantity,
                "user_id" => $admin[0]->id,
                "description" => (isset($user->name) ? $user->name : "Clinic") . " Device Bought Credit",
                "incr_decr" => "decrement"
            ]);
        }

        return redirect()->back()->with('success', 'Device Assigned Successfully!');
    }

    function viewWallet()
    {
        $clinics = array();
        $walletHistory = array();
        $totalCredit = null;

        if ($this->role === "admin") {
            $clinics = User::where('role', 'clinic')->get()->toArray();
        } else if ($this->role === "clinic") {
            $totalCredit = Clinic::select('wallet_amount')->where('user_id', $this->user_id)->get();
        }

        $walletHistory = WalletHistory::where('user_id', $this->user_id)
            ->orderBy('created_at', 'desc')->get()->toArray();

        return view('wallet', compact('clinics', 'walletHistory', 'totalCredit'));
    }

    function topupWallet(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'amount' => 'required',
            'description' => 'required|max:150',
        ], [
            'user_id.required' => 'Clinic Required',
            'amount.required' => 'Amount Required',
            'description.required' => 'Description Required',
        ]);


        $clinicData = User::select('users.*', 'clinics.wallet_amount')
            ->leftJoin('clinics', 'users.id', 'clinics.user_id')
            ->where('users.id', $request->user_id)->get()->toArray();
        if (!isset($clinicData[0]['id'])) {
            return redirect()->back()->With('fail', 'Clinic not exists');
        } elseif ($request->amount <= 0) {
            return redirect()->back()->With('fail', 'Please enter amount greater than 1');
        }

        Clinic::where('user_id', $clinicData[0]['id'])
            ->update(['wallet_amount' => (floatval($clinicData[0]['wallet_amount']) + floatval($request->amount))]);

        //create wallet history for admin
        $walletData = array(
            'user_id' => $this->user_id, 'amount' => $request->amount,
            'description' => $request->description, 'incr_decr' => 'decrement'
        );
        WalletHistory::create($walletData);

        //create wallet history for clinic
        $walletData = array(
            'user_id' => $request->user_id, 'amount' => $request->amount,
            'description' => $request->description, 'incr_decr' => 'increment'
        );
        WalletHistory::create($walletData);

        return redirect()->back()->with('success', 'Top Up Successfully!');
    }

    function billing()
    {

        $clinicID = Auth::guard()->user()->id;
        $activeDevices = Device::where('clinic_id', $clinicID)
            ->where('session', 'start')->get();

        $active_devices = array();
        if (!empty($activeDevices)) {
            foreach ($activeDevices as $activeDevice) {
                $session_record = json_decode(SessionRecord::where('device_id', $activeDevice['serial_number'])
                    ->where('patient_id', $activeDevice['patient_id'])
                    ->where('status', 'active')
                    ->orderBy('date', 'desc')
                    ->get()->unique('date'), true);

                if (!empty($session_record)) {
                    $keys = array_keys($session_record); //use key because unique gives [0] then [5]
                    $fromDate = Date('Y-m-d', strtotime($session_record[$keys[0]]['date'])); //initial 
                    $duration = sizeof($session_record);

                    $active_devices[] = array(
                        'start_date' => $fromDate, 'serial_number' => $activeDevice['serial_number'],
                        'total_payment' => $duration * 1, 'duration' => $duration
                    );
                }
            }
        }

        // $unactiveDevices = Device::where('clinic_id', $clinicID)
        //     ->where('session', 'end')->get();
        // $unactive_device = array();
        // if (!empty($unactiveDevices)) {
        //     foreach ($unactiveDevices as $unactiveDevice) {
        //         $session_record = json_decode(SessionRecord::where('device_id', $unactiveDevice['serial_number'])
        //             ->where('patient_id', $unactiveDevice['patient_id'])
        //             ->where('status', 'expired')
        //             ->get()->unique('date'), true);

        //         if (!empty($session_record)) {
        //             $keys = array_keys($session_record); //use key because unique gives [0] then [5]
        //             $fromDate = Date('Y-m-d', strtotime($session_record[$keys[0]]['date'])); //initial 
        //             $duration = sizeof($session_record);

        //             $unactive_device[] = array(
        //                 'start_date' => $fromDate, 'serial_number' => $unactive_device['serial_number'],
        //                 'total_payment' => $duration * 1, 'duration' => $duration
        //             );
        //         }
        //     }
        // }

        $ready_to_bill = ReadyToBill::select('ready_to_bills.*')
            ->leftJoin('patients', 'patients.id', '=', 'ready_to_bills.patient_id')
            ->where('patients.clinic_id', $clinicID)
            ->orderBy('created_at', 'desc')
            ->get();

        //Stripe Credentials
        $stripeConfig = StripeConfig::first();

        return view('billing', compact('active_devices', 'ready_to_bill', 'stripeConfig'));
    }

    function stripePay(Request $request)
    {

        $stripeConfig = StripeConfig::first();

        if (!isset($stripeConfig->stripe_key) || !isset($stripeConfig->stripe_secret)) {
            return redirect()->back()->with('fail', 'Please set Stripe Secret Key and  Stripe Public Key first!');
        }

        $this->setKeys($stripeConfig->stripe_key, $stripeConfig->stripe_secret);

        $model = null;
        $data = null;
        if (isset($request->b_i)) { //b_i stands for billing id
            $model = ReadyToBill::find(base64_decode(urldecode($request->b_i)));
            if (!isset($model->id)) {
                return redirect()->back()->with('fail', 'Bill not found!');
            }

            if (!empty($request->stripeToken)) {
                $token = $request->stripeToken;
                //create customer
                $response = $this->createCustomer($token);
                if ($response['success'] == 1) {
                    //create Charge Start
                    $customerID = $response['result'];
                    $due = floatval($model->charges - $model->paid);
                    $chargeResponse = $this->chargeCustomer($customerID, $due);
                    if ($chargeResponse['success'] == 1) {                                                      //getting the amount in cents to dollar divide by 100
                        $paid = floatval($model->paid + $due);
                        $model->update(["paid" => $paid, "payment_status" => "paid"]);
                        return redirect()->back()->with('success', 'Payment made successfully!, Charged Amount: $' . ($chargeResponse['result'] / 100));
                    } else if (($chargeResponse['success']) == 0) {
                        return redirect()->back()->with('fail', 'Error: ' . $chargeResponse['result']);
                    }
                    //create Charge End
                } else if (($response['success']) == 0) {
                    return redirect()->back()->with('fail', 'Error: ' . $response['result']);
                }
            } else {
                return redirect()->back()->with('fail', 'Token not found!');
            }
        } else {
            return redirect()->back()->with('fail', 'Error!');
        }
    }

    function config()
    {
        $keys = StripeConfig::first();
        return view('config', compact('keys'));
    }

    public function change_password(Request $request)
    {
        $rules = $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required',
        ]);
        $data = User::find(Auth::guard()->user()->id);
        if (Hash::check($request->old_password, $data->password)) {
            $new_password = Hash::make($request->password);
            $confirm_password = Hash::make($request->confirm_password);
            $data->password = $new_password;
            $data->save();
        } else {
            return redirect()->back()->with('fail', 'Password change failed, Wrong Old Password');
        }
        return redirect()->back()->with('success', 'Password Changed Successfully');
    }

    public function updateKeys(Request $request)
    {
        $data = $request->all();
        StripeConfig::first()->update($data);
        return redirect()->back()->with('success', 'Stripe Keys updated successfully!');
    }
    // function search_device()
    // {
    //     return view('search_device');
    // }
}
