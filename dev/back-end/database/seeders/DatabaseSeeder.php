<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Categories;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user_data = [
            'name' => 'superadmin',
            'email' => 'superadmin@nafhasuha.com',
            'password' => Hash::make('123456'),
            'is_superadmin' => 1,
            'is_admin' => 0,
            'is_detault' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        $Admin = Admin::create($user_data);

        $this->call(
            PermissionTableSeeder::class
        );
    }
}
