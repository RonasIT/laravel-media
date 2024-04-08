<?php

namespace RonasIT\Media\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use RonasIT\Support\Traits\ModelTrait;

class Media extends Model
{
    use ModelTrait;
    use SoftDeletes;
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
        'deleted_at' => 'date',
        'meta' => 'array',
    ];

    protected $hidden = ['pivot'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(config('media.classes.user_model'));
    }
}
