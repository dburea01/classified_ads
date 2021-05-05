<?php

/**
 * @OA\Schema(
 *     title="Organization",
 *     description="Organization model"
 * )
 */
class Organization
{
    /**
     * @OA\Property(
     *     title="Id",
     *     type="string",
     *     format="uuid",
     *     example="f5d3101b-de3a-4778-8f6d-90479534c2e5"
     * )
     *
     * @var string
     */
    public $id;

    /**
     * @OA\Property(
     *      title="Name",
     *
     *      type="string",
     *      example="My organization"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Contact",
     *
     *      type="string",
     *      example="Tony BANKS"
     * )
     *
     * @var string
     */
    public $contact;

    /**
     * @OA\Property(
     *      title="Comment",
     *      example="This is a comment ...."
     * )
     *
     * @var string
     */
    public $comment;

    /**
     * @OA\Property(
     *      title="Ads max",
     *
     *      example="10000"
     * )
     *
     * @var int
     */
    public $ads_max;

    /**
     * @OA\Property(
     *      title="Media max",
     *      example="3"
     * )
     *
     * @var int
     */
    public $media_max;
}
