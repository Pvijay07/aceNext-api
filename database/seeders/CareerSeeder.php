<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPosting;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        JobPosting::create([
            'company' => 'Google',
            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg',
            'role' => 'Frontend Engineer',
            'salary' => '$120,000 - $150,000',
            'location' => 'Remote',
            'type' => 'Full-time',
            'requirements' => ['React 18', 'TypeScript', 'TailwindCSS', '5+ years experience']
        ]);

        JobPosting::create([
            'company' => 'Meta',
            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/7/7b/Meta_Platforms_Inc._logo.svg',
            'role' => 'Full Stack Developer',
            'salary' => '$140,000 - $180,000',
            'location' => 'Menlo Park, CA',
            'type' => 'Full-time',
            'requirements' => ['React', 'PHP/Hack', 'GraphQL', 'System Design']
        ]);

        JobPosting::create([
            'company' => 'AceNext',
            'logo' => 'https://ui-avatars.com/api/?name=AceNext&background=4f46e5&color=fff',
            'role' => 'Junior Web Developer',
            'salary' => '$60,000 - $80,000',
            'location' => 'Remote',
            'type' => 'Internship / Full-time',
            'requirements' => ['Laravel', 'React', 'MySQL', 'Passion for learning']
        ]);
    }
}
