<?php

// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'site_logo',
        'site_description',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'enable_notifications',
        'notification_email',
        'sms_notifications',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'google_analytics_code',
        'enable_firewall',
        'firewall_rules',
        'enable_ssl',
        'enable_payment',
        'default_currency',
        'paypal_client_id',
        'paypal_secret_key',
        'stripe_publishable_key',
        'stripe_secret_key',
        'enable_integration',
        'integration_api_key',
        'other_integration_setting',
        'enable_dark_mode',
        'default_language',
        'enable_notifications',
        'max_upload_size',
        'enable_logging',
        'default_timezone',
        'enable_wysiwyg_editor',
        'default_content_category',
        'content_approval_required',
        'delete_logs_after_days',
        'retain_user_data',
        'favicon', // Add this line for favicon

    ];


    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }
}

