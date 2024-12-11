<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Contracts\Requests\DeleteMediaRequestContract;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use RonasIT\Support\BaseRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class DeleteMediaRequest extends BaseRequest implements DeleteMediaRequestContract
{
    protected $media;

    public function authorize(): bool
    {
        return $this->media->owner_id === $this->user()->id;
    }

    public function validateResolved(): void
    {
        $this->media = app(MediaServiceContract::class)->first($this->route('id'));

        if (empty($this->media)) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Media']));
        }

        if (str_contains($this->media->name, 'preview')) {
            throw new UnprocessableEntityHttpException('You can not delete a preview media');
        }

        parent::validateResolved();
    }
}
