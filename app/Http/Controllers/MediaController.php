<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMediasRequest;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\ClassifiedAd;
use App\Models\Media;
use App\Models\Organization;
use App\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use PhpParser\Node\Stmt\TryCatch;

class MediaController extends Controller
{
    protected $mediaRepository;

    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function index(Organization $organization, GetMediasRequest $request)
    {
        $this->authorize('viewAny', [Media::class, $organization]);

        try {
            $medias = $this->mediaRepository->index($request->classified_ad_id);

            return MediaResource::collection($medias);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function store(Organization $organization, StoreMediaRequest $request)
    {
        $this->authorize('create', [Media::class, $organization->id, $request->classified_ad_id]);

        DB::beginTransaction();
        try {
            $media = $this->mediaRepository->insert($request->only(['classified_ad_id', 'media_file']), null);
            $this->storeMedia($organization, $request->media_file, $media->storage_name);
            DB::commit();

            return new MediaResource($media);
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Media $media)
    {
        $this->authorize('view', [Media::class, $organization]);

        return new MediaResource($media);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Media $media)
    {
        $this->authorize('delete', [Media::class, $organization, $media]);

        DB::beginTransaction();
        try {
            $this->mediaRepository->delete($media);
            $this->deleteMedia($organization, $media);
            DB::commit();

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function storeMedia(Organization $organization, $image, string $storageName)
    {
        $response = Storage::disk('organizations')->putFileAs("/{$organization->container_folder}/medias", $image, $storageName);
    }

    public function deleteMedia(Organization $organization, Media $media)
    {
        $path = "/{$organization->container_folder}/medias/$media->storage_name";
        if (Storage::disk('organizations')->exists($path)) {
            Storage::disk('organizations')->delete($path);
        }
    }
}
