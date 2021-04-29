<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    private $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
        $this->authorizeResource(Organization::class, 'organization');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('viewAny', Organization::class);

        $organizations = $this->organizationRepository->index();

        return OrganizationResource::collection($organizations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrganizationRequest $request)
    {
        // $this->authorize('create', Organization::class);

        $organization = $this->organizationRepository->insertOrganisation($request->only(['name', 'contact', 'comment', 'ads_max', 'media_max', 'state_id', 'container_folder']));

        if ($request->has('logo_file')) {
            $this->processImageLogo($organization, $request->logo_file);
        }

        return (new OrganizationResource($organization))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrganizationRequest $request, Organization $organization)
    {
        // $this->authorize('update', Organization::class);

        $this->organizationRepository->updateOrganization($organization, $request->only(['name', 'contact', 'comment', 'ads_max', 'media_max', 'state_id', 'container_folder']));

        return (new OrganizationResource($organization))->response()->setStatusCode(200);
    }

    public function updateLogo(Request $request, Organization $organization)
    {
        // $this->authorize('update', Organization::class);

        $request->validate([
            'logo_file' => 'required|image|mimes:jpg,bmp,png|max:128'
        ]);

        if ($request->has('logo_file')) {
            $this->deleteImageLogo($organization);
            $this->processImageLogo($organization, $request->logo_file);
        }

        return new OrganizationResource($organization);
    }

    public function deleteLogo(Request $request, Organization $organization)
    {
        // $this->authorize('delete', Organization::class);

        $this->deleteImageLogo($organization);
        $this->organizationRepository->updateOrganization($organization, ['logo' => null]);

        return new OrganizationResource($organization);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        // $this->authorize('delete', Organization::class);

        DB::beginTransaction();
        try {
            $this->organizationRepository->deleteOrganization($organization);
            $this->deleteImageLogo($organization);
            DB::commit();

            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function processImageLogo(Organization $organization, $image)
    {
        $fileName = $organization->id . '.' . $image->getClientOriginalExtension();

        $response = Storage::disk('organizations')->putFileAs("/{$organization->container_folder}/logos", $image, $fileName);
        // Log::info($response);
        $this->organizationRepository->updateOrganization($organization, ['logo' => $fileName]);
    }

    public function deleteImageLogo(Organization $organization)
    {
        $path = "/{$organization->container_folder}/logos/$organization->logo";
        if (Storage::disk('organizations')->exists($path)) {
            Storage::disk('organizations')->delete($path);
        }
    }
}
