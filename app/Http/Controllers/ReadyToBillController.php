<?php

namespace App\Http\Controllers;

use App\Models\ReadyToBill;
use App\Models\User;
use Illuminate\Http\Request;

class ReadyToBillController extends Controller
{
    //
    function index()
    {
        $readyBills = json_decode(ReadyToBill::select('ready_to_bills.*', 'patients.full_name')
            ->leftJoin('patients', 'patients.id', '=', 'ready_to_bills.patient_id')
            ->where('status', 'ready')
            ->orderBy('created_at', 'DESC')->get(), true);

        $generatedBills = json_decode(ReadyToBill::select('ready_to_bills.*', 'patients.full_name')
            ->leftJoin('patients', 'patients.id', '=', 'ready_to_bills.patient_id')
            ->where('status', 'generated')
            ->orderBy('created_at', 'DESC')->get(), true);

        return view('ready_bills', compact('readyBills', 'generatedBills'));
    }

    function generateBill(Request $request)
    {
        $billIDs = $request->billIDs;
        foreach ($billIDs as $billID) {
            ReadyToBill::find($billID)->update(['status' => 'generated', 'code' => $request->blink_code, 'charges' => $request->charges]);
        }
        return redirect()->back()->with('success', 'Bill Generated Successfully!');
    }


    //Filter All Devices
    function filter(Request $request)
    {
        $table = $request->table;

        $data['tr'] = array();

        if ($table === 'ready_bill_table') {
            $readyBills = json_decode(ReadyToBill::select('ready_to_bills.*', 'patients.full_name')
                ->leftJoin('patients', 'patients.id', '=', 'ready_to_bills.patient_id')
                ->where('status', 'ready')->whereDate('ready_to_bills.created_at', '>=', $request->startDate)
                ->whereDate('ready_to_bills.created_at', '<=', $request->endDate)
                ->orderBy('ready_to_bills.created_at', 'DESC')->get(), true);
            if (!empty($readyBills)) {
                foreach ($readyBills as $readyBill) {
                    $td = array();
                    $td[] = '<td class="d-flex">
                                <div class="multiple-select2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input billIds" name="billIds[]" id="" value="' . $readyBill['id'] . '">
                                    </div>
                                </div>
                            </td>';
                    $td[] = '<td>' . (isset($readyBill['from']) ? Date('d-M-Y', strtotime($readyBill['from'])) : '--') . '</td>';
                    $td[] = '<td>' . (isset($readyBill['to']) ? Date('d-M-Y', strtotime($readyBill['to'])) : '--') . '</td>';
                    $td[] = '<td>' . (isset($readyBill['patient_id']) ? '#' . $readyBill['patient_id'] : '--') . '</td>';
                    $readyBill['patient_name'] = (isset($readyBill['full_name']) ? $readyBill['full_name'] . ' ' : '');
                    $td[] = '<td><a>' . (isset($readyBill['patient_name']) ? $readyBill['patient_name'] : "--") . '</a></td>';
                    $td[] = '<td class="cn">16</td>';
                    $encRBill = json_encode($readyBill);
                    $td[] = "<td><button type='button' class='btn btn-primary' onclick='showGenBill(" . '&#39;' . $encRBill . '&#39;' . ",'single')'>Generate Bill</button></td>";
                    $data['tr'][] = $td;
                }
            }
        } elseif ($table === 'generated_bill_table') {
            $generatedBills = json_decode(ReadyToBill::select('ready_to_bills.*', 'patients.full_name')
                ->leftJoin('patients', 'patients.id', '=', 'ready_to_bills.patient_id')
                ->where('status', 'generated')->whereDate('ready_to_bills.created_at', '>=', $request->startDate)
                ->whereDate('ready_to_bills.created_at', '<=', $request->endDate)
                ->orderBy('ready_to_bills.created_at', 'DESC')->get(), true);

            if (!empty($generatedBills)) {
                foreach ($generatedBills as $generatedBill) {
                    $td = array();
                    $td[] = '<td class="d-flex">
                                <div class="multiple-select2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input billIds" name="billIds[]" id="" value="' . $generatedBill['id'] . '">
                                    </div>
                                </div>
                            </td>';
                    $td[] = '<td>' . (isset($generatedBill['from']) ? Date('d-M-Y', strtotime($generatedBill['from'])) : '--') . '</td>';
                    $td[] = '<td>' . (isset($generatedBill['to']) ? Date('d-M-Y', strtotime($generatedBill['to'])) : '--') . '</td>';
                    $td[] = '<td>' . (isset($generatedBill['patient_id']) ? '#' . $generatedBill['patient_id'] : '--') . '</td>';
                    $generatedBill['patient_name'] = (isset($generatedBill['full_name']) ? $generatedBill['full_name'] . ' ' : '');
                    $td[] = '<td><a>' . (isset($generatedBill['patient_name']) ? $generatedBill['patient_name'] : "--") . '</a></td>';
                    $td[] = '<td class="cn">16</td>';
                    $td[] = '<td>' . (isset($generatedBill['code']) ? $generatedBill['code'] : "--") . '</td>';
                    $td[] = '<td>' . (isset($generatedBill['charges']) ? $generatedBill['charges'] : "--") . '</td>';
                    $data['tr'][] = $td;
                }
            }
        }

        echo json_encode($data);
    }

    function admin_billing()
    {
        $bills = ReadyToBill::select("ready_to_bills.*", "users.name as clinic_name")
            ->leftJoin('patients', 'ready_to_bills.patient_id', '=', 'patients.id')
            ->leftJoin('users', 'users.id', '=', 'patients.clinic_id')
            ->orderBy('ready_to_bills.created_at', 'desc')
            ->get()->toArray();

        $clinics = User::where('role', 'clinic')->get();

        return view('admin-billing', compact('bills', 'clinics'));
    }
}
