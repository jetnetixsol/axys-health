<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Device;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\SessionRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    //

    function index()
    {
        //
        $patients = array();
        $role = Auth::guard()->user()->role;

        if ($role === 'admin') {
            $patients = Patient::select('patients.*', 'devices.serial_number')->leftJoin('devices', 'devices.patient_id', '=', 'patients.id')
                ->paginate(20);
        } elseif ($role === 'clinic') {
            $clinicID = Auth::guard()->user()->id;
            $patients = Patient::select('patients.*', 'devices.serial_number')->leftJoin('devices', 'devices.patient_id', '=', 'patients.id')
                ->where('patients.clinic_id', $clinicID)->paginate(20);
        } else if ($role === 'doctor') {
            $doctorID = Auth::guard()->user()->id;
            $patients = Patient::select('patients.*', 'devices.serial_number')->leftJoin('devices', 'devices.patient_id', '=', 'patients.id')
                ->where('patients.doctor_id', $doctorID)->paginate(20);
        }

        $currentDate = Date("Y-m-d");
        $pastThirtyDates = Date("Y-m-d", strtotime('-30 Days'));
        //add Report to Patients
        if (!empty($patients)) {
            foreach ($patients as $l => $patient) {
                $patients[$l]['records'] = json_decode(SessionRecord::where('patient_id', $patient['id'])->whereDate('created_at', ">=", $pastThirtyDates)->whereDate('created_at', "<=", $currentDate)->orderBy('created_at', 'asc')->get(), true);
                $patients[$l]['last_record'] = json_decode(SessionRecord::where('patient_id', $patient['id'])->latest()->take(2)->get(), true);
                //Get Two last days record Groups
                $days_group = json_decode(SessionRecord::where('patient_id', $patient['id'])->orderBy('created_at', 'DESC')->get()->groupBy('date'), true);
                $days_group = array_splice($days_group, 0, 2);
                $days_group = array_reverse($days_group);
                if (!empty($days_group)) {
                    foreach ($days_group as $k => $group) {
                        $sysArr = [];
                        $diaArr = [];
                        $heartArr = [];
                        foreach ($group as $val) {
                            $sysArr[] = $val['systolic'];
                            $diaArr[] = $val['diastolic'];
                            $heartArr[] = $val['irregular_heartbeat'];
                        }
                        $days_group[$k]['min_sys'] = min($sysArr);
                        $days_group[$k]['max_sys'] = max($sysArr);
                        $days_group[$k]['average_sys'] =  array_sum($sysArr) / count($sysArr);
                        $days_group[$k]['min_dia'] = min($diaArr);
                        $days_group[$k]['max_dia'] = max($diaArr);
                        $days_group[$k]['average_dia'] =  array_sum($diaArr) / count($diaArr);
                        $days_group[$k]['min_heart'] = min($heartArr);
                        $days_group[$k]['max_heart'] = max($heartArr);
                        $days_group[$k]['average_heart'] =  array_sum($heartArr) / count($heartArr);
                    }
                }
                $patients[$l]['days_group'] = $days_group;
            }
        }
        // dd($patients);

        return view('all_patients', compact('patients'));
    }

    function addPatient()
    {
        $clinics = array();
        $doctors = array();
        $role = Auth::guard()->user()->role;

        if ($role === 'admin') {
            $clinics = Clinic::select('users.*')
                ->leftJoin('users', 'users.id', '=', 'clinics.user_id')
                ->orderBy('users.created_at', 'desc')->get();
            $doctors = Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at', 'desc')->leftJoin('users', 'users.id', '=', 'doctors.user_id')->get();
        } elseif ($role === 'clinic') {
            $clinicID = Auth::guard()->user()->id;
            // $clinics = Clinic::select('users.*')
            //     ->leftJoin('users', 'users.id', '=', 'clinics.user_id')
            //     ->where('users.id', $clinicID)
            //     ->orderBy('users.created_at', 'desc')->get();
            $doctors = Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at', 'desc')
                ->leftJoin('users', 'users.id', '=', 'doctors.user_id')->where('doctors.clinic_id', $clinicID)->get();
        } //elseif ($role === 'doctor') {
        //$doctorID = Auth::guard()->user()->id;
        // $doctors = Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')->orderBy('users.created_at', 'desc')->leftJoin('users', 'users.id', '=', 'doctors.user_id')
        //     ->where('users.id', $doctorID)->get();
        // if (isset($doctors[0]->clinic_id)) {
        //     $clinics = Clinic::select('users.*')
        //         ->leftJoin('users', 'users.id', '=', 'clinics.user_id')
        //         ->orderBy('users.created_at', 'desc')->where('users.id', $doctors[0]->clinic_id)->get();
        // }
        //}
        return view('add_patient', compact('clinics', 'doctors'));
    }

    function insertPatient(Request $request)
    {
        $request->validate([
            'full_name' => 'required|max: 100',
            'mrn' => 'required',
            // 'middle_name' => 'required',
            // 'last_name' => 'required',
            'mobile_number' => 'required',
            'dob' => 'required',
            'clinic_id' => Auth::guard()->user()->role === 'admin' ? 'required' : '',
            'doctor_id' => Auth::guard()->user()->role === 'admin' || Auth::guard()->user()->role === 'clinic' ? 'required' : '',
        ], [
            'full_name.required' => 'Full Name Required!',
            // 'middle_name.required' => 'Middle Name Required!',
            // 'last_name.required' => 'Last Name Required!',
            'mobile_number.required' => 'Mobile Phone Required!',
            'dob.required' => 'Date of Birth Required!',
            'clinic_id.required' => 'Clinic Required!',
            'doctor_id.required' => 'Doctor Required',
            'mrn.required' => 'MRN Required',
        ]);

        $data = $request->all();
        unset($data['_token']);

        if (Auth::guard()->user()->role === 'clinic') {
            $data['clinic_id'] = Auth::guard()->user()->id;
        } elseif (Auth::guard()->user()->role === 'doctor') {
            $data['doctor_id'] = Auth::guard()->user()->id;
            $doctorData = Doctor::where('user_id', $data['doctor_id'])->get();
            $data['clinic_id'] = $doctorData[0]['clinic_id'];
        }

        Patient::create($data);
        return redirect()->route('patients')->with('success', 'Patient Added Successfully!');
    }

    function getDoctors(Request $request)
    {
        $clinicID = $request->clinicID;
        $doctors = json_decode(Doctor::select('doctors.*', 'users.name')->where('doctors.clinic_id', $clinicID)->leftJoin('users', 'users.id', '=', 'doctors.user_id')->get(), true);
        echo json_encode($doctors);
    }

    function filter(Request $request)
    {
        $recordType = $request->recordType;
        $patients = array();
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        //format date to Y-m-d if we get into d/m/Y format
        $startDate = sizeof(explode('/', $startDate)) > 1 ? Date('Y-m-d', strtotime($startDate)) : $startDate;
        $endDate = sizeof(explode('/', $endDate)) > 1 ? Date('Y-m-d', strtotime($endDate)) : $endDate;

        $role = Auth::guard()->user()->role;

        if ($recordType === "whole_record") {
            if ($role === 'admin') {
                $patients = Patient::select('patients.*', 'devices.serial_number')->leftJoin('devices', 'devices.patient_id', '=', 'patients.id');

                //Date 
                if (!empty($startDate) && !empty($endDate)) {
                    $patients->whereDate('patients.created_at', '>=', $startDate)->whereDate('patients.created_at', '<=', $endDate);
                }

                //Search
                if (!empty($request->search)) {
                    $patients->where(function ($q) use ($request) {
                        $q->orWhere('full_name', 'like', '%' . $request->search . '%')
                            ->orWhere('mobile_number', 'like', '%' . $request->search . '%')
                            ->orWhere('mrn', 'like', '%' . $request->search . '%')
                            ->orWhere('dob', 'like', '%' . $request->search . '%');
                    });
                }

                $patients = $patients->get()->toArray();
            } elseif ($role === 'clinic') {
                $clinicID = Auth::guard()->user()->id;
                $patients =  Patient::select('patients.*', 'devices.serial_number')->leftJoin('devices', 'devices.patient_id', '=', 'patients.id')
                    ->where('patients.clinic_id', $clinicID);

                //Date 
                if (!empty($startDate) && !empty($endDate)) {
                    $patients->whereDate('patients.created_at', '>=', $startDate)->whereDate('patients.created_at', '<=', $endDate);
                }

                //Search
                if (!empty($request->search)) {
                    $patients->where(function ($q) use ($request) {
                        $q->orWhere('full_name', 'like', '%' . $request->search . '%')
                            ->orWhere('mobile_number', 'like', '%' . $request->search . '%')
                            ->orWhere('mrn', 'like', '%' . $request->search . '%')
                            ->orWhere('dob', 'like', '%' . $request->search . '%');
                    });
                }

                $patients = $patients->get()->toArray();
            } else if ($role === 'doctor') {
                $doctorID = Auth::guard()->user()->id;
                $patients = Patient::select('patients.*', 'devices.serial_number')->leftJoin('devices', 'devices.patient_id', '=', 'patients.id')
                    ->where('patients.doctor_id', $doctorID);

                //Date 
                if (!empty($startDate) && !empty($endDate)) {
                    $patients->whereDate('patients.created_at', '>=', $startDate)->whereDate('patients.created_at', '<=', $endDate);
                }

                //Search
                if (!empty($request->search)) {
                    $patients->where(function ($q) use ($request) {
                        $q->orWhere('full_name', 'like', '%' . $request->search . '%')
                            ->orWhere('mobile_number', 'like', '%' . $request->search . '%')
                            ->orWhere('mrn', 'like', '%' . $request->search . '%')
                            ->orWhere('dob', 'like', '%' . $request->search . '%');
                    });
                }

                $patients = $patients->get()->toArray();
            }

            if (!empty($patients)) {
                foreach ($patients as $l => $patient) {
                    $currentDate = Date("Y-m-d");
                    $pastThirtyDates = Date("Y-m-d", strtotime('-30 Days'));
                    $patients[$l]['records'] = json_decode(SessionRecord::where('patient_id', $patient['id'])->whereDate('created_at', ">=", $pastThirtyDates)->whereDate('created_at', "<=", $currentDate)->orderBy('created_at', 'asc')->get(), true);
                    $patients[$l]['last_record'] = json_decode(SessionRecord::where('patient_id', $patient['id'])->orderBy('created_at', 'DESC')->limit(2)->get(), true);
                    //Get Two last days record Groups
                    $days_group = json_decode(SessionRecord::where('patient_id', $patient['id'])->orderBy('created_at', 'DESC')->get()->groupBy('date'), true);
                    $days_group = array_splice($days_group, 0, 2);
                    $days_group = array_reverse($days_group);
                    if (!empty($days_group)) {
                        foreach ($days_group as $k => $group) {
                            $sysArr = [];
                            $diaArr = [];
                            $heartArr = [];
                            foreach ($group as $val) {
                                $sysArr[] = $val['systolic'];
                                $diaArr[] = $val['diastolic'];
                                $heartArr[] = $val['irregular_heartbeat'];
                            }
                            $days_group[$k]['min_sys'] = min($sysArr);
                            $days_group[$k]['max_sys'] = max($sysArr);
                            $days_group[$k]['average_sys'] =  array_sum($sysArr) / count($sysArr);
                            $days_group[$k]['min_dia'] = min($diaArr);
                            $days_group[$k]['max_dia'] = max($diaArr);
                            $days_group[$k]['average_dia'] =  array_sum($diaArr) / count($diaArr);
                            $days_group[$k]['min_heart'] = min($heartArr);
                            $days_group[$k]['max_heart'] = max($heartArr);
                            $days_group[$k]['average_heart'] =  array_sum($heartArr) / count($heartArr);
                        }
                    }
                    $patients[$l]['days_group'] = $days_group;
                }

                $patients = view('filters.all_patients_filter', compact('patients'));
            } else {
                $patients = 'No record Found!';
            }
            return $patients;
        } elseif ($recordType === "graph_record") {
            $patients = Patient::select('patients.*', 'devices.serial_number')->leftJoin('devices', 'devices.patient_id', '=', 'patients.id')
                ->where('patients.id', $request->patientID)->get();
            if (!empty($patients)) {
                foreach ($patients as $l => $patient) {
                    $patients[$l]['records'] = json_decode(SessionRecord::where('patient_id', $patient['id'])->whereDate('created_at', ">=", $startDate)->whereDate('created_at', "<=", $endDate)->orderBy('created_at', 'asc')->get(), true);
                }
            }
        }

        echo json_encode($patients);
    }
}
