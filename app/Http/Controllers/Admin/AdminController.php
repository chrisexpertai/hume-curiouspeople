<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Option;
use App\Models\Ticket; 
 use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 use UniSharp\LaravelFilemanager\Events\ImageIsUploading;
use Illuminate\Support\Facades\Artisan;
use UniSharp\LaravelFilemanager\Events\ImageWasUploaded;

class AdminController extends Controller
{


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function dashboard()
    {
        $userCount = User::count();
         $ticketCount = Ticket::count();
  
        $latestUsers = User::latest()->limit(5)->get();
         $latestTickets = Ticket::latest()->limit(5)->get();
 
        // Fetch user data for the current month
        $userChartData = User::select(DB::raw('count(id) as user_count, DAY(created_at) as day'))
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin.dashboard', compact(
            'userCount', 'ticketCount', 
            'latestUsers','latestTickets', 'userChartData'
        ));
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return redirect()->back()->with('success', 'Cache cleared successfully.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Cache clearing failed. ' . $exception->getMessage());
        }
    }

    public function createOption(Request $request)
    {
        // Validate and store the new option in the database
        Option::create($request->all());
    
        return redirect()->back()->with('success', 'Option created successfully');
    }
    
    public function handleUpload(Request $request)
    {
        // You can still handle any custom logic or validation you need

        // Trigger UniSharp FileManager upload logic
        event(new ImageIsUploading($request));
        event(new ImageWasUploaded($request));

        // Return a response as needed
        return response()->json(['success' => true]);
    }

        public function users()
        {
            $users = User::all();
            return view('admin.users', compact('users'));
        }

        
 
        public function removeUser(Request $request, User $user)
        {
            try {
                // Delete associated tickets
                $user->tickets()->delete();
        
                // Delete associated messages (assuming you have a messages relationship)
                $user->messages()->delete();
        
                // Delete the user
                $user->delete();
        
                if ($request->ajax()) {
                    return response()->json(['message' => 'User and associated data removed successfully.']);
                } else {
                    return redirect()->route('admin.users')->with('success', 'User and associated data removed successfully.');
                }
            } catch (\Exception $exception) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'User removal failed. ' . $exception->getMessage()]);
                } else {
                    return redirect()->route('admin.users')->with('error', 'User removal failed. ' . $exception->getMessage());
                }
            }
        }
        
        
      


}
