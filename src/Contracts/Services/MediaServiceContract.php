<?php

namespace RonasIT\Media\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MediaServiceContract
{
    public function search(array $filters): LengthAwarePaginator;

    public function create(string $content, string $fileName, array $data): Model;

    public function bulkCreate(array $data): array;

    /**
     * @param $where array|integer|string
     * @return int
     */
    public function delete($where): int;

    public function get(array $where = []): Collection;
}
