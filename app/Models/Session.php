<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Session extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'sessions';

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];

    /**
     * @return HasOne|null
     */
    public function user() : ?HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
