<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;


class SettingsController extends Controller
{


    public function GeneralSettings(){
        $title = trans('admin.general_settings');
        return view('admin.settings.general_settings', compact('title'));
    }

    public function LMSSettings(){
        $title = trans('admin.lms_settings');
        return view('admin.settings.lms_settings', compact('title'));
    }


    public function ImageSettings(){
        $title = trans('admin.image_settings');
        return view('admin.settings.image_settings', compact('title'));
    }


    public function RegisterSettings(){
        $title = trans('admin.register_settings');
        return view('admin.settings.register_settings', compact('title'));
    }
    public function TextSettings(){
        $title = trans('admin.text_settings');
        return view('admin.settings.text_settings', compact('title'));
    }
    public function HOMESettings(){
        $title = trans('admin.home_settings');
        return view('admin.settings.home_settings', compact('title'));
    }

    public function HeaderSettings(){
        $title = trans('admin.header_settings');
        return view('admin.settings.header_settings', compact('title'));
    }

    public function FooterSettings(){
        $title = trans('admin.footer_settings');
        return view('admin.settings.footer_settings', compact('title'));
    }


    public function MarketingSettings(){
        $title = trans('admin.marketing_settings');
        return view('admin.settings.marketing_settings', compact('title'));
    }

    public function ContactSettings(){
        $title = trans('admin.contact_settings');
        return view('admin.settings.contact_settings', compact('title'));
    }

    public function CourseSettings(){
        $title = trans('admin.course_settings');
        return view('admin.settings.course_settings', compact('title'));
    }

    public function MaintenanceSettings(){
        $title = trans('admin.maintenance_settings');
        return view('admin.settings.maintenance_settings', compact('title'));
    }
    public function StorageSettings(){
        $title = trans('admin.file_storage_settings');
        return view('admin.settings.storage_settings', compact('title'));
    }

    public function ThemeSettings(){
        $title = trans('admin.theme_settings');
        return view('admin.settings.theme_settings', compact('title'));
    }
    public function invoiceSettings(){
        $title = trans('admin.invoice_settings');
        return view('admin.settings.invoice_settings', compact('title'));
    }

    public function modernThemeSettings(){
        $title = trans('admin.modern_theme_settings');
        return view('admin.settings.modern_theme_settings', compact('title'));
    }

    public function SocialUrlSettings(){
        $title = trans('admin.social_url_settings');
        return view('admin.settings.social_url_settings', compact('title'));
    }
    public function SocialSettings(){
        $title = __a('social_login_settings');
        return view('admin.settings.social_settings', compact('title'));
    }
    public function BlogSettings(){
        $title = trans('admin.blog_settings');
        return view('admin.settings.blog_settings', compact('title'));
    }


    public function email(){

    $title = trans('admin.email');
    $emailSettings = [
        'smtp_host' => get_option('mail.smtp_host'),
        'smtp_port' => get_option('mail.smtp_port'),
        'smtp_username' => get_option('mail.smtp_username'),
        'smtp_password' => get_option('mail.smtp_password'),
        'smtp_encryption' => get_option('mail.smtp_encryption'),
        'from_address' => get_option('mail.smtp_from_address'),
        'from_name' => get_option('mail.smtp_from_name'),
    ];
    return view('admin.settings.email_settings', compact('title', 'emailSettings'));
}


    public function saveEmailSettings(Request $request)
    {

    // Update the email settings
    $data = $request->only(['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption']);

    foreach ($data as $key => $value) {
        update_option('mail.'.$key, $value);
    }

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Email settings updated successfully');
}


    public function withdraw(){
        $title = trans('admin.withdraw');
        return view('admin.settings.withdraw_settings', compact('title'));
    }


    /**
     * Update the specified resource in storage.
     *
     *
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $inputs = array_except($request->input(), ['_token']);

        foreach ($inputs as $key => $value) {
            // Check if the value is an array (indicating JSON-encoded value)
            if (is_array($value)) {
                $value = 'json_encode_value_'.json_encode($value);
            }

            // Check if the existing value is "1" and the incoming value is "0"
            if ($value === "0") {
                $existingOption = Option::where('option_key', $key)->first();
                if ($existingOption && $existingOption->option_value === "1") {
                    // Update the existing value to "0"
                    $existingOption->option_value = $value;
                    $existingOption->save();
                    continue; // Skip to the next option
                }
            }

            // If it's not a switch field update, proceed with the regular update
            $option = Option::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
        }

        // Check if the request comes via ajax
        if ($request->ajax()) {
            return ['success' => 1, 'msg' => __a('settings_saved_msg')];
        }

        return redirect()->back()->with('success', __a('settings_saved_msg'));
    }



}

