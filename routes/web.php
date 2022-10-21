<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallCenterController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\CustomerCareController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OperationManagerController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ReadyToBillController;
use App\Http\Controllers\RemindersController;
use App\Http\Controllers\AjaxPerformController;
use App\Http\Controllers\SalesManagerController;
use App\Http\Controllers\SalesRepresentativeController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WarehouseController;
use App\Models\Clinic;
use App\Models\Installer;
use App\Models\Product;
use App\Models\ReadyToBill;
use App\Models\SessionRecord;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Prophecy\Call\CallCenter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('getCSRF',function(){
//     echo csrf_token();
// });


Route::middleware(['auth:web', 'preventBackHistory', 'clinicBalance'])->group(function () {
    //Dashboard
    //->middleware('role.access:admin,salemanager,salereps,callcenter,operationmanager')

    //Dashboard Controller
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/buy-monitor', [DashboardController::class, 'buyMonitor'])->name('buy.monitor');
    Route::post('/add-more-device', [DashboardController::class, 'insertMoreDevice'])->name('more.device.add');
    Route::post('/request-devices', [DashboardController::class, 'requestDevice'])->name('request.device'); //Request Device
    Route::post('/assign-device', [DashboardController::class, 'assignDevice'])->name('assign.device');
    Route::post('/buy-device', [DashboardController::class, 'buyDevice'])->name('buy.device');

    //Patinets
    Route::get('/all-patients', [PatientController::class, 'index'])->name('patients');
    Route::get('/add-patient', [PatientController::class, 'addPatient'])->name('add.patient');
    Route::post('/insert-patient', [PatientController::class, 'insertPatient'])->name('insert.patient');
    Route::post('/get-clinic-doctors', [PatientController::class, 'getDoctors'])->name('getClinic.doctors');
    Route::post('/filter-patients', [PatientController::class, 'filter'])->name('patients.filter');

    //Doctor
    Route::get('/all-doctors', [DoctorController::class, 'index'])->name('doctors');
    Route::get('/add-doctor', [DoctorController::class, 'addDoctor'])->name('add.doctor');
    Route::post('/insert-doctor', [DoctorController::class, 'insert'])->name('insert.doctor');
    Route::get('/view-doctor-profile/{id}', [DoctorController::class, 'viewDoctor'])->name('doctor.single');
    Route::get('/assign-to-patient', [DoctorController::class, 'viewPatientAssign'])->name('patient.assign');
    Route::post('/assign-patient', [DoctorController::class, 'assignPatient'])->name('patient.assign.device');
    Route::post('/get-doctor-devices', [DoctorController::class, 'getDevices'])->name('patient.get.devices');
    Route::post('/insert-remarks', [DoctorController::class, 'insertRemarks'])->name('patient.remarks');
    Route::get('/end-session/{id}', [DoctorController::class, 'endSession'])->name('session.end');
    Route::post('/filter-doctors', [DoctorController::class, 'filter'])->name('doctor.filter');

    //Clinic 
    Route::get('/all-clinics', [ClinicController::class, 'index'])->name('clinics');
    Route::get('/add-clinic', function () {
        return view('add_clinic');
    })->name('add.clinic');
    Route::post('/insert-clinic', [ClinicController::class, 'insert'])->name('insert.clinic');
    Route::get('/view-clinic-profile/{id}', [ClinicController::class, 'viewClinic'])->name('clinic.single');
    Route::get('/assign-doctor-device', [ClinicController::class, 'assignDevice'])->name('doctor.assigndevice');
    Route::post('/add-more-doctor-device', [ClinicController::class, 'insertMoreDevice'])->name('doctor.device.add');
    Route::post('/buy-doctor-device', [ClinicController::class, 'buyDevice'])->name('doctor.buydevice');
    Route::post('/filter-clinic', [ClinicController::class, 'filter'])->name('clinic.filter');
    Route::get('/clinic-assign-to-patient', [ClinicController::class, 'viewPatientAssign'])->name('clinic.patient.assign');

    //Deivce
    Route::get('/all-devices', [DeviceController::class, 'index'])->name('devices');
    Route::post('/import-devices', [DeviceController::class, 'uploadCsv'])->name('import');
    Route::get('/view-single-device/{id}', [DeviceController::class, 'viewDevice'])->name('device.single');
    Route::post('/fetchDevices', [DeviceController::class, 'fetchDeivces'])->name('fetch.devices');
    Route::post('/filter-devices', [DeviceController::class, 'filter'])->name('devices.filter');
    Route::post('/search-device', [DeviceController::class, 'searchDevice'])->name('devices.search');

    //Reminders
    Route::get('/reminders', [RemindersController::class, 'reminders'])->name('reminders');
    Route::post('/insert-reminder', [RemindersController::class, 'insertReminder'])->name('insertReminder');

    //Ready To Bills
    Route::get('/ready-to-bill', [ReadyToBillController::class, 'index'])->name('readytobill');
    Route::post('/generate-bill', [ReadyToBillController::class, 'generateBill'])->name('generateBill');
    Route::post('/filter-bills', [ReadyToBillController::class, 'filter'])->name('bills.filter');

    //Notification 
    Route::post('/get-notification', [NotificationController::class, 'getNotifications'])->name('initNotif');
    Route::post('/open-notification', [NotificationController::class, 'openNotification'])->name('openNotif');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


    ///Billing New Asad
    Route::get('/billing', [DashboardController::class, 'billing'])->name('billing');
    Route::get('/admin-billing', [ReadyToBillController::class, 'admin_billing'])->name('admin-billing');
    // Route::get('/search_device', [DashboardController::class, 'search_device'])->name('search_device');

    //Access by both admin and clinic
    Route::get('/wallet', [DashboardController::class, 'viewWallet'])->name('wallet');
    Route::post('/topup-wallet', [DashboardController::class, 'topupWallet'])->name('wallet.topup');

    Route::post('/perform-ajax', [AjaxPerformController::class, 'ajaxFunction'])->name('ajax.perform');

    Route::post('/stripe-pay', [DashboardController::class, 'stripePay'])->name('stripe.pay');

    //Config Page
    // ->middleware('role.access:admin,salemanager,salereps,callcenter,operationmanager')
    Route::middleware('role.access:admin')->get('/configuration', [DashboardController::class, 'config'])->name('config');
    Route::post('/change-password', [DashboardController::class, 'change_password'])->name('password.change');
    Route::post('/update-stripe-keys', [DashboardController::class, 'updateKeys'])->name('config.keys');
});

Route::middleware(['guest:web', 'preventBackHistory'])->group(function () {
    //login Page Here
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'signin'])->name('signin');
});



