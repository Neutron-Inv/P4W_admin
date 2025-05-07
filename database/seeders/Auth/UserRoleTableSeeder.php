<?php
namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserRoleTableSeeder.
 */
class UserRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        User::query()->find(1)->assignRole(config('access.users.app_admin_role'));
        User::query()->find(2)->assignRole(config('access.users.app_manager_role'));
        User::query()->find(3)->assignRole(config('access.users.app_employee_role'));
        User::query()->find(4)->assignRole(config('access.users.app_candidate_role'));

        $this->enableForeignKeys();
    }
}
