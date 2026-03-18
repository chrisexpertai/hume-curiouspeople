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
        Schema::create('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('parent_id')->nullable()->index('messages_parent_id_foreign');
            $table->unsignedBigInteger('sender_id')->index('messages_sender_id_foreign');
            $table->unsignedBigInteger('receiver_id')->index('messages_receiver_id_foreign');
            $table->unsignedBigInteger('course_id')->index('messages_course_id_foreign');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
