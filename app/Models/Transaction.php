<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Transaction extends Model
{

    protected $fillable = [
        'user_id',
        'package_name',
        'amount',
        'off_code',
        'status',
        'bank_issue_tracking',
        'bank_details',
        'bank_name',
    ];

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }

}