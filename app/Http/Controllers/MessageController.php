<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Course;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
class MessageController extends Controller
{
    public function index()
{
    // Fetch enrolled courses for the current user
    $enrolledCourses = Auth::user()->enrolls;

    // Fetch instructors for each enrolled course
    $instructors = [];
    foreach ($enrolledCourses as $course) {
        $instructors[$course->id] = $course->instructors;
    }
    

    // Fetch recent messages for the current user
    $recentMessages = Message::where('receiver_id', Auth::user()->id)
        ->orWhere('sender_id', Auth::user()->id)
        ->latest()
        ->get();

    return view(theme('dashboard.messages.index'), compact('enrolledCourses', 'instructors', 'recentMessages'));
}

    

        public function show($courseId, $userId)
        {
            // Fetch the course
            $course = Course::findOrFail($courseId);
        
            // Fetch the user
            $user = User::findOrFail($userId);
        
            // Fetch messages between the user and instructor for the course
            $messages = Message::where(function ($query) use ($courseId, $userId) {
                $query->where('course_id', $courseId)
                    ->where('sender_id', Auth::user()->id)
                    ->where('receiver_id', $userId);
            })->orWhere(function ($query) use ($courseId, $userId) {
                $query->where('course_id', $courseId)
                    ->where('sender_id', $userId)
                    ->where('receiver_id', Auth::user()->id);
            })->latest()->get();
        
            // Make sure there's at least one message before passing it to the view
            $message = $messages->first();
        
            return view(theme('dashboard.messages.show'), compact('course', 'user', 'messages', 'message'));
        }
        

        public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'receiver_id' => 'required|exists:users,id',
        'content' => 'required|string',
    ]);

    // Create a new message (not a reply)
    Message::create([
        'sender_id' => Auth::user()->id,
        'receiver_id' => $request->receiver_id,
        'course_id' => $request->course_id,
        'content' => $request->content,
        'is_reply' => false,
    ]);

    // Redirect back to the messages index
    return redirect()->route('messages.index')->with('success', 'Message sent successfully!');
}

public function storeReply(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'sender_id' => 'required|exists:users,id',
        'content' => 'required|string',
    ]);

    // Find the parent message
    $parentMessage = Message::findOrFail($id);

    // Determine the ID of the user who originally sent the message
    $originalSender = ($parentMessage->sender_id == Auth::user()->id) ? $parentMessage->receiver_id : $parentMessage->sender_id;

    // Create a new reply message
    Message::create([
        'sender_id' => $request->sender_id,
        'receiver_id' => $originalSender,
        'course_id' => $parentMessage->course_id,
        'content' => $request->content,
        'is_reply' => true,
    ]);

    // Redirect back to the message details page
    return redirect()->route('messages.show', ['courseId' => $parentMessage->course_id, 'userId' => $originalSender])->with('success', 'Reply sent successfully!');
}

}
