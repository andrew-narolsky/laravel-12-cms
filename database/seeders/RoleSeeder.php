<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            0 => [
                'name' => 'Administrator',
                'slug' => 'admin',
            ],
            1 => [
                'name' => 'Editor',
                'slug' => 'editor',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
