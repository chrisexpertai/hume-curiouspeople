<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // User side


// Display all tickets created by the user
public function userViewTickets()
{
    // Use the 'auth' middleware to ensure the user is authenticated
    $this->middleware('auth');

    // Now you can safely access the authenticated user's tickets
    $userTickets = Auth::user()->tickets;

    return view('front.tickets.index', compact('userTickets'));
}


    // Display the form to create a ticket
    public function createTicketForm()
    {
        return view('front.tickets.create');
    }

 public function sendNotification($userIds, $message, $userLink, $adminLink, $type)
{
    // Create and store notifications for users with the user link
    foreach ($userIds as $userId) {
        Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'link' => $userLink,
            'type' => $type,
        ]);
    }

    // Check if admin and send a separate notification with the admin link
    $admins = User::where('user_type', 'admin')->get();
    foreach ($admins as $admin) {
        Notification::create([
            'user_id' => $admin->id,
            'message' => $message,
            'link' => $adminLink,
            'type' => $type,
        ]);
    }

 }

 public function createTicket(Request $request)
{
    $this->validate($request, [
        'subject' => 'required|string',
        'content' => 'required|string',
    ]);

    // Create the ticket
    $ticket = Auth::user()->tickets()->create([
        'subject' => $request->input('subject'),
        'content' => $request->input('content'),
    ]);

    // Get the ticket ID
    $ticketId = $ticket->id;

    // Generate links for users and admins
    $userLink = route('user.tickets.view', $ticketId);
    $adminLink = route('admin.tickets.view', $ticketId);

    // Send notification to users with the user link
    $userId = auth()->id();
    $userIds = [$userId];
    $message = 'You have received a new ticket!';
    $type = 'new_ticket';
    $this->sendNotification($userIds, $message, $userLink, $adminLink, $type);

    return redirect()->route('user.tickets.view', $ticketId)->with('success', 'Ticket created successfully.');
}




    // View a ticket
    public function viewTicket(Ticket $ticket)
    {
        $replies = $ticket->replies;
        return view('front.tickets.view', compact('ticket', 'replies'));
    }

    // Respond to a ticket
    public function respondToTicket(Request $request, Ticket $ticket)
    {
        $this->validate($request, [
            'reply' => 'required|string',
        ]);

        // Create a reply for the ticket
        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('reply'),
        ]);

        return redirect()->route('user.tickets.view', $ticket->id)->with('success', 'Reply sent successfully.');
    }

    // Admin side

    // Display all tickets for the admin
    public function adminViewTickets(Request $request)
    {
        $query = Ticket::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('subject', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        }

        // Sorting functionality
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_dir', 'desc');

        // Adjust sorting options
        switch ($sortBy) {
            case 'newest':
                $sortBy = 'created_at';
                break;
            case 'oldest':
                $sortBy = 'created_at';
                $sortDirection = 'asc'; // Change direction for oldest
                break;
            case 'subject_asc':
                $sortBy = 'subject';
                break;
            case 'subject_desc':
                $sortBy = 'subject';
                $sortDirection = 'desc'; // Change direction for subject_desc
                break;
            default:
                $sortBy = 'created_at';
                break;
        }

        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $tickets = $query->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }





// View a specific ticket from the admin perspective
public function adminViewTicket(Ticket $ticket)
{
    // Make sure to load the user relationship along with the ticket
    $ticket->load('user');

    $replies = $ticket->replies;
    return view('admin.tickets.view', compact('ticket', 'replies'));
}

    // Create a reply for a ticket from the admin perspective

// Create a reply for a ticket from the admin perspective
public function adminRespondToTicket(Request $request, Ticket $ticket)
{
    $this->validate($request, [
        'reply' => 'required|string',
    ]);

    // Make sure to load the user relationship along with the ticket
    $ticket->load('user');

    // Create a reply for the ticket
    $ticket->replies()->create([
        'user_id' => auth()->id(), // Assuming you want to attribute the reply to the admin
        'content' => $request->input('reply'),
    ]);

    return redirect()->route('admin.tickets.view', $ticket->id)->with('success', 'Admin reply sent successfully.');
}
}
