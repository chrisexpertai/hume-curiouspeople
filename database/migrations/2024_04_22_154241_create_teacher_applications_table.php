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
        Schema::create('teacher_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('teacher_applications_user_id_foreign');
            $table->timestamps();
            $table->string('status')->default('pending');
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('highest_degree')->nullable();
            $table->string('institution')->nullable();
            $table->string('field_of_study')->nullable();
            $table->year('year_of_graduation')->nullable();
            $table->integer('teaching_experience')->nullable();
            $table->string('previous_positions')->nullable();
            $table->string('subjects_taught')->nullable();
            $table->string('institutions_worked')->nullable();
            $table->string('teaching_certificates')->nullable();
            $table->string('training_attended')->nullable();
            $table->text('teaching_philosophy')->nullable();
            $table->string('lms_familiarity')->nullable();
            $table->string('edu_technology_experience')->nullable();
            $table->string('software_proficiency')->nullable();
            $table->text('references')->nullable();
            $table->string('cv_resume')->nullable();
            $table->string('cover_letter')->nullable();
            $table->string('certificates_scans')->nullable();
            $table->text('additional_questions')->nullable();
            $table->string('preferences')->nullable();
            $table->string('availability')->nullable();
            $table->boolean('agree_terms_conditions')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_applications');
    }
};
