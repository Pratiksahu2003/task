<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $website = Project::create(['name' => 'Website Redesign']);
        $mobile = Project::create(['name' => 'Mobile App']);

        $websiteTasks = [
            'Design homepage mockup',
            'Implement responsive layout',
            'Write deployment checklist',
        ];

        foreach ($websiteTasks as $index => $name) {
            Task::create([
                'project_id' => $website->id,
                'name' => $name,
                'priority' => $index + 1,
            ]);
        }

        $mobileTasks = [
            'Set up authentication',
            'Build task list screen',
            'Add push notifications',
        ];

        foreach ($mobileTasks as $index => $name) {
            Task::create([
                'project_id' => $mobile->id,
                'name' => $name,
                'priority' => $index + 1,
            ]);
        }
    }
}
