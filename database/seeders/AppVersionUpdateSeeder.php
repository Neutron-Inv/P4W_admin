<?php

namespace Database\Seeders;

use App\Models\App\Applicant\ApplicationAnswer;
use App\Models\Core\File\File;
use App\Models\Core\Setting\Setting;
use App\Services\DataMigrate\DataMigrateFor2_3Service;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class AppVersionUpdateSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settingApplication = Setting::query()->select('id', 'value as apply_form_settings')->where('name', 'application_form')->first();

        $apply_form_settings = gettype($settingApplication->apply_form_settings) === 'string' ? json_decode($settingApplication->apply_form_settings) : $settingApplication->apply_form_settings;
        foreach ($apply_form_settings as $key => &$value) {
            if(isset($value->key) && ($value->key === 'basic_information')) {
                $value->actions = [
                                    "edit" => true,
                                    "delete" => false,
                                    "move" => false,
                                    "fixed" => true,
                                ];
                break;
            }
        }
        $settingApplication->value = json_encode($apply_form_settings);
        $settingApplication->save();

        resolve(DataMigrateFor2_3Service::class)->migrate();

        // file path migrate
        $files = File::query()->get();
        foreach($files as $file) {
            $file->path = str_replace('/storage/', '/', $file->path);
            $file->path = str_replace(env('APP_URL'), '', $file->path);
            $file->save();
        }
        $answers = ApplicationAnswer::query()->whereNotNull('attachment')->get();
        foreach($answers as $answer) {
            $answer->attachment = str_replace('/storage/', '/', $answer->attachment);
            $answer->attachment = str_replace(env('APP_URL'), '', $answer->attachment);
            $answer->save();
        }
    }
}
