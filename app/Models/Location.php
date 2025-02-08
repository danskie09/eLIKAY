<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'status',
        'description',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(LocationStatusHistory::class);
    }
}
