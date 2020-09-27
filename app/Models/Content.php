<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Content extends Model
{
    use HasFactory;

    protected $table = 'contents';

    protected $fillable = [
        'state',
        'user_id',
        'note_id',
        'ip_address',
        'user_agent',
        'content',
    ];

    /**
     * @return HasOne|null
     */
    public function user() : ?HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne|null
     */
    public function note() : ?HasOne
    {
        return $this->hasOne(Note::class, 'id', 'note_id');
    }

    /**
     * @return HasMany|null
     */
    public function comments() : ?HasMany
    {
        return $this->hasMany(Comment::class, 'note_id', 'note_id');
    }
}
