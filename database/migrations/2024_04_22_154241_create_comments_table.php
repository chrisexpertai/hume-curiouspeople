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
        Schema::create('comments', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('review_id')->nullable();
            $table->unsignedInteger('webinar_id')->nullable();
            $table->unsignedInteger('blog_id')->nullable();
            $table->unsignedInteger('reply_id')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', ['pending', 'active']);
            $table->boolean('report')->default(false);
            $table->boolean('disabled')->default(false);
            $table->integer('created_at');
            $table->unsignedInteger('viewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
