<?php

namespace RonasIT\Media\Services;

use Bepsvpt\Blurhash\BlurHash;
use League\Flysystem\Local\LocalFilesystemAdapter;
use RonasIT\Media\Enums\PreviewDriverEnum;
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

    protected BlurHash $blurHash;

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

    public function create($content, string $fileName, array $data = [], PreviewDriverEnum ...$previewDrivers): Model
    {
        $fileName = $this->saveFile($fileName, $content);

        $this->createPreviews($fileName, $data, ...$previewDrivers);

        $data['name'] = $fileName;
        $data['link'] = Storage::url($data['name']);
        $data['owner_id'] = Auth::id();

        return $this->repository
            ->create($data)
            ->load('preview');
    }

    public function bulkCreate(array $data, PreviewDriverEnum ...$previewDrivers): array
    {
        return array_map(function ($media) use ($previewDrivers) {
            $file = $media['file'];
            $content = file_get_contents($file->getPathname());

            return $this->create($content, $file->getClientOriginalName(), $media, ...$previewDrivers);
        }, $data);
    }

    public function delete($where): int
    {
        $entity = $this->first($where);

        if (!empty($entity->preview_id)) {
            $this->delete($entity->preview_id);
        }

        Storage::delete($entity->name);

        return $this->repository->delete($where);
    }

    public function first(array|int $where = []): ?Model
    {
        return $this->repository->first($where);
    }

    public function createFilePreview(string $filename): Model
    {
        $this->createTempDir(Storage::disk('local')->path('temp_files'));

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

    protected function createHashPreview(string $fileName): string
    {
        $filePath = Storage::path($fileName);

        return $this->getBlurHashEncoder()->encode($filePath);
    }

    protected function getBlurHashEncoder(): BlurHash
    {
        return $this->blurHash ??= new BlurHash(
            config('blurhash.driver'),
            config('blurhash.components-x'),
            config('blurhash.components-y'),
            config('blurhash.resized-max-size')
        );
    }

    protected function createPreviews(string $fileName, array &$data, PreviewDriverEnum ...$previewTypes): void
    {
        if (empty($previewTypes)) {
            $previewTypes = config('media.drivers');
        }

        foreach ($previewTypes as $type) {
            if ($type === PreviewDriverEnum::File) {
                $preview = $this->createFilePreview($fileName);

                $data['preview_id'] = $preview->id;
            }

            if ($type === PreviewDriverEnum::Hash) {
                $data['blur_hash'] = $this->createHashPreview($fileName);
            }
        }
    }
}
