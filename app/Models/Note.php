<?php

namespace App\Models;

use App\Enums\States;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Note extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'notes';

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'state',
        'type',
        'user_id',
        'ip_address',
        'user_agent',
        'comments',
        'title',
    ];

    /**
     * @return HasOne|null
     */
    public function user() : ?HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return Model|HasMany|object|null
     */
    public function content()
    {
        return $this->contents()->where('state', States::Active)->latest()->first();
    }

    /**
     * @return HasMany|null
     */
    public function contents() : ?HasMany
    {
        return $this->hasMany(Content::class, 'note_id', 'id');
    }

    /**
     * @return HasMany|null
     */
    public function comments() : ?HasMany
    {
        return $this->hasMany(Comment::class, 'note_id', 'id');
    }
}
