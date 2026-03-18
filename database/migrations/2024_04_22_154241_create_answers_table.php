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
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quiz_id')->nullable();
            $table->integer('question_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('attempt_id')->nullable();
            $table->text('answer')->nullable();
            $table->string('q_type', 20)->nullable();
            $table->decimal('q_score', 5, 1)->nullable();
            $table->decimal('r_score', 5, 1)->nullable();
            $table->tinyInteger('is_correct')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
};
