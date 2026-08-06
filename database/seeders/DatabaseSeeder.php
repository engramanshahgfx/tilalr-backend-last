<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ContactInfoSeeder::class,
            TrainingSeeder::class,
            PermissionSeeder::class,
            CreateSuperAdminSeeder::class,
            RolePermissionSeeder::class,
            TourismOfferSeeder::class,
            TourismDestinationsSeeder::class,
        ]);
    }
}
