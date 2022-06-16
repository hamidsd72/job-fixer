<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilePhoto extends Model
{

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function pictures()
    {
        return $this->morphTo();
    }

}
