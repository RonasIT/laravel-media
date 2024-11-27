<?php

namespace RonasIT\Media\Services;

use Illuminate\Support\Facades\Http;
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

    public function createPreview(string $link): Model
    {
        $pathinfo = pathinfo($link);
        $filename = $pathinfo['basename'];

        if (Storage::getDefaultDriver() == 'gcs') {
            $content = Http::get($link)
                ->getBody()
                ->getContents();

            $localPath = '/temp_files/' . $filename;

            Storage::disk('local')->put($localPath, $content);

            $previewLocalPath = '/temp_files/preview_' . $filename;

            Image::load(Storage::disk('local')->path($localPath))
                ->width(config('media.preview.width'))
                ->height(config('media.preview.height'))
                ->save(Storage::disk('local')->path($previewLocalPath));

            Storage::put('preview_' . $filename, Storage::disk('local')->get($previewLocalPath));

            unlink(Storage::disk('local')->path($localPath));
            unlink(Storage::disk('local')->path($previewLocalPath));
        } else {
            $link = Storage::path($filename);

            Image::load($link)
                ->width(config('media.preview.width'))
                ->height(config('media.preview.height'))
                ->save();
        }

        $name = "preview_{$filename}";
        $data['name'] = $name;
        $data['link'] = "{$pathinfo['dirname']}/{$name}";
        $data['owner_id'] = Auth::id();

        return $this->repository->create($data);
    }
}