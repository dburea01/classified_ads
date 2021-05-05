<?php

class OrganizationController
{
    /**
     * @OA\Get(
     *      path="/organizations",
     *      operationId="getOrganizationsList",
     *      tags={"Organizations"},
     *      summary="Get list of organizations",
     *      description="Returns list of organizations",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="page",
     *          description="Page number",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="fields[]",
     *          description="The available fields",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Organization")
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    /**
     * @OA\Post(
     *      path="/organizations",
     *      operationId="storeOrganizations",
     *      tags={"Organizations"},
     *      summary="Store a new organization",
     *      description="Store new organization",
     *      security={{"bearer_token":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreOrganizationRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Organization")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    /**
     * @OA\Get(
     *      path="/organizations/{id}",
     *      operationId="getOrganizationById",
     *      tags={"Organizations"},
     *      summary="Get organization information",
     *      description="Returns organization data",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Organization id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string", format="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Organization")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    /**
     * @OA\Put(
     *      path="/organizations/{id}",
     *      operationId="updateOrganization",
     *      tags={"Organizations"},
     *      summary="Update existing organization",
     *      description="Returns updated organization data",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Organization id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string", format="uuid"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreOrganizationRequest")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Organization")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    /**
     * @OA\Delete(
     *      path="/organizations/{id}",
     *      operationId="deleteOrganization",
     *      tags={"Organizations"},
     *      summary="Delete existing organization",
     *      description="Deletes an organization and returns no content",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Organization id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string", format="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
}
