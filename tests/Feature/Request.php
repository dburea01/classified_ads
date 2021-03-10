<?php

declare(strict_types=1);

namespace tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait Request
{
    public function getUrl(): string
    {
        return '/api';
    }

    /*
    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];
    }
    */
    public function actingAsSuperAdmin(Organization $organization)
    {
        $superAdmin = User::factory()->create([
            'organization_id' => $organization->id,
            'role_id' => 'SUPERADMIN'
        ]);

        Sanctum::actingAs($superAdmin);
    }
}
