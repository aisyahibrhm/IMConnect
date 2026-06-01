<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'phone',
        'college_email',
        'graduation_year',
        'career_interest',
        'industry_interest',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function mentorshipRequests()
    {
        return $this->hasMany(MentorshipRequest::class, 'student_id');
    }
}