<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\File;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'email',
        'status',
        'info_blocked',
        'verify_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    public function activity()
    {
        return $this->morphOne('App\Models\Activity', 'activities');
    }
    public function photo_profile()
    {
        return $this->morphOne('App\Models\FilePhoto', 'pictures');
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            if($item->photo_profile)
            {
                if(is_file($item->photo_profile->path))
                {
                    File::delete($item->photo_profile->path);
                }
                $item->photo_profile->delete();
            }
        });
    }
}
