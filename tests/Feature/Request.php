<?php

declare(strict_types=1);

namespace tests\Feature;

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
}
