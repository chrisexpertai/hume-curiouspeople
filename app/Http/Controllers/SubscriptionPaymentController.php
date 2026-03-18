<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPayment;
use Illuminate\Support\Facades\Auth;

class SubscriptionPaymentController extends Controller
{


public function index()
{
    $query = SubscriptionPayment::query()->with('user');

    // Search functionality
    if (Request::has('search')) {
        $search = Request::input('search');
        $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });
    }

    // Sorting functionality
    if (Request::has('sort_by')) {
        $sort_by = Request::input('sort_by');
        if ($sort_by == 'newest') {
            $query->latest();
        } elseif ($sort_by == 'oldest') {
            $query->oldest();
        } elseif ($sort_by == 'success') {
            $query->where('status', 'success');
        } elseif ($sort_by == 'failed') {
            $query->where('status', 'failed');
        }
    }

    $subscriptionPayments = $query->paginate(10); // Paginate the results

    return view('admin.subscription-payments.index', compact('subscriptionPayments'));
}




    public function updateStatus(Request $request)
    {
        $paymentId = $request->payment_id;
        $status = $request->status;

        $payment = SubscriptionPayment::findOrFail($paymentId);
        $payment->status = $status;
        $payment->save();

        // If the status is updated to "success", update corresponding payments' status
        if ($status === 'success') {
            $payment->user->payments()->where('status', '!=', 'success')->update(['status' => 'success']);
        }

        return redirect()->route('admin.subscription-payments')->with('success', 'Payment status updated successfully.');
    }
}
