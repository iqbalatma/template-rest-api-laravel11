<?php

namespace Database\Seeders;

use App\Enums\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Permission::cases() as $permission){
            \App\Models\Permission::create([
                "name" => $permission->value,
                "description" => $permission->description(),
                "feature_group" => $permission->featureGroup(),
                "guard_name" => "jwt",
            ]);
        }
    }
}
