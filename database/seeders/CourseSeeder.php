<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['code' => 'DCS', 'name' => 'Diploma in Computer Science'],
            ['code' => 'DIA', 'name' => 'Diploma in Accountancy'],
            ['code' => 'DCD', 'name' => 'Diploma in Creative Digital Media Production'],
            ['code' => 'DEC+ITBM', 'name' => 'Diploma in English Communication + Certificate in IT-Based Translation & Media'],
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate(['code' => $course['code']], $course);
        }
    }
}