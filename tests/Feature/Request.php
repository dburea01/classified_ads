<?php

declare(strict_types=1);

namespace tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait Request
{
    public function getUrl(): string
    {
        return '/api';
    }

    public function actingAsRole(string $roleId, string $organizationId = null) : User
    {
        $user = User::factory()->create([
            'organization_id' => $organizationId,
            'role_id' => $roleId
        ]);

        Sanctum::actingAs($user);

        return $user;
    }
}
