<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $p1 = Project::create(['name' => 'Personal']);
        $p2 = Project::create(['name' => 'Work']);

        Task::create(['project_id' => $p1->id, 'name' => 'Buy groceries', 'priority' => 1]);
        Task::create(['project_id' => $p1->id, 'name' => 'Call dentist', 'priority' => 2]);

        Task::create(['project_id' => $p2->id, 'name' => 'Finish report', 'priority' => 1]);
        Task::create(['project_id' => $p2->id, 'name' => 'Email client', 'priority' => 2]);
        Task::create(['project_id' => $p2->id, 'name' => 'Prepare meeting notes', 'priority' => 3]);
    }
}
