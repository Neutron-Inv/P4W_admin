<?php

namespace Database\Seeders\App\JobPost;

use App\Models\App\JobPost\ExperienceLevel;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class ExperienceLevelSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        ExperienceLevel::query()->truncate();
        $jobTypes = [
            [
                'name' => 'Entry level',
            ],
            [
                'name' => 'Mid level',
            ],
            [
                'name' => 'Senior level',
            ]
        ];

        ExperienceLevel::query()->insert($jobTypes);

        $this->enableForeignKeys();
    }
}
