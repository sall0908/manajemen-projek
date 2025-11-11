<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Board;

class CreateDefaultBoardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        
        foreach ($projects as $project) {
            // Cek apakah project sudah punya boards
            if ($project->boards()->count() == 0) {
                // Buat boards default
                $boards = [
                    'To Do',
                    'In Progress', 
                    'Done'
                ];
                
                foreach ($boards as $boardName) {
                    Board::create([
                        'name' => $boardName,
                        'project_id' => $project->project_id
                    ]);
                }
                
                $this->command->info("Boards created for project: {$project->project_name}");
            }
        }
    }
}