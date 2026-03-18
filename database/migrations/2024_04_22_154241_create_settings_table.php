<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site_name', 255);
            $table->text('site_description')->nullable();
            $table->string('site_logo', 255)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->timestamps();
            $table->string('mail_driver', 255)->nullable();
            $table->string('mail_host', 255)->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_username', 255)->nullable();
            $table->string('mail_password', 255)->nullable();
            $table->string('facebook_url', 255)->nullable();
            $table->string('twitter_url', 255)->nullable();
            $table->string('instagram_url', 255)->nullable();
            $table->string('linkedin_url', 255)->nullable();
            $table->boolean('enable_notifications')->nullable();
            $table->string('notification_email', 255)->nullable();
            $table->boolean('sms_notifications')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('google_analytics_code', 255)->nullable();
            $table->boolean('enable_firewall')->nullable();
            $table->text('firewall_rules')->nullable();
            $table->boolean('enable_ssl')->nullable();
            $table->boolean('enable_payment')->nullable();
            $table->string('default_currency', 255)->nullable();
            $table->string('paypal_client_id', 255)->nullable();
            $table->string('paypal_secret_key', 255)->nullable();
            $table->string('stripe_publishable_key', 255)->nullable();
            $table->string('stripe_secret_key', 255)->nullable();
            $table->boolean('enable_integration')->nullable();
            $table->string('integration_api_key', 255)->nullable();
            $table->text('other_integration_setting')->nullable();
            $table->boolean('enable_dark_mode')->nullable();
            $table->string('default_language', 255)->nullable();
            $table->integer('max_upload_size')->nullable();
            $table->boolean('enable_logging')->nullable();
            $table->string('default_timezone', 255)->nullable();
            $table->boolean('enable_wysiwyg_editor')->nullable();
            $table->string('default_content_category', 255)->nullable();
            $table->boolean('content_approval_required')->nullable();
            $table->integer('delete_logs_after_days')->nullable();
            $table->boolean('retain_user_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
