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
        Schema::create('cart', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('webinar_id')->nullable();
            $table->unsignedInteger('reserve_meeting_id')->nullable();
            $table->unsignedInteger('subscribe_id')->nullable();
            $table->unsignedInteger('promotion_id')->nullable();
            $table->unsignedInteger('ticket_id')->nullable();
            $table->unsignedInteger('special_offer_id')->nullable();
            $table->integer('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart');
    }
};
