<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class);
    }

    public function alumniProfiles()
    {
        return $this->hasMany(AlumniProfile::class);
    }
}