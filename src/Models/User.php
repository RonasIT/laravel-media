<?php

namespace RonasIT\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use RonasIT\Support\Traits\ModelTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use ModelTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'set_password_hash_created_at',
    ];

    protected $guarded = [
        'set_password_hash',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'set_password_hash',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'set_password_hash_created_at' => 'datetime',
    ];

    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'owner_id');
    }
}
