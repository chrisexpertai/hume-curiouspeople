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
        Schema::create('blog', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('author_id');
            $table->string('slug', 255);
            $table->string('image', 255);
            $table->unsignedInteger('visit_count')->nullable()->default(0);
            $table->boolean('enable_comment')->default(true);
            $table->enum('status', ['pending', 'publish'])->default('pending');
            $table->unsignedInteger('created_at');
            $table->unsignedInteger('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog');
    }
};
