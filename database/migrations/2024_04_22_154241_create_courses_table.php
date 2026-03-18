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
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('courses_user_id_foreign');
            $table->unsignedBigInteger('parent_category_id')->nullable()->index('courses_parent_category_id_foreign');
            $table->unsignedBigInteger('second_category_id')->nullable()->index('courses_second_category_id_foreign');
            $table->unsignedBigInteger('category_id')->nullable()->index('courses_category_id_foreign');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('short_description', 225)->nullable();
            $table->longText('description')->nullable();
            $table->text('benefits')->nullable();
            $table->text('requirements')->nullable();
            $table->string('price_plan', 20)->nullable();
            $table->decimal('price', 16)->nullable();
            $table->decimal('sale_price', 16)->nullable();
            $table->tinyInteger('level')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_presale')->default(0);
            $table->timestamp('launch_at')->nullable();
            $table->unsignedBigInteger('thumbnail_id')->nullable();
            $table->text('video_src')->nullable();
            $table->integer('total_video_time')->nullable();
            $table->integer('require_enroll')->nullable();
            $table->integer('require_login')->nullable();
            $table->tinyInteger('total_lectures')->default(0);
            $table->tinyInteger('total_assignments')->default(0);
            $table->tinyInteger('total_quiz')->default(0);
            $table->decimal('rating_value', 3)->default(0);
            $table->tinyInteger('rating_count')->default(0);
            $table->tinyInteger('five_star_count')->default(0);
            $table->tinyInteger('four_star_count')->default(0);
            $table->tinyInteger('three_star_count')->default(0);
            $table->tinyInteger('two_star_count')->default(0);
            $table->tinyInteger('one_star_count')->default(0);
            $table->tinyInteger('is_featured')->nullable();
            $table->timestamp('featured_at')->nullable();
            $table->tinyInteger('is_popular')->nullable();
            $table->timestamp('popular_added_at')->nullable();
            $table->timestamps();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->text('faq')->nullable();
            $table->text('tags')->nullable();
            $table->integer('subscription_plan_id')->nullable();
            $table->text('reviewer_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
