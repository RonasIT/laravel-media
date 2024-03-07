<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Requests\Request;
use RonasIT\Media\Contracts\Requests\DeleteMediaRequestContract;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMediaRequest extends Request implements DeleteMediaRequestContract
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function validateResolved(): void
    {
        parent::validateResolved();

        $service = app(MediaServiceContract::class);

        if (!$service->exists($this->route('id'))) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Media']));
        }
    }
}
