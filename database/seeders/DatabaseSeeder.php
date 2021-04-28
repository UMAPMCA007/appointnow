<?php

namespace Database\Seeders;

use App\Models\BusinessOwner;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TimezoneSeeder::class,
            UserPermissionSeeder::class,
            UserRoleSeeder::class,
            UserSeeder::class,
            BusinessOwnerSeeder::class
        ]);
    }
}
