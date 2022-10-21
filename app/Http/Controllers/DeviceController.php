<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console;

class DeviceController extends Controller
{
    //
    function index()
    {
        $devices = array();
        $assigned_devices = array();

        $role = Auth::guard()->user()->role;

        if ($role === 'admin') {
            $devices = Device::whereNull('clinic_id')->get();
            $assigned_devices = json_decode(Device::select(
                'devices.*',
                'patients.full_name',
                'patients.email',
                'users.name',
                'users.email',
                'doctor.name as doctorName',
                'doctor.email as doctorEmail',
                'doctors.middle_name',
                'doctors.last_name'
            )
                ->whereNotNull('devices.clinic_id')
                ->leftJoin('patients', 'patients.id', '=', 'devices.patient_id')
                ->leftJoin('users', 'users.id', '=', 'devices.clinic_id')
                ->leftJoin('users as doctor', 'doctor.id', '=', 'devices.doctor_id')
                ->leftJoin('doctors', 'doctors.user_id', '=', 'doctor.id')
                ->get(), true);
        } elseif ($role === 'clinic') {
            $clinic_id = Auth::guard()->user()->id;
            $assigned_devices = json_decode(
                Device::select(
                    'devices.*',
                    'patients.full_name',
                    'patients.email',
                    'users.name',
                    'users.email',
                    'doctor.name',
                    'doctor.email',
                    'doctors.middle_name',
                    'doctors.last_name'
                )
                    ->where('devices.clinic_id', $clinic_id)
                    ->leftJoin('patients', 'patients.id', '=', 'devices.patient_id')
                    ->leftJoin('users', 'users.id', '=', 'devices.patient_id')
                    ->leftJoin('users as doctor', 'doctor.id', '=', 'devices.doctor_id')
                    ->leftJoin('doctors', 'doctors.user_id', '=', 'doctor.id')
                    ->get(),
                true
            );
        } elseif ($role === 'doctor') {
            $doctor_id = Auth::guard()->user()->id;
            $assigned_devices = json_decode(Device::select(
                'devices.*',
                'patients.full_name',
                'patients.email',
            )
                ->leftJoin('patients', 'patients.id', '=', 'devices.patient_id')
                ->where('devices.doctor_id', $doctor_id)->get(), true);
        }
        return view('all_devices', compact('devices', 'assigned_devices'));
    }

    public function uploadCsv(Request $request)
    {
        $csv_file = $request->file('csv_file');
        if (!empty($csv_file) && strpos($_FILES['csv_file']['name'], '.csv')) {
            $file_name = $csv_file->storeAs('/', Date('Ymd') . time() . $_FILES['csv_file']['name'], 'csvs');
            $count = $this->get_data($file_name);
            if ($count > 0) {
                return redirect()->route('devices')->with('success', 'Data Uploaded Success!');
            } else {
                return redirect()->route('devices')->with('fail', 'No Data added!');
            }
        } else if ($csv_file != null) {
            return redirect()->back()->with('fail', 'CSV file accepted');
        } else {
            return redirect()->back()->with('fail', 'Some Error!');
        }
    }

