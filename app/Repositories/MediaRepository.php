<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Media;
use Ramsey\Uuid\Uuid;

class MediaRepository
{
    public function index(string $classifiedAdId)
    {
        $medias = Media::where('classified_ad_id', $classifiedAdId)->orderBy('created_at');

        return $medias->paginate(10);
    }

    public function insert(array $data) : Media
    {
        $media = new Media();
        $media->id = Uuid::uuid4()->toString();
        $media->fill($data);
        $media->storage_name = $media->id . '.' . $data['media_file']->getClientOriginalExtension();
        $media->original_name = $data['media_file']->getClientOriginalName();
        $media->save();

        return $media;
    }

    public function update(Media $media, array $data) : Media
    {
        $media->fill($data);
        $media->save();

        return $media;
    }

    public function delete(Media $media) : void
    {
        $media->delete();
    }
}
