<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the basics of HTML, CSS, and JavaScript.',
                'user_id' => 1,
                'course_tags' => ['web', 'beginner', 'frontend'],
                'status' => 'active',
                'reapply_cooldown_days' => 0,
            ],
            [
                'title' => 'Advanced Python Programming',
                'description' => 'Master object-oriented design, decorators, and async programming.',
                'user_id' => 1,
                'course_tags' => ['python', 'advanced', 'backend'],
                'status' => 'active',
                'reapply_cooldown_days' => 14,
            ],
            [
                'title' => 'Database Design & Management',
                'description' => 'Comprehensive guide to relational databases and indexing.',
                'user_id' => 1,
                'course_tags' => ['sql', 'database', 'backend'],
                'status' => 'active',
                'reapply_cooldown_days' => 7,
            ],
            [
                'title' => 'Docker & Containerization',
                'description' => 'Learn how to containerize application environments.',
                'user_id' => 1,
                'course_tags' => ['docker', 'devops'],
                'status' => 'active',
                'reapply_cooldown_days' => 0,
            ],
            [
                'title' => 'UI/UX Fundamentals',
                'description' => 'Understand core principles of user experience design.',
                'user_id' => 1,
                'course_tags' => ['design', 'ui', 'ux'],
                'status' => 'draft',
                'reapply_cooldown_days' => 0,
            ],
            [
                'title' => 'RESTful API Architecture',
                'description' => 'Design scalable and clean APIs using standard practices.',
                'user_id' => 1,
                'course_tags' => ['api', 'backend', 'web'],
                'status' => 'active',
                'reapply_cooldown_days' => 30,
            ],
            [
                'title' => 'Machine Learning Basics',
                'description' => 'Introductory concepts to AI models and data analysis.',
                'user_id' => 1,
                'course_tags' => ['ai', 'data', 'python'],
                'status' => 'inactive',
                'reapply_cooldown_days' => 15,
            ],
            [
                'title' => 'Git & Version Control',
                'description' => 'Manage codebases efficiently using Git workflows.',
                'user_id' => 1,
                'course_tags' => ['git', 'tools', 'workflow'],
                'status' => 'active',
                'reapply_cooldown_days' => 0,
            ],
            [
                'title' => 'Cybersecurity Essentials',
                'description' => 'Learn fundamental concepts to secure web applications.',
                'user_id' => 1,
                'course_tags' => ['security', 'web', 'networking'],
                'status' => 'draft',
                'reapply_cooldown_days' => 0,
            ],
            [
                'title' => 'Microservices Architecture',
                'description' => 'Build distributed applications with microservices.',
                'user_id' => 1,
                'course_tags' => ['architecture', 'devops', 'cloud'],
                'status' => 'active',
                'reapply_cooldown_days' => 30,
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(['title' => $course['title']], $course);
        }
    }
}