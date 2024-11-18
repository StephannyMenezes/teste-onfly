<?php

namespace App\Models;

use App\Notifications\TravelOrderStatusChanged;
use Carbon\Carbon;
use Database\Factories\TravelOrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

/**
 * @property integer $id
 * @property string $requester_name
 * @property string $destination
 * @property Carbon $departure_date
 * @property Carbon $return_date
 * @property string $status
 * @property ?string $notification_email
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
        'notification_email',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::updated(function ($order) {
            if ($order->isDirty('status') && $order->notification_email) {
                Notification::route('mail', $order->notification_email)
                    ->notify(new TravelOrderStatusChanged($order->id, $order->status));
            }
        });
    }
}
