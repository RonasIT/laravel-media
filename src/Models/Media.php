<?php

namespace RonasIT\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RonasIT\Support\Traits\ModelTrait;

class Media extends Model
{
    use ModelTrait;
    use HasFactory;

    protected $fillable = [
        'link',
        'name',
        'owner_id',
        'is_public',
        'meta',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'meta' => 'array',
    ];

    protected $hidden = ['pivot'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(config('media.classes.user_model'));
    }
}
