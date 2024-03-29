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
use App\Repositories\CategoryRepository;

class OrganizationController extends Controller
{
    private $organizationRepository;

    private $categoryRepository;

    public function __construct(OrganizationRepository $organizationRepository, CategoryRepository $categoryRepository)
    {
        $this->organizationRepository = $organizationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authorizeResource(Organization::class, 'organization');
    }

    public function index()
    {
        $organizations = $this->organizationRepository->index();

        return OrganizationResource::collection($organizations);
    }

    public function store(StoreOrganizationRequest $request)
    {
        DB::beginTransaction();
        try {
            $organization = $this->organizationRepository->insertOrganisation($request->only(['name', 'contact', 'comment', 'ads_max', 'media_max', 'state_id', 'container_folder']));
            $this->categoryRepository->insertDefaultCategories($organization->id);

            if ($request->has('logo_file')) {
                $this->processImageLogo($organization, $request->logo_file);
            }

            DB::commit();

            return (new OrganizationResource($organization))->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['erreur' => 'Impossible to create this organization'], 422);
        }
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

    public function update(StoreOrganizationRequest $request, Organization $organization)
    {
        $this->organizationRepository->updateOrganization($organization, $request->only(['name', 'contact', 'comment', 'ads_max', 'media_max', 'state_id', 'container_folder']));

        return (new OrganizationResource($organization))->response()->setStatusCode(200);
    }

    public function updateLogo(Request $request, Organization $organization)
    {
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
        $this->deleteImageLogo($organization);
        $this->organizationRepository->updateOrganization($organization, ['logo' => null]);

        return new OrganizationResource($organization);
    }

    public function destroy(Organization $organization)
    {
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

        Storage::disk('organizations')->putFileAs("/{$organization->container_folder}/logos", $image, $fileName);

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
