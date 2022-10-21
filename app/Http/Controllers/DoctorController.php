<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Device;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\ReadyToBill;
use App\Models\SessionRecord;
use App\Models\User;
use App\Models\WalletHistory;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class DoctorController extends Controller
{
    //
    function index()
    {
        $role = Auth::guard()->user()->role;
        $doctors = array();
        if ($role === 'admin') {
            $doctors = Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at')->leftJoin('users', 'users.id', '=', 'doctors.user_id')->get();
        } else if ($role === 'clinic') {
            $clinic_id = Auth::guard()->user()->id;
            $doctors = Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at')->leftJoin('users', 'users.id', '=', 'doctors.user_id')
                ->where('doctors.clinic_id', $clinic_id)->get();
        }
        return view('all_doctors', compact('doctors'));
    }

    function addDoctor()
    {
        $role = Auth::guard()->user()->role;
        $clinics = array();
        if ($role === 'admin') {
            $clinics = Clinic::select('users.*', 'clinics.address', 'clinics.manager_name', 'clinics.mobile_number')
                ->leftJoin('users', 'users.id', '=', 'clinics.user_id')->get();
        } else if ($role === 'clinic') {
            //Display Only the Clinic 
            $clinic_id = Auth::guard()->user()->id;
            // $clinics = Clinic::select('users.*', 'clinics.address', 'clinics.manager_name', 'clinics.mobile_number')
            //     ->leftJoin('users', 'users.id', '=', 'clinics.user_id')->where('users.id', $clinic_id)->get();
        }
        return view('add_doctor', compact('clinics'));
    }

    function insert(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            // 'middle_name' => 'required',
            'last_name' => 'required',
            'mobile_number' => 'required',
            'email' => 'required|unique:users,email',
            'speciality' => 'required',
            'clinic_id' => Auth::guard()->user()->role === 'admin' ? 'required' : '',
            'password' => 'required|min:6',
            'retype_password' => 'required|same:password',
        ], [
            'first_name.required' => 'First Name Required!',
            // 'middle_name.required' => 'Middle Name Required!',
            'last_name.required' => 'Last Name Required!',
            'mobile_number.required' => 'Mobile Phone Required!',
            'email.required' => 'Email Required!',
            'speciality.required' => 'Speciality Required!',
            'password.required' => 'Password Required!',
            'clinic_id.required' => 'Clinic Required!',
            'retype_password.required' => 'Retype Password Required!',
            'retype_password.same' => 'Re-Type Password Should same as Password',
        ]);

        $data = $request->all();

        $password = $data['password'];
        $email = $data['email'];

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'doctor';

        //Create User
        $user = User::create([
            'name' => $data['first_name'], 'email' => $data['email'],
            'password' => $data['password'], 'role' => $data['role']
        ]);

        $data['user_id'] = $user->id;

        unset(
            $data['_token'],
            $data['retype_password'],
            $data['first_name'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['first_name']
        );

        if (Auth::guard()->user()->role === 'clinic') {
            $data['clinic_id'] = Auth::guard()->user()->id;
        }

        //Create Doctor
        Doctor::create($data);

        $email_temp = view('email_temps.credentials', compact('email', 'password'))->render();
        $this->sendMail($email, "Welcome to Axys Health as Doctor!", $email_temp);

        return redirect()->route('doctors')->with('success', 'Doctor Added Successfully!');
    }

    //sendMail
    function sendMail($to, $subject, $message)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings //mail.jetnetix.com //Baborao123#!
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = getenv('MAIL_HOST');                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                  //Enable SMTP authentication
            $mail->Username   = getenv('MAIL_USERNAME');                     //SMTP username
            $mail->Password   = getenv('MAIL_PASSWORD');                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(getenv('MAIL_USERNAME'), getenv('MAIL_FROM_NAME'));
            $mail->addReplyTo(getenv('MAIL_USERNAME'), getenv('MAIL_FROM_NAME'));
            $mail->addAddress($to);  //Add a recipient              

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $message;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            // echo 'Message has been sent';
            // return redirect($redirect);
        } catch (MailException $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return 'error'; die;
        }
    }

    function viewDoctor($id)
    {
        $doctor = User::select('users.*', 'doctors.clinic_id', 'doctors.middle_name', 'doctors.last_name', 'doctors.mobile_number', 'doctors.speciality')
            ->leftJoin('doctors', 'users.id', '=', 'doctors.user_id')
            ->where('users.id', $id)
            ->get()->toArray();

        $patients = Patient::select('patients.*')->get()->toArray();
        if (!empty($patients)) {
            foreach ($patients as $k => $patient) {
                $patients[$k]['current'] = SessionRecord::where('session_records.patient_id', $patient['id'])->latest()->limit(1)->get()->toArray();
            }
        }

        $devices = Device::where('doctor_id', $id)->get();

        return view('view_doctorProfile', compact('doctor', 'patients', 'devices'));
    }

    function viewPatientAssign()
    {
        $role = Auth::guard()->user()->role;
        $patients = array();
        $devices = array();
        // if ($role === 'admin') {
        //     $patients = json_decode(Patient::select('patients.*', 'devices.patient_id')->leftJoin('devices', 'devices.patient_id', '=', 'patients.id')
        //         ->whereNull('devices.patient_id')->get(), true);

        //get the devices with doctor_id in patients
        // } elseif ($role === 'doctor') {
        $doctor_id = Auth::guard()->user()->id; //Doctor ID
        $doctor_data = Doctor::where('user_id', $doctor_id)->get()->toArray();
        $patients = json_decode(Patient::select('patients.*', 'devices.patient_id')->where('patients.doctor_id', $doctor_id)
            ->leftJoin('devices', 'devices.patient_id', '=', 'patients.id')->whereNull('devices.patient_id')
            ->get(), true);
        if (isset($doctor_data[0]['clinic_id'])) {
            $devices = json_decode(Device::where('clinic_id', $doctor_data[0]['clinic_id'])->whereNull('patient_id')->get(), true);
        }
        // }
        return view('assigned_patient', compact('patients', 'devices'));
    }

    function assignPatient(Request $request)
    {
        $patient = Patient::find($request->patient);
        Device::find($request->device_id)->update([
            'patient_id' => $request->patient,
            'doctor_id' => $patient->doctor_id,
            'session' => 'start'
        ]);
        return redirect()->back()->with('success', 'Assign to Patient Successfully!');
    }

    function getDevices(Request $request)
    {
        // $doctor_id = json_decode(Patient::select('doctor_id')->where('id', $request->patientID)->get(), true)[0]['doctor_id'];
        $data['devices'] = array();
        $doctor_id = Auth::guard()->user()->id;
        $doctor_data = Doctor::where('user_id', $doctor_id)->get()->toArray();
        if (isset($doctor_data[0]['clinic_id'])) {
            $data['devices'] = json_decode(Device::where('clinic_id', $doctor_data[0]['clinic_id'])->whereNull('patient_id')->get(), true);
        }
        echo json_encode($data);
    }

    function endSession($patient_id) //Doctor Only Function
    {
        $device = Device::where('patient_id', $patient_id)->get();

        if (empty($device[0]->id)) {
            return redirect()->back()->with('fail', 'No Device Found!');
        }

        //Check Session Record Existing To Expire or Move to ready to bill 
        $session_record = json_decode(SessionRecord::where('device_id', $device[0]->serial_number)
            ->where('patient_id', $patient_id)
            ->where('status', 'active')
            ->get()->unique('date'), true);

        if (empty($session_record)) {
            Device::find($device[0]->id)->update(['patient_id' => null, 'session' => 'end']);
            return redirect()->back()->with('success', 'Session Stopped Successfully');
        }

        // var_dump($session_record); die;
        $keys = array_keys($session_record); //use key because unique gives [0] then [5]
        $fromDate = Date('Y-m-d', strtotime($session_record[$keys[0]]['date'])); //initial Date('Y-m-d',strtotime($session_record[$keys[0]]['date']));
        $toDate = Date('Y-m-d', strtotime($session_record[$keys[sizeof($keys) - 1]]['date'] . '+1 day'));
        // dd($fromDate, $toDate, Date('Y-m-d', strtotime($toDate . '-1 day')));
        // dd(strtotime($fromDate), strtotime($toDate . '+44 days'));
        $intFromDate = strtotime($fromDate);
        $intToDate = strtotime($toDate);
        $timeDiff = abs($intFromDate - $intToDate);
        $numberDays = intval($timeDiff / 86400);

        SessionRecord::where('device_id', $device[0]->serial_number)
            ->whereDate('date', '>=', Date('Y-m-d', strtotime($fromDate)))->where('date', '<=', Date('Y-m-d', strtotime($toDate . '-1 day')))
            ->update(['status' => 'expired']);

        //move to readybill
        $charges = 1 * $numberDays; //dates * 1$
        if ($numberDays > 45) {
            $daysInterval = floor($numberDays / 45);
            for ($i = 0; $i < $daysInterval; $i++) {
                $toDate = Date('Y-m-d', strtotime($fromDate . '+44 days'));
                $charges = 45;
                $detuctedAmount = 0.0;
                $payment_status = 'unpaid';

                //minimize $charge with wallet credit
                $wallet_amount = Patient::select('clinics.user_id as ClinicId', 'clinics.wallet_amount')->where('patients.id', $patient_id)
                    ->leftJoin('clinics', 'patients.clinic_id', '=', 'clinics.user_id')
                    ->get();

                if (isset($wallet_amount[0]['wallet_amount']) && $wallet_amount[0]['wallet_amount'] > 0) {
                    $remainingAmount = $wallet_amount[0]['wallet_amount'] - $charges;
                    if ($remainingAmount < 0) { //deducted amount is in negative form
                        $detuctedAmount = $charges + $remainingAmount;
                        $remainingAmount = 0;
                    } else {
                        $detuctedAmount = $charges;
                        $payment_status = 'paid';
                    }

                    WalletHistory::create([
                        "amount" => $detuctedAmount, "quantity" => 1,
                        "user_id" => $wallet_amount[0]['ClinicId'],
                        "description" => $device[0]->serial_number . " Device Charges Debit",
                        "incr_decr" => "decrement"
                    ]);

                    //update Clinic wallet amount by deducting paid amount 
                    Clinic::where('user_id', $wallet_amount[0]['ClinicId'])->update(['wallet_amount' => $remainingAmount]);
                }

                ReadyToBill::create([
                    'patient_id' => $patient_id, 'device_ids' => $device[0]->serial_number,
                    'from' => $fromDate, 'to' => $toDate, 'charges' => $charges,
                    "paid" => $detuctedAmount, "payment_status" => $payment_status
                ]);

                $fromDate = Date('Y-m-d', strtotime($toDate . '+1 day'));
            }
        } else {
            $detuctedAmount = 0.0;
            $payment_status = 'unpaid';
            $wallet_amount = Patient::select('clinics.user_id as ClinicId', 'clinics.wallet_amount')->where('patients.id', $patient_id)
                ->leftJoin('clinics', 'patients.clinic_id', '=', 'clinics.user_id')->get();

            if (isset($wallet_amount[0]['wallet_amount']) && $wallet_amount[0]['wallet_amount'] > 0) {
                $remainingAmount = $wallet_amount[0]['wallet_amount'] - $charges;
                if ($remainingAmount < 0) { //deducted amount is in negative form
                    $detuctedAmount = $charges + $remainingAmount;
                    $remainingAmount = 0;
                } else {
                    $detuctedAmount = $charges;
                    $payment_status = 'paid';
                }

                WalletHistory::create([
                    "amount" => $detuctedAmount, "quantity" => 1,
                    "user_id" => $wallet_amount[0]['ClinicId'],
                    "description" => $device[0]->serial_number . " Device Charges Debit",
                    "incr_decr" => "decrement"
                ]);

                //update Clinic wallet amount by deducting paid amount 
                Clinic::where('user_id', $wallet_amount[0]['ClinicId'])->update(['wallet_amount' => $remainingAmount]);
            }

            ReadyToBill::create([
                'patient_id' => $patient_id, 'device_ids' => $device[0]->serial_number,
                'from' => $fromDate, 'to' => Date('Y-m-d', strtotime($toDate . '+1 day')), 'charges' => $charges,
                "paid" => $detuctedAmount, "payment_status" => $payment_status
            ]);
        }


        //Unassigned Patient ID with device
        // Device::find($device[0]->id)->update(['patient_id' => null, 'session' => 'end']);


        // if (sizeof($session_record) > 15) {
        //     $keys = array_keys($session_record); //use key because unique gives [0] then [5]
        //     //$break_record = floor($session_record / 16); //get the number of breaks
        //     $initialDate = Date('Y-m-d', strtotime($session_record[$keys[0]]['date'])); //initial 
        //     //Date First Time
        //     $daysCount = 0;
        //     $fromDate = $initialDate;
        //     $toDate = null;
        //     for ($i = 0; $i < sizeof($session_record); $i++) {
        //         $currentPosDate = Date('Y-m-d', strtotime($session_record[$keys[$i]]['date']));
        //         if ($initialDate !== $currentPosDate) { //02 != 03

        //             //expire previous ones
        //             $secondLastDate = Date("Y-m-d", strtotime($currentPosDate . '-1 Day'));
        //             SessionRecord::where('device_id', $device[0]->serial_number)
        //                 ->whereBetween('date', [$fromDate, $secondLastDate])->update(['status' => 'expired']);

        //             $daysCount = 0; //reset days count
        //             $initialDate = $currentPosDate; //set initialize date
        //             $fromDate = $initialDate;

        //             //repeat increment process
        //             $daysCount += 1;
        //             $initialDate = Date('Y-m-d', strtotime($initialDate . '+1 Day'));
        //         } else {
        //             //increment date and time
        //             $daysCount += 1;
        //             $initialDate = Date('Y-m-d', strtotime($initialDate . '+1 Day'));
        //         }



        //         if ($daysCount == 16) {
        //             $daysCount = 0;  //reset days Count
        //             $toDate = $currentPosDate;

        //             //expire from to date
        //             SessionRecord::where('device_id', $device[0]->serial_number)
        //                 ->whereBetween('date', [$fromDate, $toDate])->update(['status' => 'expired']);

        //             //move to readybill
        //             ReadyToBill::create(['patient_id' => $patient_id, 'device_id' => $device[0]->id, 'from' => $fromDate, 'to' => $toDate]);

        //             //update From Date 
        //             $fromDate = $initialDate;
        //         }
        //     }
        // }

        //Unassigned Patient ID with device
        if (!empty($device[0]->id)) {
            Device::find($device[0]->id)->update(['patient_id' => null, 'session' => 'end']);
            SessionRecord::where('device_id', $device[0]->serial_number)->where('patient_id', $patient_id)
                ->where('status', 'active')->update(['status' => 'expired']);
        }

        return redirect()->back()->with('success', 'Session End Successfully');
    }

    function insertRemarks(Request $request)
    {
        $text = $request->text;
        Patient::where('id', $request->patientID)->update(['remarks' => $text]);
    }

    //Filter All Doctors
    function filter(Request $request)
    {
        $role = Auth::guard()->user()->role;
        $doctors = array();
        if ($role === 'admin') {
            $doctors = Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at')->leftJoin('users', 'users.id', '=', 'doctors.user_id')
                ->whereDate('users.created_at', '>=', $request->startDate)->whereDate('users.created_at', '<=', $request->endDate)->get();
        } else if ($role === 'clinic') {
            $clinic_id = Auth::guard()->user()->id;
            $doctors = Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at')->leftJoin('users', 'users.id', '=', 'doctors.user_id')
                ->where('doctors.clinic_id', $clinic_id)->whereDate('users.created_at', '>=', $request->startDate)->whereDate('users.created_at', '<=', $request->endDate)->get();
        }

        $data['tr'] = array();
        if (!empty($doctors)) {
            foreach ($doctors as $doctor) {
                $td = array();
                $doctor['name'] = (isset($doctor['name']) ? $doctor['name'] . ' '  : '')
                    . (isset($doctor['middle_name']) ? $doctor['middle_name'] . ' '  : '')
                    . (isset($doctor['last_name']) ? $doctor['last_name']  : '');
                $td[] = '<td>' . (isset($doctor['name']) ? $doctor['name'] : '') . '</td>';
                $td[] = '<td>' . (isset($doctor['speciality']) ? $doctor['speciality'] : '') . '</td>';
                $td[] = '<td><a href="tel:' . (isset($doctor['mobile_number']) ? $doctor['mobile_number'] : 'javascript:void(0)') . '">' . (isset($doctor['mobile_number']) ? $doctor['mobile_number'] : '--') . '</a></td>';
                $td[] = '<td><a href="mailto:' . (isset($doctor['email']) ? $doctor['email'] : 'javascript:void(0)') . '">' . (isset($doctor['email']) ? $doctor['email'] : '--') . '</a></td>';
                $td[] = '<td><a href="' . route('doctor.single', ['id' => $doctor['id']]) . '" class="btn btn-primary"><i class="las la-eye"></i></a></td>';
                $data['tr'][] = $td;
            }
        }

        echo json_encode($data);
    }
}
