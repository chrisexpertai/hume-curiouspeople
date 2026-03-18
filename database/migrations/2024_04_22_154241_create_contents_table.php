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
        Schema::create('contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('text')->nullable();
            $table->text('video_src')->nullable();
            $table->string('video_time')->nullable();
            $table->string('item_type', 30)->nullable();
            $table->boolean('is_preview')->nullable()->default(false);
            $table->integer('status')->nullable()->default(0);
            $table->integer('sort_order')->nullable()->default(0);
            $table->text('options')->nullable();
            $table->boolean('quiz_gradable')->nullable();
            $table->dateTime('unlock_date')->nullable();
            $table->integer('unlock_days')->nullable();
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
        Schema::dropIfExists('contents');
    }
};
