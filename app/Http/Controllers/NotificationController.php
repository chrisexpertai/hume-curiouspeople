<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller


{
    public function index()
    {
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->get();
        return view('notifications.index', compact('notifications'));
    }


    public function Adminindex()
    {
        // Retrieve all admin notifications
        $adminNotifications = Notification::whereHas('user', function ($query) {
            $query->where('user_type', 'admin');
        })->orderBy('created_at', 'desc')->get();

        // Render the admin notifications view and pass the notifications
        return view('admin.notifications.index', compact('adminNotifications'));
    }
    public function sendNotification(Request $request)
    {
        $user = auth()->user(); // Assuming you're sending notifications to authenticated users
        $message = $request->input('message');
        $type = $request->input('type');

        // Create and store notification
        $notification = new Notification();
        $notification->user_id = $user->id;
        $notification->message = $message;
        $notification->type = $type;
        $notification->save();

        return response()->json(['message' => 'Notification sent successfully'], 200);
    }

    // Method to mark a notification as read
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read = true;
        $notification->save();

        return redirect()->route('notifications.index')->with('success', 'Notification marked as read successfully!');
    }

    // Method to clear all notifications for a user
    public function clearNotifications()
    {
        auth()->user()->notifications()->delete();

        return redirect()->route('notifications.index')->with('success', 'All notifications cleared successfully!');
    }
    // Method to clear all notifications for the admin
        public function clearAdminNotifications()
        {
            // Ensure the authenticated user is an admin
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }

            // Delete all notifications for admins
            Notification::where('user_type', 'admin')->delete();

            return redirect()->route('admin.notifications')->with('success', 'All admin notifications cleared successfully!');
        }


        public function markAdminNotificationsAsRead()
        {
            // Get the authenticated admin user
            $admin = Auth::user();

            // Update the read field of admin notifications to 1
            $admin->notifications()->where('read', 0)->update(['read' => 1]);

            // Redirect back or wherever you need to
            return redirect()->back()->with('success', 'All notifications marked as read.');
        }
}
