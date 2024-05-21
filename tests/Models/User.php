<?php

namespace RonasIT\Media\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use RonasIT\Media\Models\Media;
use RonasIT\Support\Traits\ModelTrait;

class User extends Authenticatable
{
    use Notifiable;
    use ModelTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];

    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'owner_id');
    }
}
