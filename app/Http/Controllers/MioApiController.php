<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\SessionRecord;
use App\Models\TestRecord;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MioApiController extends Controller
{
    //
    function deviceStatus(Request $request)
    {
        // var_dump($request->all(),$request['deviceId']); die;
        if (isset($request['deviceId'])) {
            Device::where('serial_number', $request['deviceId'])
                ->update([
                    'signal' => isset($request['status']['sig']) ? $request['status']['sig'] : null,
                    'battery' => isset($request['status']['bat']) ?  $request['status']['bat'] : null,
                    // 'imsi' => $request['imsi'],
                    // 'iccid' => $request['iccid']
                    // 'createdAt' =>$request['createdAt'],
                ]);
        }
        // $test['record'] = json_encode($request->all());
        //TestRecord::create($test); //For Test Purpose
    }

    function telemetryData(Request $request)
    {
        // var_dump($request->all()); die;
        // if(isset($request[''])){

        // }
        $data = $request->all();
        // var_dump($request->all(),'data',$data['deviceId']); die;
        if (isset($data['deviceId'])) {
            $device = Device::where('serial_number', $data['deviceId'])->get();
            if (isset($device[0]->session) && $device[0]->session === 'start' && !empty($device[0]->patient_id)) {
                $irregular_heartbeat = isset($data['data']['ihb']) && $data['data']['ihb'] != false ? $data['data']['ihb'] : null;
                $pulse_rate = isset($data['data']['pul']) && $data['data']['pul'] != false ? $data['data']['pul'] : null;
                $ovit = isset($data['data']['ovit']) && $data['data']['ovit'] != false ? $data['data']['ovit'] : null;
                $systolic = isset($data['data']['sys']) && $data['data']['sys'] != false ? $data['data']['sys'] : null;
                $diastolic = isset($data['data']['dia']) && $data['data']['dia'] != false ? $data['data']['dia'] : null;
                $ops = isset($data['data']['ops']) && $data['data']['ops'] != false ? $data['data']['ops'] : null;
                $ts = isset($data['data']['ts']) && $data['data']['ts'] != false ? $data['data']['ts'] : null;

                SessionRecord::create([
                    'device_id' => $data['deviceId'], 'patient_id' => $device[0]->patient_id,
                    'irregular_heartbeat' => $irregular_heartbeat, 'pulse_rate' => $pulse_rate, 'ovit' => $ovit,
                    'ovit' => $ovit, 'systolic' => $systolic, 'diastolic' => $diastolic, 'ops' => $ops, 'ts' => $ts, 'date' => Date('Y-m-d')
                ]);

                //$test['record'] = json_encode($request->all());
                //TestRecord::create($test); //For Test Purpose
            }
        }
    }
}
