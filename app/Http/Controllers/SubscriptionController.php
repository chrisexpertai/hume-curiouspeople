<?php


namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::all();

  // Ensure the user is authenticated
  if (Auth::check()) {
    // Assuming Auth::user() returns the authenticated user
    $user = Auth::user();
    $userPlan = $user->subscription_plan ? SubscriptionPlan::find($user->subscription_plan) : null;
} else {
    $userPlan = null;
}
        return view('subscription-plans.index', ['plans' => $plans, 'userPlan' => $userPlan]);
    }


    public function subscription()
    {
        $plans = SubscriptionPlan::all();

  // Ensure the user is authenticated
  if (Auth::check()) {
    // Assuming Auth::user() returns the authenticated user
    $user = Auth::user();
    $userPlan = $user->subscription_plan ? SubscriptionPlan::find($user->subscription_plan) : null;
} else {
    $userPlan = null;
}
        return view('admin.subscription.index', ['plans' => $plans, 'userPlan' => $userPlan]);
    }

    public function subscriptionUsers(Request $request)
    {
        // Initialize query builder
        $query = User::whereNotNull('subscription_plan')->with('subscriptionPlan');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sorting functionality
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_dir', 'asc');

        // Validate and sanitize sort direction
        $sortDirection = strtolower($sortDirection) === 'desc' ? 'desc' : 'asc';

        // Apply sorting
        if (in_array($sortBy, ['id', 'name', 'email', 'subscription_plan', 'subscription_start_date', 'subscription_end_date'])) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            // Default sorting by user ID if invalid column provided
            $query->orderBy('id', 'asc');
        }

        // Pagination
        $subscribedUsers = $query->paginate(10);

        return view('admin.subscription.users', compact('subscribedUsers'));
    }




    public function show($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        // Ensure the user is authenticated
        if (Auth::check()) {
            // Assuming Auth::user() returns the authenticated user
            $user = Auth::user();
            $userPlan = $user->subscription_plan ? SubscriptionPlan::find($user->subscription_plan) : null;

            return view('subscription-plans.show', ['plan' => $plan, 'userPlan' => $userPlan, 'user' => $user]);
        } else {
            $userPlan = null;
            $user = null;

            return view('subscription-plans.show', ['plan' => $plan, 'userPlan' => $userPlan, 'user' => $user]);
        }
    }



    public function create()
    {

        return view('admin.subscription.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'badge' => 'required|string|max:255',
            'duration_months' => 'required|integer|min:1', // Add validation for duration_months
            'includes' => 'nullable|string', // Add validation for includes
        ]);

        // Create a new subscription plan
        $plan = SubscriptionPlan::create($validatedData);

        // Redirect to the plan's details page
        return redirect()->route('subscription.index', ['id' => $plan->id]);
    }



    public function edit(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        return view('admin.subscription.edit', compact('plan'));
    }
    public function update(Request $request, $id)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'badge' => 'required|string|max:255',
        'duration_months' => 'required|integer|min:1',
        'includes' => 'nullable|string', // Add validation for includes

        // Add other validation rules as needed
    ]);

    // Update the subscription plan
    $plan = SubscriptionPlan::findOrFail($id);
    $plan->update($validatedData);

    // Redirect to the plan's details page
    return redirect()->route('subscription-plans.show', ['id' => $plan->id]);
}


    public function destroy($id)
    {
        // Delete the subscription plan
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();

        // Redirect to the index page
        return redirect()->route('subscription.index');
    }


    public function assignSubscriptionPlan($planId)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $plan = SubscriptionPlan::findOrFail($planId);

            // Assuming Auth::user() returns the authenticated user
            $user = Auth::user();

            // Check if the user already has an active subscription



            // Assign the new plan to the user
            $user->subscription_plan = $plan->id;
            $user->subscription_start_date = now();
            $user->subscription_end_date = now()->addMonths($plan->duration_months);
            $user->plan_expired = false; // Reset expired flag
            $user->plan_expired = false; // Reset expired flag (if needed)
            $user->save();

            // Redirect back to the plan's details page
            return redirect()->route('subscription-plans.show', ['id' => $user->subscription_plan])
            ->with('error', 'You already have an active subscription.');

                } else {
            // Redirect or handle the case where the user is not authenticated
            return redirect()->route('login')->with('error', 'Please log in to assign a subscription plan.');
        }
    }


    public function cancelSubscription()
{
    // Check if the user is authenticated
    if (Auth::check()) {
        // Assuming Auth::user() returns the authenticated user
        $user = Auth::user();

        // Check if the user has an active subscription
        if ($user->isSubscribed()) {
            // Expire the existing subscription
            $user->expireSubscription();

            return redirect()->route('subscription-plans.show', ['id' => $user->subscription_plan])
                ->with('success', 'Subscription canceled successfully.');
        } else {
            return redirect()->route('subscription-plans.index')
                ->with('error', 'You do not have an active subscription to cancel.');
        }
    } else {
        // Redirect or handle the case where the user is not authenticated
        return redirect()->route('login')->with('error', 'Please log in to cancel your subscription.');
    }
}
public function mySubscription()
{
    // Fetch the authenticated user
    $user = Auth::user();

    $userPlan = $user->subscription_plan ? SubscriptionPlan::find($user->subscription_plan) : null;

    $plans = SubscriptionPlan::all();

    // Ensure the user has a subscription plan assigned
    if ($user->subscription_plan) {
        // Retrieve the user's subscription with eager loading
        $subscription = SubscriptionPlan::with('users')->find($user->subscription_plan);

        // Pass the subscription data to the view
        return view('front.dashboard.my_subscriptions', compact('subscription', 'plans', 'userPlan'));
    } else {
        // No subscription plan assigned for the user
        return view('front.dashboard.my_subscriptions')->with('subscription', null);
    }
}





    public function renewSubscription($planId)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user has an expired subscription
            if ($user->hasSubscriptionExpired()) {
                $plan = SubscriptionPlan::findOrFail($planId);

                // Renew the subscription
                $user->subscription_plan = $plan->id;
                $user->subscription_start_date = now();
                $user->subscription_end_date = now()->addMonths($plan->duration_months);
                $user->plan_expired = false; // Reset expired flag
                $user->save();

                return redirect()->route('subscription-plans.show', ['id' => $plan->id])
                    ->with('success', 'Subscription renewed successfully.');
            } else {
                return redirect()->route('subscription-plans.show', ['id' => $planId])
                    ->with('error', 'You do not have an expired subscription to renew.');
            }
        } else {
            return redirect()->route('login')->with('error', 'Please log in to renew your subscription.');
        }
    }
   // SubscriptionController.php
 // SubscriptionController.php

