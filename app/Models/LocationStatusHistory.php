<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationStatusHistory extends Model
{
    //
    protected $table = 'location_status_history';

    protected $fillable = [
        'location_id',
        'status',
        'notes',
        'user_id'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
