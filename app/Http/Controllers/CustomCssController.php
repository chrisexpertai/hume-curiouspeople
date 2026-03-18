<?php

namespace App\Http\Controllers;

use App\Models\CustomCss;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomCssController extends Controller
{
    public function index()
    {
        $customCss = CustomCss::latest()->first();

        return view('admin.custom-css', compact('customCss'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'css_code' => 'required',
        ]);

        CustomCss::create([
            'css_code' => $request->input('css_code'),
        ]);

        return redirect('admin/custom/css')->with('success', 'Custom CSS saved successfully');
    }
}
