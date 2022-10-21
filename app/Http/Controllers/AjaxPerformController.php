<?php

namespace App\Http\Controllers;

use App\Models\AddHours;
use App\Models\AssignJob;
use App\Models\AssignTruck;
use App\Models\Availibility;
use App\Models\Device;
use App\Models\FollowUp;
use App\Models\lead;
use App\Models\MovingExpert;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Project;
use App\Models\ReadyToBill;
use App\Models\Request as ModelsRequest;
use App\Models\SessionRecord;
use App\Models\trucks;
use App\Models\User;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console;

class AjaxPerformController extends Controller
{
    //
    function ajaxFunction(Request $request)
    {
        $message = 'Error';
        $data = array();
        $success = 0;
        switch ($request->work) {
            case "move_archive":
                ModelsRequest::find($request->requestID)->update(["is_archive" => ($request->action === 'archive' ? 'Yes' : 'No')]);
                $success = 1;
                $message = ($request->action === 'archive' ? 'Archived' : 'Unarchived') . ' Successfully!';
                break;
            case "filter_wallet_history":
                $wallet_history = WalletHistory::where('user_id', Auth::guard()->user()->id)
                    ->whereDate('created_at', '>=', $request->startDate)
                    ->whereDate('created_at', '<=', $request->endDate)
                    ->orderBy('created_at', 'desc')
                    ->get()->toArray();

                $data['tr'] = array();
                if (!empty($wallet_history)) {
                    foreach ($wallet_history as $history) {
                        $td = array();
                        $td[] = '<td>' . (isset($history['description']) ? $history['description'] : '') . '</td>';
                        $td[] = '<td>' . (isset($history['quantity']) ? $history['quantity'] : '') . '</td>';
                        $td[] = '<td>' . (isset($history['created_at']) ? Date('d/m/Y', strtotime($history['created_at'])) : '--') . '</td>';
                        $td[] = ($history['incr_decr'] === "increment" ? '<td class="succ"><i class="las la-arrow-down"></i> + $' . $history['amount'] . '</td>' :
                            '<td class="dng"><i class="las la-arrow-up"></i> - $' . $history['amount'] . '</td>');
                        $data['tr'][] = $td;
                    }
                }
                $success = 1;
                break;
            case 'filter_billing':
                $readyToBill = ReadyToBill::select('ready_to_bills.*', 'users.name as clinic_name')
                    ->leftJoin('patients', 'patients.id', '=', 'ready_to_bills.patient_id')
                    ->leftJoin('users', 'users.id', '=', 'patients.clinic_id');
                $role = Auth::guard()->user()->role;

                if (isset($request->clinicID) && $role === 'admin') {
                    $readyToBill->where('patients.clinic_id', $request->clinicID);
                }

                if ($role === 'clinic') {
                    $clinc_id = Auth::guard()->user()->id;
                    $readyToBill->where('patients.clinic_id', $clinc_id);
                }

                if (isset($request->dateRange)) {
                    $formattedDate = explode(" - ", $request->dateRange);
                    $fromDate = Date('Y-m-d', strtotime($formattedDate[0]));
                    $toDate = Date('Y-m-d', strtotime($formattedDate[1]));
                    $readyToBill->whereDate('ready_to_bills.from', '>=', $fromDate)
                        ->whereDate('ready_to_bills.from', '<=', $toDate);
                }

                $readyToBill = $readyToBill->orderBy('ready_to_bills.from', 'desc')->get()->toArray();
                $data['tr'] = array();
                if (!empty($readyToBill)) {
                    foreach ($readyToBill as $bill) {
                        $td = array();
                        if ($role === "admin") {
                            $td[] = '<td>' . (isset($bill['from']) ? Date('Y-m-d', strtotime($bill['from'])) : '--') . '</td>';
                            $td[] = '<td>' . (isset($bill['to']) ? Date('y-m-d', strtotime($bill['to'])) : '--') . '</td>';
                            $td[] = '<td>' . (isset($bill['clinic_name']) ? $bill['clinic_name'] : '--') . '</td>';
                            $td[] = '<td>' . ($bill['device_ids']) . '</td>';
                            $days = isset($bill['from']) && isset($bill['to']) ? intval(abs(Date(strtotime($bill['to'] . '+1 day')) - Date(strtotime($bill['from']))) / 86400) : 0;
                            $td[] = '<td>' . $days . ' Day' . ($days > 1 ? 's' : '') . '</td>';
                            $td[] = '<td>' . $bill['paid'] . '$</td>';
                            $td[] = '<td>' . ($bill['charges'] - $bill['paid']) . '$</td>';
                            $td[] = '<td>' . ($bill['payment_status'] === "paid" ? '<span class="active-paid">paid</span>' : '<span class="active-unpaid">unpaid</span>') . '</td>';
                        } else if ($role === "clinic") {
                            $td[] = '<td>' . Date('Y-m-d', strtotime($bill['from'])) . '</td>';
                            $td[] = '<td>' . $bill['device_ids'] . '</td>';
                            $td[] = '<td>' . $bill['charges'] . '$</td>';
                            $td[] = '<td>' . $bill['paid'] . '$</td>';
                            $td[] = '<td>' . ($bill['charges'] - $bill['paid']) . '$</td>';
                            //-------------  Payment Status
                            if ($bill['payment_status'] === 'paid') {
                                $td[] = '<td><span class="active-paid">paid</span></td>';
                            } elseif ($bill['payment_status'] === 'unpaid') {
                                $td[] = '<span class="active-unpaid">unpaid</span>';
                            }
                            //-------------- Action
                            if ($bill['payment_status'] === 'unpaid' && isset($stripeConfig["stripe_key"])) {
                                $td[] = '<td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paid_modal" onclick="(function(){ $("#b_i").val("' . urlencode(base64_encode($bill["id"])) . '"); }())">Pay Now</button></td>';
                            } elseif ($bill['payment_status'] === 'unpaid' && !isset($stripeConfig["stripe_key"])) {
                                $td[] = '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#keys_alert">Pay Now</button>';
                            } elseif ($bill['payment_status'] === 'paid') {
                                $td[] = '<button type="button" class="btn btn-primary"><i class="fa fa-check"></i>Paid</button>';
                            }
                        }
                        $data['tr'][] = $td;
                    }
                }
                $success = 1;
                break;
            case 'active_device_filter':
                $clinicID = Auth::guard()->user()->id;
                $activeDevices = Device::where('clinic_id', $clinicID)
                    ->where('session', 'start')->get();


                $formattedDate = explode(" - ", $request->dateRange);
                $fromDate = Date('Y-m-d', strtotime($formattedDate[0]));
                $toDate = Date('Y-m-d', strtotime($formattedDate[1]));

                $active_devices = array();
                if (!empty($activeDevices)) {
                    foreach ($activeDevices as $activeDevice) {
                        $session_record = json_decode(SessionRecord::where('device_id', $activeDevice['serial_number'])
                            ->where('patient_id', $activeDevice['patient_id'])
                            ->where('status', 'active')
                            ->whereDate("date", ">=", $fromDate)
                            ->whereDate("date", "<=", $toDate)
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
                break;
            case 'get_clinic_details':
                $user = User::select('users.name', 'users.email', 'clinics.*')
                    ->leftJoin('clinics', 'clinics.user_id', '=', 'users.id')
                    ->where('users.id', $request->clinicID)
                    ->get();
                $success = 1;
                $data = array('user_data' => $user);
                break;
        }
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message]);
    }
}
