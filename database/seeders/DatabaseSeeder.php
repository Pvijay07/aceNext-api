<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use App\Models\Package;
use App\Models\Setting;
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

        // Seed Add-on Packages
        Package::create([
            'name' => 'Job Placement Guarantee',
            'price' => 25000,
            'price_display' => '₹25,000',
            'users' => 145,
            'active' => true,
        ]);

        Package::create([
            'name' => '1-on-1 Mentorship (Monthly)',
            'price' => 5000,
            'price_display' => '₹5,000/mo',
            'users' => 89,
            'active' => true,
        ]);

        Package::create([
            'name' => 'Resume & Portfolio Review',
            'price' => 2500,
            'price_display' => '₹2,500',
            'users' => 210,
            'active' => true,
        ]);

        Package::create([
            'name' => 'Premium Mock Interviews',
            'price' => 4000,
            'price_display' => '₹4,000',
            'users' => 0,
            'active' => false,
        ]);

        // Seed settings
        Setting::create(['key' => 'platform_name', 'value' => 'AceNext AI']);
        Setting::create(['key' => 'contact_email', 'value' => 'support@acenext.com']);
        Setting::create(['key' => 'support_phone', 'value' => '+91 98765 43210']);
        Setting::create(['key' => 'maintenance_mode', 'value' => 'false']);
        Setting::create(['key' => 'enable_ai_tutor', 'value' => 'true']);
        Setting::create(['key' => 'enable_gamification', 'value' => 'true']);
        Setting::create(['key' => 'enable_zoom_integration', 'value' => 'false']);
        Setting::create(['key' => 'allow_self_registration', 'value' => 'true']);
    }
}