// Route::get('insert', function () {
//     User::create([
//         'name' => 'Admin',
//         'email' => 'admin@onlinespa.com',
//         'password' => Hash::make('admin123'),
//         'role' => 'admin',
//     ]);
// });

Route::get('/test', function () {
    // echo getenv('MAIL_HOST');
    // return view('email_temps.credentials')->render();
    // $startTimeStamp = strtotime("2011/07/01");
    // $endTimeStamp = strtotime("2011/07/17" . '+1 day');

    // $timeDiff = abs($endTimeStamp - $startTimeStamp);

    // $numberDays = $timeDiff / 86400;  // 86400 seconds in one day

    // and you might want to convert to integer
    // echo $numberDays = intval($numberDays);


    // $fromDate = Date('Y-m-d', strtotime($session_record[$keys[0]]['date'])); //initial 
    // $toDate = Date('Y-m-d', strtotime($session_record[$keys[sizeof($keys) - 1]]['date'] . '+1 day'));

    // $fromDate = Date('Y-m-d');
    // $toDate = Date('Y-m-d', strtotime($fromDate . '+44 days'));
    // echo $fromDate." ".$toDate." ".(40 + -30);

    // $pervDate = Date("Y-m-d", strtotime('-151 days'));
    // dd($pervDate);

    // for ($i = 0; $i <= 150; $i++) {
    //     $date = Date("Y-m-d", strtotime("2022-04-18 +" . $i . "day"));
    //     SessionRecord::where('device_id', '11F215200214')->where('date', $date)->update(["status" => "active"]);
    // }
    // dd("reverted");

    //
    // for ($i = 0; $i <= 150; $i++) {
    //     $date = Date("Y-m-d", strtotime("2022-04-18 +" . $i . "day"));
    //     SessionRecord::create(
    //         array(
    //             "device_id" => "11F215200214",
    //             "patient_id" => 1,
    //             "pulse_rate" => 59 + $i,
    //             "ovit" => 2751,
    //             "systolic" => 60 + $i,
    //             "diastolic" => 90 + $i,
    //             "ops" => "Verizon Wireless",
    //             "ts" => 1655262600,
    //             "date" => $date,
    //             "created_at" => Date("Y-m-d H:i", strtotime($date))
    //         )
    //     );
    // }
});
