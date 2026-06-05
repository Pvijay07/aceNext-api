<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $mentor = User::create([
            'name' => 'John Mentor',
            'email' => 'mentor@acenext.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $student = User::create([
            'name' => 'Jane Student',
            'email' => 'student@acenext.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $course = Course::create([
            'title' => 'Fullstack AI Development',
            'slug' => Str::slug('Fullstack AI Development'),
            'description' => 'Learn how to build AI-powered apps with Next.js and Laravel.',
            'thumbnail_url' => 'https://via.placeholder.com/800x600?text=AI+Course',
            'price' => 0.00,
            'status' => 'published',
            'mentor_id' => $mentor->id,
        ]);

        $module1 = CourseModule::create([
            'course_id' => $course->id,
            'title' => 'Module 1: Introduction',
            'order_no' => 1,
        ]);

        CourseLesson::create([
            'course_module_id' => $module1->id,
            'title' => 'Welcome to the Course',
            'type' => 'video',
            'asset_url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
            'duration' => 120,
            'order_no' => 1,
        ]);

        CourseLesson::create([
            'course_module_id' => $module1->id,
            'title' => 'Setting up the Environment',
            'type' => 'text',
            'asset_url' => null,
            'duration' => 300,
            'order_no' => 2,
        ]);
    }
}
