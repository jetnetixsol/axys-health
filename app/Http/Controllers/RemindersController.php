<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Notification;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemindersController extends Controller
{
    //
    function reminders()
    {
        $user_id = Auth::guard()->user()->id;
        $reminders = Reminder::orderBy('start_date','ASC')->where('user_id',$user_id)->get();
        return view('reminders',compact('reminders'));
    }

    function insertReminder(Request $request)
    {
        //insert Reminders
        $data = $request->all();
        $data['user_id'] = Auth::guard('web')->user()->id;
        unset($data['_token']);
        Reminder::create($data);
        return redirect()->route('reminders')->with('success', 'Reminders added Successfully!');
    }
}
