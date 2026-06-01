<?php

namespace App\Services;

use App\Models\AlumniProfile;
use App\Models\StudentProfile;

class RecommendationService
{
    /**
     * Return up to 3 recommended alumni for a given student,
     * ranked by match score. Excludes alumni the student has
     * already sent an active (pending/accepted) request to.
     */
    public function recommend(StudentProfile $student): \Illuminate\Support\Collection
    {
        // IDs of alumni already requested by this student
        $excludedIds = $student->mentorshipRequests()
            ->whereIn('status', ['pending', 'accepted'])
            ->pluck('alumni_id');

        $candidates = AlumniProfile::with('user', 'course')
            ->where('status', 'approved')
            ->whereNotIn('id', $excludedIds)
            ->get();

        // Score each candidate
        return $candidates
            ->map(function (AlumniProfile $alumni) use ($student) {
                $score = 0;

                // Rule 1: Same course (highest weight — 50pts)
                if ($alumni->course_id === $student->course_id) {
                    $score += 50;
                }

                // Rule 2: Career interest matches alumni industry (30pts)
                if (
                    $student->career_interest &&
                    $alumni->industry &&
                    $this->softMatch($student->career_interest, $alumni->industry)
                ) {
                    $score += 30;
                }

                // Rule 3: Industry interest matches alumni job position (20pts)
                if (
                    $student->industry_interest &&
                    $alumni->job_position &&
                    $this->softMatch($student->industry_interest, $alumni->job_position)
                ) {
                    $score += 20;
                }

                $alumni->match_score = $score;
                return $alumni;
            })
            ->filter(fn ($a) => $a->match_score > 0)   // only relevant matches
            ->sortByDesc('match_score')
            ->take(3)
            ->values();
    }

    /**
     * Case-insensitive partial string match between two values.
     */
    private function softMatch(string $a, string $b): bool
    {
        $a = strtolower(trim($a));
        $b = strtolower(trim($b));
        return str_contains($a, $b) || str_contains($b, $a);
    }
}