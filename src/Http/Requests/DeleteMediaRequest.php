<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Contracts\Requests\DeleteMediaRequestContract;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use RonasIT\Support\BaseRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
        $this->media = app(MediaServiceContract::class)->with('parent')->first($this->route('id'));

        if (empty($this->media)) {
            throw new NotFoundHttpException(__('media::validation.exceptions.not_found', ['entity' => 'Media']));
        }

        if (!empty($this->media->parent)) {
            throw new BadRequestHttpException(__('media::validation.exceptions.media_is_preview'));
        }

        parent::validateResolved();
    }
}
