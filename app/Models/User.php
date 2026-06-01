<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kpmim_id',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function alumniProfile()
    {
        return $this->hasOne(AlumniProfile::class);
    }

    // ── Role helpers ───────────────────────────────────────────────────────

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isAlumni(): bool  { return $this->role === 'alumni'; }
    public function isStudent(): bool { return $this->role === 'student'; }
    public function isActive(): bool  { return (bool) $this->is_active; }
}