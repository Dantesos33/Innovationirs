<?php
namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super admin
        Admin::updateOrCreate(
            ['email' => 'admin@amsparts.com'],
            [
                'name'      => 'AMS Administrator',
                'password'  => Hash::make('password'),
                'role'      => 'super_admin',
                'is_active' => true,
            ]
        );

        // Regular admin
        Admin::updateOrCreate(
            ['email' => 'staff@amsparts.com'],
            [
                'name'      => 'Sarah Mitchell',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // Second staff member
        Admin::updateOrCreate(
            ['email' => 'parts@amsparts.com'],
            [
                'name'      => 'Mike Torres',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );
    }
}
