<?php

namespace RonasIT\Media\Services;

use Illuminate\Support\Facades\File as FileFacade;
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

use function PHPUnit\Framework\directoryExists;

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
        $data['preview_id'] = $this->createPreview($data['name'])->id;

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

        $preview = $this->first($entity->preview_id);

        Storage::delete([$entity->name, $preview->name]);

        $this->repository->delete($preview->id);

        return $this->repository->delete($where);
    }

    public function first(array|int $where = []): ?Model
    {
        return $this->repository->first($where);
    }

    public function createPreview(string $filename): Model
    {
        $this->createTempDir(Storage::path('temp_files'));

        $filePath = Storage::path($filename);
        $previewFilename = "preview_{$filename}";
        $tempPreviewFilePath = "/temp_files/{$previewFilename}";

        $tempFilePath = "/temp_files/{$filename}";

        $content = Storage::get($filename);

        if (!$this->isLocalStorageUsing()) {
            Storage::disk('local')->put($tempFilePath, $content);

            $filePath = Storage::disk('local')->path($tempFilePath);
        }

        Image::load($filePath)
            ->width(config('media.preview.width'))
            ->height(config('media.preview.height'))
            ->save(Storage::disk('local')->path($tempPreviewFilePath));

        Storage::put($previewFilename, Storage::disk('local')->get($tempPreviewFilePath));

        if (!$this->isLocalStorageUsing()) {
            Storage::disk('local')->delete(Storage::path($tempFilePath));
        }

        Storage::disk('local')->delete($tempPreviewFilePath);

        $data['name'] = $previewFilename;
        $data['link'] = Storage::url($previewFilename);
        $data['owner_id'] = Auth::id();

        return $this->repository->create($data);
    }

    private function isLocalStorageUsing(): bool
    {
        return Storage::getAdapter() instanceof (LocalFilesystemAdapter::class);
    }

    protected function createTempDir(string $name): void
    {
        if (!is_dir($name)) {
            mkdir(
                directory: $name,
                recursive: true,
            );
        }
    }
}