<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state',
        'type',
        'first_name',
        'last_name',
        'email',
        'password',
        'ip_address',
        'user_agent',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name='.urlencode(strtolower($this->first_name.' '.$this->last_name)).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * @return string
     */
    public function visibleName() : string
    {
        $name = '';
        if (strlen($this->first_name) > 0) {
            $name = $this->first_name;
            if (strlen($this->last_name) > 0) {
                $name .= ' '.$this->last_name;
            }
        }
        else $name = $this->email;
        return ucwords(strtolower(trim($name)));
    }

    /**
     * @return HasOne|null
     */
    public function session() : ?HasOne
    {
        return $this->hasOne(Session::class, 'user_id', 'id');
    }

    /**
     * @return HasMany|null
     */
    public function actions() : ?HasMany
    {
        return $this->hasMany(UserAction::class, 'user_id', 'id');
    }

    /**
     * @return HasMany|null
     */
    public function notes() : ?HasMany
    {
        return $this->hasMany(Note::class, 'user_id', 'id');
    }

    /**
     * @return HasMany|null
     */
    public function contents() : ?HasMany
    {
        return $this->hasMany(Content::class, 'user_id', 'id');
    }

    /**
     * @return HasMany|null
     */
    public function comments() : ?HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
}
