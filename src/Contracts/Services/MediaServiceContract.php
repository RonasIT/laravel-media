<?php

namespace RonasIT\Media\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use RonasIT\Media\Enums\PreviewDriverEnum;

interface MediaServiceContract
{
    public function search(array $filters): LengthAwarePaginator;

    public function create(string $content, string $fileName, array $data, PreviewDriverEnum ...$previewDrivers): Model;

    public function bulkCreate(array $data, PreviewDriverEnum ...$previewDrivers): array;

    /**
     * @param  $where  array|integer|string
     */
    public function delete($where): int;

    public function first(int|array $where = []): ?Model;

    public function createFromStream(UploadedFile $uploadedFile, array $data = [], PreviewDriverEnum ...$previewDrivers): Model;
}
