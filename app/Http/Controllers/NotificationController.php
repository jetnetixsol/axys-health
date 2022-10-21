<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //

    function getNotifications(Request $request)
    {
        $userID = Auth::guard()->user()->id; //representative ID or sales Manager ID
        $currentDate = Date('Y-m-d'); //H:i:s
        // $before1Hr = Date('Y-m-d H:i:s', strtotime('-30 Minutes'));

        $data['current_date'] = $currentDate;
        // $data['before1Hr'] = $before1Hr;

        $notifications = json_decode(Notification::select('notifications.*')
            ->where('user_id',Auth::guard()->user()->id)
            ->where('notifications.status','unread')
            ->orderBy('notifications.created_at','ASC')
            ->get(), true);

        $notifications = json_decode(Notification::select('notifications.*')
            ->where('user_id',Auth::guard()->user()->id)
            ->where('notifications.status','read')
            ->orderBy('notifications.created_at','ASC')
            ->get(), true);    

        $data['notifications'] = $notifications;
        $data['new_message_count'] = Notification::select('notifications.*')
        ->where('user_id',Auth::guard()->user()->id)
        ->where('notifications.status','unread')->count();

        echo json_encode($data);
    }

    function openNotification(Request $request)
    {
        $notifID = $request->notifID;
        
        $update = Notification::find($notifID);
        $update->status = "read";
        $update->save();

        echo json_encode("success");
    }
}
