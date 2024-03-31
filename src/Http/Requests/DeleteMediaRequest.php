<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Requests\Request;
use RonasIT\Media\Contracts\Requests\DeleteMediaRequestContract;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMediaRequest extends Request implements DeleteMediaRequestContract
{
    protected $media;

    public function authorize(): bool
    {
        return $this->media->owner_id === $this->user()->id;
    }

    public function validateResolved(): void
    {
        $this->media = app(MediaServiceContract::class)->get($this->route('id'));

        if (!$this->media) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Media']));
        }

        parent::validateResolved();
    }
}