public function changeSubscriptionPlan($planId)
{
    // Check if the user is authenticated
    if (Auth::check()) {
        $newPlan = SubscriptionPlan::findOrFail($planId);

        // Assuming Auth::user() returns the authenticated user
        $user = Auth::user();

        // Check if the user already has an active subscription
        if ($user->isSubscribed()) {
            // Expire the existing subscription
            $user->expireSubscription();

            // Assign the new plan to the user
            $user->subscription_plan = $newPlan->id;
            $user->subscription_start_date = now();
            $user->subscription_end_date = now()->addMonths($newPlan->duration_months);
            $user->plan_expired = false; // Reset expired flag
            $user->save();

            return redirect()->route('subscription-plans.show', ['id' => $newPlan->id])
                ->with('success', 'Subscription plan changed successfully.');
        } else {
            // User does not have an active subscription, proceed to assign the new plan
            $user->subscription_plan = $newPlan->id;
            $user->subscription_start_date = now();
            $user->subscription_end_date = now()->addMonths($newPlan->duration_months);
            $user->plan_expired = false; // Reset expired flag
            $user->save();

            return redirect()->route('subscription-plans.show', ['id' => $newPlan->id])
                ->with('success', 'Subscription plan assigned successfully.');
        }
    } else {
        // Redirect or handle the case where the user is not authenticated
        return redirect()->route('login')->with('error', 'Please log in to change your subscription plan.');
    }
}



}
