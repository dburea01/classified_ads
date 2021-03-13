<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Organization;
use App\Models\Site;
use App\Models\SiteType;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = Organization::all();
        $countries = Country::all();

        foreach ($organizations as $organization) {
            $siteTypes = SiteType::where('organization_id', $organization->id)->get();

            for ($i = 0; $i < 100; $i++) {
                try {
                    Site::factory()->create([
                        'organization_id' => $organization->id,
                        'country_id' => $countries->random()->id,
                        'site_type_id' => $siteTypes->random()->id
                    ]);
                } catch (\Throwable $th) {
                    echo 'doublon insert site, pas grave ça continue ....';
                }
            }
        }
    }
}
