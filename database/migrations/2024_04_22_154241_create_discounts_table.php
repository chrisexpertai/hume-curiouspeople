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
        Schema::create('discounts', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedInteger('creator_id');
            $table->string('title', 255);
            $table->enum('discount_type', ['percentage', 'fixed_amount']);
            $table->enum('source', ['all', 'course', 'category', 'meeting']);
            $table->string('code', 64);
            $table->unsignedInteger('percent')->nullable();
            $table->unsignedInteger('amount')->nullable();
            $table->unsignedInteger('max_amount')->nullable();
            $table->unsignedInteger('minimum_order')->nullable();
            $table->integer('count')->default(1);
            $table->enum('user_type', ['all_users', 'special_users']);
            $table->boolean('for_first_purchase')->default(false);
            $table->enum('status', ['active', 'disable'])->default('active');
            $table->unsignedInteger('expired_at');
            $table->unsignedInteger('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
};
