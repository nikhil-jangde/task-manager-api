<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Get the test user or create one if not exists
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password')
            ]
        );

        $tasks = [
            [
                'title' => 'Design System Implementation',
                'description' => 'Create a cohesive design system with color palette, typography and spacing.',
                'status' => 'completed',
            ],
            [
                'title' => 'Authentication Logic',
                'description' => 'Implement Laravel Sanctum for API token based authentication.',
                'status' => 'completed',
            ],
            [
                'title' => 'Dashboard Analytics',
                'description' => 'Create charts for task status distribution and completion progress.',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Kanban Board Drag & Drop',
                'description' => 'Implement drag and drop functionality for the projects view.',
                'status' => 'backlog',
            ],
            [
                'title' => 'User Profile Settings',
                'description' => 'Allow users to update their profile information and password.',
                'status' => 'backlog',
            ],
             [
                'title' => 'Mobile Responsiveness',
                'description' => 'Ensure the application looks good on mobile devices.',
                'status' => 'in_progress',
            ],
        ];

        foreach ($tasks as $task) {
            Task::create(array_merge($task, ['user_id' => $user->id]));
        }
    }
}
