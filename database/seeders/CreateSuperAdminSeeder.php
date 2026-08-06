<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'superadmin@tilalr.com';
        $password = 'superadmin123';

        // Ensure super_admin role exists
        $role = Role::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Admin',
                'description' => 'Full administrative access to all features',
                'is_active' => true,
            ]
        );

        // If a user already has the target email, make that the superadmin and demote others
        $existingByEmail = User::where('email', $email)->first();
        if ($existingByEmail) {
            $existingByEmail->update([
                'name' => 'Super Admin',
                'password' => $password,
                'is_admin' => true,
            ]);

            // Demote any other admins
            User::where('id', '!=', $existingByEmail->id)->where('is_admin', true)->update(['is_admin' => false]);

            $existingByEmail->roles()->syncWithoutDetaching([$role->id]);

            echo "Set existing user {$email} as Super Admin and assigned super_admin role (password updated to: {$password})\n";
            return;
        }

        // Otherwise, prefer updating the current super admin (if any) to the target email
        $currentAdmin = User::where('is_admin', true)->first();
        if ($currentAdmin) {
            $currentAdmin->update([
                'email' => $email,
                'name' => 'Super Admin',
                'password' => $password,
            ]);

            // Demote any other admins (shouldn't be necessary but keep it safe)
            User::where('id', '!=', $currentAdmin->id)->where('is_admin', true)->update(['is_admin' => false]);

            $currentAdmin->roles()->syncWithoutDetaching([$role->id]);

            echo "Updated existing Super Admin email to: {$email} and assigned super_admin role (password updated to: {$password})\n";
            return;
        }

        // No existing email or admin found: create a new super admin
        $user = User::create([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => $password,
            'is_admin' => true,
        ]);

        $user->roles()->syncWithoutDetaching([$role->id]);

        echo "Created Super Admin: {$email} with password: {$password} and assigned super_admin role\n";
    }
}
