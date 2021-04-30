<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SuperAdmin;
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
            OrganizationSeeder::class,
            DomainSeeder::class,
            CategoryGroupSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            SiteTypeSeeder::class,
            SiteSeeder::class,
            ClassifiedAdSeeder::class
        ]);
    }
}
