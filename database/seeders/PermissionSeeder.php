<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure all roles exist
        $roles = [
            'super_admin' => ['display_name' => 'Super Admin', 'description' => 'Full administrative access to all features'],
            'executive_manager' => ['display_name' => 'Executive Manager', 'description' => 'Management access to bookings, offers and reports'],
            'consultant' => ['display_name' => 'Consultant', 'description' => 'Access to reservations, bookings and visas'],
            'administration' => ['display_name' => 'Administration', 'description' => 'Administrative operations and user management'],
            'content_manager' => ['display_name' => 'Content Manager', 'description' => 'Manages tourism offers, destinations, and media banners'],
            'support_agent' => ['display_name' => 'Support Agent', 'description' => 'Manages bookings, visa applications, and contact requests'],
            'data_analyst' => ['display_name' => 'Data Analyst', 'description' => 'View access to reports, bookings, and reservations'],
        ];

        foreach ($roles as $name => $data) {
            Role::firstOrCreate(
                ['name' => $name],
                [
                    'display_name' => $data['display_name'],
                    'description' => $data['description'],
                    'is_active' => true,
                ]
            );
        }
        $this->command->info('Roles ensured successfully.');

        // 2. Define all resources and display names
        $resources = [
            // Administration
            'users' => 'Users',
            'roles' => 'Roles',
            'permissions' => 'Permissions',

            // Tourism & Offers
            'tourism_offers' => 'Saudi Offers',
            'tourism_destinations' => 'International Destinations',
            'coupons' => 'Discount Coupons',
            'trips' => 'Trips',
            'offers' => 'Special Offers',

            // Jamoula
            'jamoula_offers' => 'Jamoula Offers',

            // Cities
            'cities' => 'Cities',

            // Visas
            'schengen_applications' => 'Schengen Applications',
            'evisa_applications' => 'E-Visa Applications',
            'saudi_visas' => 'Saudi Visas',
            'visa_countries' => 'Visa Countries',

            // International Services
            'services' => 'Services',
            'internet_package_requests' => 'Internet Package Requests',
            'private_jet_requests' => 'Private Jet Requests',

            // Bookings & Payments
            'bookings' => 'Bookings',
            'reservations' => 'Reservations',
            'payments' => 'Payments',
            'custom_payment_offers' => 'Custom Payment Offers',

            // Settings & Media
            'settings' => 'App Settings',
            'banners' => 'Banners',
            'header_banners' => 'Header Banners',
            'partners' => 'Partner Logos',
            'contacts' => 'Contacts & Inquiries',
            'products' => 'Products',
            'projects' => 'Projects',
        ];

        // Actions for each resource
        $actions = [
            'view_any' => 'View All',
            'view' => 'View',
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
        ];

        // Create permissions for each resource
        foreach ($resources as $resourceKey => $displayName) {
            foreach ($actions as $actionKey => $actionDisplay) {
                Permission::firstOrCreate(
                    ['name' => "{$resourceKey}.{$actionKey}"],
                    [
                        'display_name' => "{$actionDisplay} {$displayName}",
                        'group' => $displayName,
                        'description' => "Allows user to {$actionDisplay} {$displayName}",
                    ]
                );
            }
        }

        // Add special permissions
        $specialPermissions = [
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'group' => 'Dashboard', 'description' => 'Access to the admin dashboard'],
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'group' => 'Reports', 'description' => 'Access to view reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'group' => 'Reports', 'description' => 'Export reports to PDF/Excel'],
        ];

        foreach ($specialPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // Fill any null or empty display_name values for legacy permissions
        Permission::whereNull('display_name')->orWhere('display_name', '')->get()->each(function ($p) {
            $p->update([
                'display_name' => ucwords(str_replace(['.', '_'], ' ', $p->name)),
                'group' => ucwords(explode('.', str_replace('_', ' ', $p->name))[0]),
                'description' => 'Allows user to perform ' . str_replace(['.', '_'], ' ', $p->name),
            ]);
        });

        $this->command->info('Permissions seeded successfully! Total permissions: ' . Permission::count());

        // 3. Assign all permissions to super_admin role
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $allPermissions = Permission::pluck('id')->toArray();
            $superAdminRole->permissions()->sync($allPermissions);
            $this->command->info('All permissions assigned to Super Admin role.');
        }

        // 4. Attach super_admin role to all existing is_admin users
        $adminUsers = User::where('is_admin', true)->get();
        if ($superAdminRole) {
            foreach ($adminUsers as $adminUser) {
                if (!$adminUser->hasRole('super_admin')) {
                    $adminUser->roles()->attach($superAdminRole->id);
                }
            }
            $this->command->info('Super Admin role attached to ' . $adminUsers->count() . ' admin user(s).');
        }
    }
}

