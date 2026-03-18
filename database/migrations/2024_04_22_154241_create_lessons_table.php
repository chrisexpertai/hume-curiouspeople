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
        Schema::create('lessons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_id')->index('lessons_course_id_foreign');
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('embed_id', 255)->nullable();
            $table->text('short_text')->nullable();
            $table->text('full_text')->nullable();
            $table->unsignedInteger('position')->nullable();
            $table->tinyInteger('free_lesson')->nullable()->default(0);
            $table->tinyInteger('published')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};
