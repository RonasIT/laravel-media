<?php

namespace RonasIT\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RonasIT\Support\Traits\ModelTrait;
use RonasIT\Media\Database\Factories\MediaFactory;

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
        'preview_id',
        'blur_hash',
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

    public function preview(): BelongsTo
    {
        return $this->belongsTo(self::class, 'preview_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            related: self::class,
            foreignKey: 'id',
            ownerKey: 'preview_id',
        );
    }

    protected static function newFactory(): MediaFactory
    {
        return MediaFactory::new();
    }
}
