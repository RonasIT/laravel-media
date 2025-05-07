<?php

namespace RonasIT\Media\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use RonasIT\Media\Enums\PreviewDriverEnum;

interface MediaServiceContract
{
    public function search(array $filters): LengthAwarePaginator;

    public function create(string $content, string $fileName, array $data, ?int $ownerId, PreviewDriverEnum ...$previewDrivers): Model;

    public function bulkCreate(array $data, PreviewDriverEnum ...$previewDrivers): array;

    /**
     * @param $where array|integer|string
     * @return int
     */
    public function delete($where): int;

    public function first(int|array $where = []): ?Model;
}
