<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\TravelOrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    /** @use HasFactory<TravelOrderFactory> */
    use HasFactory;

    protected $fillable = [
        'requester_name',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
