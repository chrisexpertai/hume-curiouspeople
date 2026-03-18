<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated($request, $user)
    {
        if ($request->_redirect_back_to) {
            return redirect($request->_redirect_back_to);
        }

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin'));
        } else {
            return redirect()->intended(route('dashboard'));
        }
    }
}
