<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $requester_name
 * @property string $destination
 * @property Carbon $departure_date
 * @property Carbon $return_date
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TravelOrder extends Model
{
    protected $fillable = [
        'requester_name',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'return_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
