<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherApplication extends Model
{
    protected $fillable = [
        'user_id',
        'contact_number',
        'address',
        'date_of_birth',
        'highest_degree',
        'institution',
        'field_of_study',
        'year_of_graduation',
        'teaching_experience',
        'previous_positions',
        'subjects_taught',
        'institutions_worked',
        'teaching_certificates',
        'training_attended',
        'teaching_philosophy',
        'lms_familiarity',
        'edu_technology_experience',
        'software_proficiency',
        'references',
        'cv_resume',
        'cover_letter',
        'certificates_scans',
        'additional_questions',
        'preferences',
        'availability',
        'agree_terms_conditions',
        // Add more fields as needed
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Additional methods can be added here as needed
}
