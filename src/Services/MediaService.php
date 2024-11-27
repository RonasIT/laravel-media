<?php

namespace RonasIT\Media\Services;

use Illuminate\Support\Facades\Http;
use League\Flysystem\Local\LocalFilesystemAdapter;
use RonasIT\Media\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use RonasIT\Support\Services\EntityService;
use RonasIT\Support\Traits\FilesUploadTrait;
use Spatie\Image\Image;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property MediaRepository $repository
 * @mixin MediaRepository
 */
class MediaService extends EntityService implements MediaServiceContract
{
    use FilesUploadTrait;
    use InteractsWithMedia;

    public function __construct()
    {
        $this->setRepository(MediaRepository::class);
    }

    public function search(array $filters): LengthAwarePaginator
    {
        if (!Auth::check()) {
            $filters['is_public'] = true;
        } else {
            $filters['owner_id'] = Auth::id();
        }

        return $this
            ->searchQuery($filters)
            ->filterByQuery(['name'])
            ->getSearchResults();
    }

    public function create($content, string $fileName, array $data = []): Model
    {
        $fileName = $this->saveFile($fileName, $content);
        $data['name'] = $fileName;
        $data['link'] = Storage::url($data['name']);
        $data['owner_id'] = Auth::id();
        $data['preview_id'] = $this->createPreview($data['link'])->id;

        return $this->repository->create($data);
    }

    public function bulkCreate(array $data): array
    {
        return array_map(function ($media) {
            $file = $media['file'];
            $content = file_get_contents($file->getPathname());

            return $this->create($content, $file->getClientOriginalName(), $media);
        }, $data);
    }

    public function delete($where): int
    {
        $entity = $this->first($where);

        Storage::delete($entity->name);

        return $this->repository->delete($where);
    }

    public function first(array|int $where = []): ?Model
    {
        return $this->repository->first($where);
    }

    public function createPreview(string $filename): Model
    {
        $link = Storage::path($filename);
        $pathinfo = pathinfo($link);

        if (!Storage::getAdapter() instanceof (LocalFilesystemAdapter::class)) {
            $content = Storage::get($filename);

            $localPath = '/temp_files/' . $filename;

            Storage::disk('local')->put($localPath, $content);

            $filePath = Storage::disk('local')->path($localPath);
        } else {
            $filePath = Storage::path($filename);
        }

        $previewLocalPath = '/temp_files/preview_' . $filename;

        Image::load($filePath)
            ->width(config('media.preview.width'))
            ->height(config('media.preview.height'))
            ->save(Storage::disk('local')->path($previewLocalPath));

        Storage::put('preview_' . $filename, Storage::disk('local')->get($previewLocalPath));

        if (!Storage::getAdapter() instanceof (LocalFilesystemAdapter::class)) {
            Storage::disk('local')->delete($filePath);
        }
        Storage::disk('local')->delete($previewLocalPath);

        $name = "preview_{$filename}";
        $data['name'] = $name;
        $data['link'] = "{$pathinfo['dirname']}/{$name}";
        $data['owner_id'] = Auth::id();

        return $this->repository->create($data);
    }
}