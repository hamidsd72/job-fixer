<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
class OffCode extends Model
{
    protected $guarded = [ "id",'created_at','updated_at' ];

    public function activity()
    {
        return $this->morphOne('App\Models\Activity', 'activities');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function user_create()
    {
        return $this->belongsTo('App\Models\User', 'create_user_id');
    }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {

        });

    }
}