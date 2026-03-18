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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->boolean('active')->default(true);
            $table->string('occupation')->nullable();
            $table->string('profile_photo', 255)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('user_type', 255)->nullable();
            $table->boolean('active_status')->default(true);
            $table->string('gender', 255)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->integer('country_id')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('address_2', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('zip_code', 255)->nullable();
            $table->string('postcode', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->text('about_me')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('job_title', 255)->nullable();
            $table->json('options')->nullable();
            $table->string('provider_user_id', 255)->nullable();
            $table->string('provider', 255)->nullable();
            $table->string('reset_token', 255)->nullable();
            $table->string('timezone', 255)->nullable();
            $table->string('subscription_plan')->nullable();
            $table->timestamp('subscription_start_date')->nullable();
            $table->timestamp('subscription_end_date')->nullable();
            $table->boolean('plan_expired')->default(false);
            $table->string('user_type_before_deactivation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
