<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'comments';

    /**
     * @var string[] $fillable
     */
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
     * @return BelongsTo|null
     */
    public function note() : ?BelongsTo
    {
        return $this->belongsTo(Note::class, 'id', 'note_id');
    }
}
