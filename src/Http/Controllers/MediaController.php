<?php

namespace RonasIT\Media\Http\Controllers;

use Illuminate\Routing\Controller;
use RonasIT\Media\Contracts\Requests\BulkCreateMediaRequestContract;
use RonasIT\Media\Contracts\Requests\CreateMediaRequestContract;
use RonasIT\Media\Contracts\Requests\DeleteMediaRequestContract;
use RonasIT\Media\Contracts\Requests\SearchMediaRequestContract;
use RonasIT\Media\Contracts\Resources\MediaCollectionContract;
use RonasIT\Media\Contracts\Resources\MediaListResourceContract;
use RonasIT\Media\Contracts\Resources\MediaResourceContract;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    public function create(
        CreateMediaRequestContract $request,
        MediaServiceContract $mediaService,
    ): MediaResourceContract {
        $file = $request->file('file');
        $data = $request->onlyValidated();

        $media = $mediaService->createFromStream($file, $data);

        return app(MediaResourceContract::class, ['resource' => $media]);
    }

    public function delete(DeleteMediaRequestContract $request, MediaServiceContract $mediaService, int $id): Response
    {
        $mediaService->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(
        SearchMediaRequestContract $request,
        MediaServiceContract $mediaService,
    ): MediaCollectionContract {
        $result = $mediaService->search($request->onlyValidated());

        return app(MediaCollectionContract::class, ['resource' => $result]);
    }

    public function bulkCreate(
        BulkCreateMediaRequestContract $request,
        MediaServiceContract $mediaService,
    ): MediaListResourceContract {
        $data = $request->onlyValidated();

        $result = array_map(
            callback: fn ($media) => $mediaService->createFromStream($media['file'], $media),
            array: $data['media'],
        );

        return app(MediaListResourceContract::class, ['resource' => $result]);
    }
}
