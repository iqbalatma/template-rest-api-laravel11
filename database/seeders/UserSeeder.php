<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public const array DATA_USER = [
        [
            "first_name" => "superadmin",
            "last_name" => "superadmin",
            "password" => "admin",
            "email" => "superadmin@mail.com",
            "phone_number" => "+62895351172040",
            "phone_number_verified_at" => "2023-10-14",
            "email_verified_at" => "2023-10-14",
            "profile" => [
                "address" => "Bandung Dago",
                "gender" => "male",
                "avatar" => null,
                "birth_date" => "1999-02-16",
                "birth_place" => "bandung",
            ]
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::DATA_USER as $user) {
            /** @var User $user */
            $profile = null;
            if (isset($user["profile"])) {
                $profile = $user["profile"];
                unset($user["profile"]);
            }

            $user = User::query()->create($user);

            if ($profile){
                $user->profile()->create($profile);
            }
        }

        $userSuperAdmin = User::query()
            ->where("email", "superadmin@mail.com")
            ->first();
        $userSuperAdmin->assignRole(Role::SUPERADMIN->value);

        User::factory()->count(100)->create();
    }
}
