<?php

namespace App\Http\Controllers;

use App\Models\Buyrecord;
use App\Models\Clinic;
use App\Models\Device;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class ClinicController extends Controller
{
    //

    function index()
    {
        $clinics = Clinic::select('users.*', 'clinics.address', 'clinics.manager_name', 'clinics.mobile_number')->orderBy('users.created_at')->leftJoin('users', 'users.id', '=', 'clinics.user_id')->get();
        return view('all_clinics', compact('clinics'));
    }

    function insert(Request $request)
    {
        $request->validate([
            'clinic_name' => 'required',
            'manager_name' => 'required',
            'mobile_number' => 'required',
            'email' => 'required|unique:users,email',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'password' => 'required|min:6',
            'retype_password' => 'required|same:password',
        ], [
            'clinic_name.required' => 'Clinic Name Required!',
            'manager.required' => 'Manager Name Required!',
            'mobile_number.required' => 'Mobile Phone Required!',
            'email.required' => 'Email Required!',
            'password.required' => 'Password Required!',
            'retype_password.required' => 'Retype Password Required!',
            'retype_password.same' => 'Re-Type Password Should same as Password!',
            'city.required' => 'City Required!',
            'state.required' => 'State Required!',
        ]);

        $data = $request->all();

        $password = $data['password'];
        $email = $data['email'];

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'clinic';

        //Create User
        $user = User::create([
            'name' => $data['clinic_name'], 'email' => $data['email'],
            'password' => $data['password'], 'role' => $data['role']
        ]);

        $data['user_id'] = $user->id;

        unset(
            $data['_token'],
            $data['retype_password'],
            $data['first_name'],
            $data['email'],
            $data['password'],
            $data['clinic_name'],
            $data['role']
        );

        //Create Doctor
        Clinic::create($data);

        $email_temp = view('email_temps.credentials', compact('email', 'password'))->render();
        $this->sendMail($email, "Welcome to Axys Health as Clinic!", $email_temp);
        return redirect()->route('clinics')->with('success', 'Clinic Added Successfully!');
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
            return 'error';
        }
    }

    function viewClinic($id)
    {
        $clinic = json_decode(User::select('users.*', 'clinics.address', 'clinics.manager_name', 'clinics.mobile_number')->leftJoin('clinics', 'users.id', '=', 'clinics.user_id')->where('users.id', $id)->get(), true);
        $doctors = json_decode(User::select('users.*', 'doctors.clinic_id', 'doctors.middle_name', 'doctors.last_name', 'doctors.mobile_number', 'doctors.speciality')->leftJoin('doctors', 'users.id', '=', 'doctors.user_id')->where('doctors.clinic_id', $id)->get(), true);
        $clinicDevices = Device::where('clinic_id', $id)->get()->count();
        $assignDevices = Device::where('clinic_id', $id)->whereNotNull('patient_id')->count();
        return view('view_clinicProfile', compact('clinic', 'doctors', 'clinicDevices', 'assignDevices'));
    }

    function assignDevice()
    {
        $clinic_id = Auth::guard()->user()->id;
        $doctors = json_decode(Doctor::select('users.*', 'doctors.*', 'doctors.created_at as dCreatedAt', 'doctors.updated_at as dUpdatedAt')
            ->leftJoin('users', 'users.id', '=', 'doctors.user_id')->where('doctors.clinic_id', $clinic_id)
            ->get(), true);
        $devices = json_decode(Device::select('devices.*')->where('clinic_id', $clinic_id)->whereNull('doctor_id')->limit(4)->get(), true);
        return view('assigned_doctor', compact('doctors', 'devices'));
    }

    function insertMoreDevice(Request $request)
    {
        $clinic_id = Auth::guard()->user()->id;
        $deviceLimit = intval($request->quant) * 4;
        $data['devices'] = json_decode(Device::select('devices.*')->where('clinic_id', $clinic_id)->whereNull('doctor_id')
            ->limit($deviceLimit)->get(), true);

        $data['exceed_limit'] = 'no';
        if (sizeof($data['devices']) < $deviceLimit) {
            $data['exceed_limit'] = 'yes';
        }

        echo json_encode($data);
    }

    //For Clinic
    function buyDevice(Request $request)
    {
        $data = $request->all();

        if (empty($data['deviceIds'])) {
            return redirect()->route('doctor.assigndevice')->with('fail', 'No Device Available!');
        }

        $devicesIDs = $data['deviceIds'];
        $data['doctor_id'] = $data['doctor_name'];
        $data['quantity'] = $data['quantity_val'];
        $data['buyer'] = 'doctor';

        unset(
            $data['_token'],
            $data['doctor_name'],
            $data['device_quantity'],
            $data['deviceIds'],
            $data['quantity_val']
        );

        foreach ($devicesIDs as $deviceID) {
            Device::find($deviceID)->where('id', $deviceID)->update(['doctor_id' => $data['doctor_id']]);
            $data['device_id'] = $deviceID;
            Buyrecord::create($data);
        }

        Notification::create([
            'user_id' => $data['doctor_id'],
            'notifications' => 'Clinic Assigned You Devices',
            'action' => route('devices'),
            'status' => "unread",
        ]);

        return redirect()->back()->with('success', 'Device Assigned Successfully!');
    }

    //Filter All Clinics
    function filter(Request $request)
    {
        $clinics = Clinic::select('users.*', 'clinics.address', 'clinics.manager_name', 'clinics.mobile_number')->orderBy('users.created_at')->leftJoin('users', 'users.id', '=', 'clinics.user_id')
            ->whereDate('users.created_at', '>=', $request->startDate)->whereDate('users.created_at', '<=', $request->endDate)->get();

        $data['tr'] = array();
        if (!empty($clinics)) {
            foreach ($clinics as $clinic) {
                $td = array();
                $td[] = '<td>' . (isset($clinic['name']) ? $clinic['name'] : '--') . '</td>';
                $td[] = '<td><a href="#">' . (isset($clinic['manager_name']) ? $clinic['manager_name'] : '--') . '</a></td>';
                $td[] = '<td><a href="tel:' . (isset($clinic['mobile_number']) ? $clinic['mobile_number'] : 'javascript:void(0)') . '">' . (isset($clinic['mobile_number']) ? $clinic['mobile_number'] : '--') . '</a></td>';
                $td[] = '<td>' . (isset($clinic['address']) ? $clinic['address'] : '--') . '</td>';
                $td[] = '<td><a href="' . route('clinic.single', ['id' => $clinic['id']]) . '" class="btn btn-primary"><i class="las la-eye"></i></a></td>';
                $data['tr'][] = $td;
            }
        }

        echo json_encode($data);
    }

    function viewPatientAssign()
    {
        $role = Auth::guard()->user()->role;
        $patients = array();
        $devices = array();
        $clinic_id = Auth::guard()->user()->id; //Clinic ID
        $patients = json_decode(Patient::select('patients.*', 'devices.patient_id')->where('patients.clinic_id', $clinic_id)
            ->leftJoin('devices', 'devices.patient_id', '=', 'patients.id')->whereNull('devices.patient_id')
            ->get(), true);
        $devices = json_decode(Device::where('clinic_id', $clinic_id)->whereNull('patient_id')->get(), true);
        return view('clinic_assigned_patient', compact('patients', 'devices'));
    }

    function getDevices(Request $request)
    {
        // $doctor_id = json_decode(Patient::select('doctor_id')->where('id', $request->patientID)->get(), true)[0]['doctor_id'];
        $data['devices'] = array();
        $clinic_id = Auth::guard()->user()->id; //Clinic ID
        if (isset($doctor_data[0]['clinic_id'])) {
            $data['devices'] = json_decode(Device::where('clinic_id', $doctor_data[0]['clinic_id'])->whereNull('patient_id')->get(), true);
        }
        echo json_encode($data);
    }
}
