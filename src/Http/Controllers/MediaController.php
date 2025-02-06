<?php

namespace RonasIT\Media\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use RonasIT\Media\Contracts\Requests\BulkCreateMediaRequestContract;
use RonasIT\Media\Contracts\Requests\CreateMediaRequestContract;
use RonasIT\Media\Contracts\Requests\DeleteMediaRequestContract;
use RonasIT\Media\Contracts\Requests\SearchMediaRequestContract;
use RonasIT\Media\Contracts\Resources\MediaCollectionContract;
use RonasIT\Media\Contracts\Resources\MediaListResourceContract;
use RonasIT\Media\Contracts\Resources\MediaResourceContract;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use RonasIT\Media\Enums\PreviewDriverEnum;
use RonasIT\Media\Http\Resources\MediaCollection;
use RonasIT\Media\Http\Resources\MediaResource;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    public function create(
        CreateMediaRequestContract $request,
        MediaServiceContract $mediaService
    ): MediaResourceContract {
        $file = $request->file('file');
        $data = $request->onlyValidated();

        if (Arr::get($data, 'preview_drivers', false)) {
            $data['preview_drivers'] = Arr::map($data['preview_drivers'], fn($type) => PreviewDriverEnum::from($type));
        }

        $content = file_get_contents($file->getPathname());

        $media = $mediaService->create($content, $file->getClientOriginalName(), $data);

        return MediaResource::make($media);
    }

    public function delete(DeleteMediaRequestContract $request, MediaServiceContract $mediaService, int $id): Response
    {
        $mediaService->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(
        SearchMediaRequestContract $request,
        MediaServiceContract $mediaService
    ): MediaCollectionContract {
        $result = $mediaService->search($request->onlyValidated());

        return MediaCollection::make($result);
    }

    public function bulkCreate(
        BulkCreateMediaRequestContract $request,
        MediaServiceContract $mediaService
    ): MediaListResourceContract {
        $data = $request->onlyValidated('media');

        if (Arr::get($data, 'preview_drivers', false)) {
            $data['preview_drivers'] = Arr::map($data['preview_drivers'], fn($type) => PreviewDriverEnum::from($type));
        }

        $result = $mediaService->bulkCreate($data);

        return MediaCollection::make($result);
    }
}
