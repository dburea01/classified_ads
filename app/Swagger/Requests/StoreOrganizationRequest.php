<?php

/**
 * @OA\Schema(
 *      title="StoreOrganizationRequest",
 *      description="Store Organization request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreOrganizationRequest
{
    /**
     * @OA\Property(
     *      title="Name of the new organization",
     *      description="Name of the new organization",
     *      example="My organization",
     *      type="string"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Contact of the new organization",
     *      description="Contact of the new organization",
     *      example="Tony BANKS",
     *      type="string"
     * )
     *
     * @var string
     */
    public $contact;

    /**
     * @OA\Property(
     *     title="Comment",
     *     description="A comment for this new organization",
     *     example="This is a comment.",
     *
     *     type="string"
     * )
     *
     * @var string
     */
    private $comment;

    /**
     * @OA\Property(
     *     title="Ads max",
     *     description="Qty maximum of classified ads for this new organization",
     *     example=10000,
     *
     *     type="integer"
     * )
     *
     * @var integer
     */
    private $ads_max;

    /**
     * @OA\Property(
     *     title="Media max",
     *     description="Qty maximum of medias for a classified ad for this new organization",
     *     example=3,
     *
     *     type="integer"
     * )
     *
     * @var integer
     */
    private $media_max;
}
