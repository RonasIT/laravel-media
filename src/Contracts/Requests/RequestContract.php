<?php

namespace RonasIT\Media\Contracts\Requests;

interface RequestContract
{
    /**
     * Get the validated data from the request.
     *
     * @return array;
     */
    public function onlyValidated(array $keys = []): array;

    public function rules(): array;

    public function authorize(): bool;
}
