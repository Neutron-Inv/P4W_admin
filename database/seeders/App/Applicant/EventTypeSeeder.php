<?php


namespace Database\Seeders\App\Applicant;


use App\Models\App\Applicant\EventType;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
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
        EventType::query()->truncate();
        $eventTypes = [
            [
                'name' => 'Call',
            ],
            [
                'name' => 'Meeting',
            ],
            [
                'name' => 'Video Call',
            ],
            [
                'name' => 'Job Interview',
            ],

        ];

        EventType::query()->insert($eventTypes);

        $this->enableForeignKeys();
    }

}