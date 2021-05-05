<?php

/**
 * @OA\Schema(
 *     title="OrganizationResource",
 *     description="Organization resource",
 *     @OA\Xml(
 *         name="OrganizationResource"
 *     )
 * )
 */
class OrganizationResource
{
    /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Swagger\Models\Organization
     */
    private $data;
}
