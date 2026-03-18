<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            // Handle unauthenticated user (optional)
            if ($request->ajax()) {
                return ['success' => 0, 'message' => 'unauthenticated'];
            }
            return redirect()->route('login');
        }

        $course_id = $request->course_id;
        $course = Course::find($course_id);

        if (!$course) {
            // Handle invalid course
            return response()->json(['success' => 0, 'message' => 'Invalid course'], 404);
        }

        $cartData = (array) session('cart');

        // Add course to cart data
        $cartData[] = [
            'hash'              => str_random(),
            'course_id'         => $course->id,
            'title'             => $course->title,
            'price'             => $course->get_price,
            'original_price'    => $course->price,
            'price_plan'        => $course->price_plan,
            'course_url'        => route('course', $course->slug),
            'thumbnail'         => media_image_uri($course->thumbnail_id)->thumbnail,
            'price_html'        => $course->price_html(false),
        ];

        session(['cart' => $cartData]);

        if ($request->ajax()) {
            return response()->json(['success' => 1, 'cart_html' => view_template_part('partials.minicart')]);
        }

        if ($request->cart_btn === 'buy_now') {
            return redirect(route('checkout'));
        }
    }


    public function addSubscription(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            // Handle unauthenticated user (optional)
            if ($request->ajax()) {
                return response()->json(['success' => 0, 'message' => 'unauthenticated']);
            }
            return redirect()->route('login');
        }

        $planId = $request->plan_id;
        $plan = SubscriptionPlan::find($planId);

        // Check if plan exists
        if (!$plan) {
            // Handle invalid plan
            return response()->json(['success' => 0, 'message' => 'Invalid subscription plan'], 404);
        }

        $cartData = (array) session('cart');

        // Add subscription plan to cart data
        $cartData[] = [
            'hash'              => str_random(),
            'title'             => $plan->title,
            'plan_id'           => $plan->id,
            'name'              => $plan->name,
            'price'             => $plan->price,
            'duration_months'   => $plan->duration_months,
        ];

        session(['cart' => $cartData]);

        if ($request->ajax()) {
            return response()->json(['success' => 1, 'cart_html' => view_template_part('partials.paycart')]);
        }

        // Redirect to checkout or any other page as needed
        return redirect()->route('subscription.checkout');
    }




    /**
     * @param Request $request
     * @return array
     *
     * Remove From Cart
     */
   public function removeCart(Request $request) {
    $cartData = (array) session('cart');
    if (isset($cartData[$request->cart_id])) {
        unset($cartData[$request->cart_id]);
    }
    session(['cart' => $cartData]);

    return ['success' => 1, 'cart_html' => view_template_part('partials.minicart')];
}


    public function checkout(){
        $title = __('checkout');
        $subscriptionPlans = SubscriptionPlan::all();
        return view(theme('checkout'), compact('title', 'subscriptionPlans'));
    }






}
