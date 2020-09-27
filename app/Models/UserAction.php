<?php

namespace App\Models;

use App\Enums\LogLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserAction extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'user_actions';

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'state',
        'level',
        'user_id',
        'message',
    ];

    /**
     * @var string[] $casts
     */
    protected $casts = [
        'level' => LogLevel::class
    ];

    /**
     * @return HasOne|null
     */
    public function user() : ?HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
