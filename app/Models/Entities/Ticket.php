<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $fillable = [
        'reservation_id',
        'seat_id',
        'ticket_code',
        'status'
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_USED = 'used';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_TRANSFERRED = 'transferred';

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            $ticket->ticket_code = Str::random(10);
        });
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function canBeTransferred(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function canBeCancelled(): bool
    {
        return $this->status === self::STATUS_ACTIVE && 
            $this->reservation->event->start_date->subHours(24)->isFuture();
    }

    public function isUsed(): bool
    {
        return $this->status === self::STATUS_USED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isTransferred(): bool
    {
        return $this->status === self::STATUS_TRANSFERRED;
    }
}
