<?php

namespace Database\Seeders;

use App\Enums\GuardType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            //users page
            "user-list" => "user",
            "user-create" => "user",
            "user-edit" => "user",
            "user-delete" => "user",

            //user roles page
            "user-role-list" => "user-role",
            "user-role-create" => "user-role",
            "user-role-edit" => "user-role",
            "user-role-delete" => "user-role",

            //Business page
            "business-list" => "business",
            "business-create" => "business",
            "business-edit" => "business",
            "business-delete" => "business",

            //Business Owner page
            "business-owner-list" => "business",
            "business-owner-create" => "business",
            "business-owner-edit" => "business",
            "business-owner-delete" => "business",

            //Business Plans page
            "business-plan-list" => "business",
            "business-plan-create" => "business",
            "business-plan-edit" => "business",
            "business-plan-delete" => "business",
        ];

        foreach ($permissions as $permission => $group) {
            Permission::firstOrCreate([
               'name' => $permission,
               'group' => $group,
               'guard_name' => GuardType::ADMIN
            ]);
        }
    }
}