    public function get_data($file_name)
    {
        $file_n = public_path('/csvs' . '/' . $file_name);
        // var_dump($file_n); die;
        $file = fopen($file_n, 'r');
        $headers = fgetcsv($file);
        $data = [];
        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            $item = [];
            foreach ($row as $key => $value) {
                $item[str_replace(' ', '_', $headers[$key])] = $value ?: null;
            }

            // var_dump($item); die;

            // var_dump($item); die;
            // ini_set('max_execution_time', 3600);//Increase the excetuion time
            if (isset($item['serialNumber']) && isset($item['imei']) && isset($item['modelNumber']) && isset($item['signal'])) {
                $exists = Device::where('serial_number', $item['serialNumber'])->get();
                if (!isset($exists[0]->id)) {
                    // DB::table('product2')->insert($item);
                    Device::create([
                        'serial_number' => $item['serialNumber'], 'imei' => $item['imei'],
                        'model_number' => $item['modelNumber'], 'signal' => $item['signal'],
                        'battery' => $item['battery']
                    ]);
                    $count += 1;
                }
            }

            $data[] = $item;
        }
        fclose($file);
        return $count;
    }

    function viewDevice($id)
    {
        $role = Auth::guard()->user()->role;
        $device = array();
        if ($role === 'admin' || $role === 'clinic') {
            $device = Device::select(
                'devices.*',
                'patients.full_name',
                'patients.email',
                'users.name as clinicName',
                'users.email as clinicEmail',
                'doctor.name as doctorName',
                'doctor.email as doctorEmail',
                'doctors.middle_name',
                'doctors.last_name'
            )
                ->where('devices.id', $id)
                ->leftJoin('patients', 'patients.id', '=', 'devices.patient_id')
                ->leftJoin('users', 'users.id', '=', 'devices.clinic_id')
                ->leftJoin('users as doctor', 'doctor.id', '=', 'devices.doctor_id')
                ->leftJoin('doctors', 'doctors.user_id', '=', 'doctor.id')
                ->get()
                ->toArray();
        } elseif ($role === 'doctor') {
            $device = Device::select(
                'devices.*',
                'patients.full_name',
                'patients.email',
            )
                ->where('devices.id', $id)
                ->leftJoin('patients', 'patients.id', '=', 'devices.patient_id')
                ->get()
                ->toArray();
        }
        return view('view_device', compact('device'));
    }

    function fetchDeivces(Request $request)
    {
        $limit = $request->count; //number of devices
        $clinic_id = $request->userID;
        if ($request->requestBy === "clinic") {
            $data['devices'] = json_decode(Device::whereNull('clinic_id')->limit($limit)->get(), true);
        } elseif ($request->requestBy === "doctor") {
            $data['devices'] = json_decode(Device::where('clinic_id', $clinic_id)->whereNull('doctor_id')->limit($limit)->get(), true);
        }
        echo json_encode($data);
    }

    //Filter All Devices
    function filter(Request $request)
    {
        $role = Auth::guard()->user()->role;
        $table = $request->table;
        $device = array();
        $assigned_devices = array();

        $data['tr'] = array();


        if ($role === 'admin') {
            $devices = Device::whereDate('created_at', '>=', $request->startDate)
                ->whereDate('created_at', '<=', $request->endDate)->whereNull('clinic_id')->get();
            $assigned_devices = json_decode(Device::whereNotNull('clinic_id')->whereDate('created_at', '>=', $request->startDate)
                ->whereDate('created_at', '<=', $request->endDate)->get(), true);
        } elseif ($role === 'clinic') {
            $clinic_id = Auth::guard()->user()->id;
            $assigned_devices = json_decode(Device::where('clinic_id', $clinic_id)->whereDate('created_at', '>=', $request->startDate)
                ->whereDate('created_at', '<=', $request->endDate)->get(), true);
        } elseif ($role === 'doctor') {
            $doctor_id = Auth::guard()->user()->id;
            $assigned_devices = json_decode(Device::where('doctor_id', $doctor_id)->whereDate('created_at', '>=', $request->startDate)
                ->whereDate('created_at', '<=', $request->endDate)->get(), true);
        }

        // var_dump($request->startDate,$request->endDate,$assigned_devices);die;

        if ($table === 'assignedDevicesTable') {
            if (!empty($devices)) {
                foreach ($devices as $device) {
                    $td = array();
                    $td[] = '<td>' . (Date('Y-m-d', strtotime($device['created_at']))) . '</td>';
                    $td[] = '<td>' . (isset($device['serial_number']) ? $device['serial_number'] : '') . '</td>';
                    $td[] = '<td>' . (isset($device['imei']) ? $device['imei'] : '') . '</td>';
                    $td[] = '<td>' . (isset($device['model_number']) ? $device['model_number'] : '') . '</td>';
                    if (isset($device['signal']) && $device['signal'] < 10) {
                        $td[] = '<td class="txt-red">Weak</td>';
                    } elseif (isset($device['signal']) && ($device['signal'] >= 10 && $device['signal'] < 20)) {
                        $td[] = '<td>Medium</td>';
                    } elseif (isset($device['signal']) && $device['signal'] >= 20) {
                        $td[] = '<td>Strong</td>';
                    }
                    $td[] = '<td><a href="' . route('device.single', ['id' => $device['id']]) . '" class="btn btn-primary"><i class="las la-eye"></i></a></td>';
                    $data['tr'][] = $td;
                }
            }
        } elseif ($table === 'assignedDevicesTable2') {
            if (!empty($assigned_devices)) {
                foreach ($assigned_devices as $assigned_device) {
                    $td = array();
                    $td[] = '<td>' . (Date('Y-m-d', strtotime($assigned_device['created_at']))) . '</td>';
                    $td[] = '<td>' . (isset($assigned_device['serial_number']) ? $assigned_device['serial_number'] : '') . '</td>';
                    $td[] = '<td>' . (isset($assigned_device['imei']) ? $assigned_device['imei'] : '') . '</td>';
                    $td[] = '<td>' . (isset($assigned_device['model_number']) ? $assigned_device['model_number'] : '') . '</td>';
                    if (isset($assigned_device['signal']) && $assigned_device['signal'] < 10) {
                        $td[] = '<td class="txt-red">Weak</td>';
                    } elseif (isset($assigned_device['signal']) && ($assigned_device['signal'] >= 10 && $assigned_device['signal'] < 20)) {
                        $td[] = '<td>Medium</td>';
                    } elseif (isset($assigned_device['signal']) && $assigned_device['signal'] >= 20) {
                        $td[] = '<td>Strong</td>';
                    }
                    $td[] = '<td><a href="' . route('device.single', ['id' => $assigned_device['id']]) . '" class="btn btn-primary"><i class="las la-eye"></i></a></td>';
                    $data['tr'][] = $td;
                }
            }
        }

        // var_dump($request->startDate,$request->endDate); die;

        echo json_encode($data);
    }

    function searchDevice(Request $request)
    {
        $query = $request->search;
        $serial_response = Device::select("devices.id", "devices.serial_number", "devices.clinic_id")->where('serial_number', "like", '%' . $query . '%')
            ->get()->toArray();
        echo json_encode($serial_response);
    }
}
