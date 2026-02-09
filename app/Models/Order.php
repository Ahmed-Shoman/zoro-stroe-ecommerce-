<?php

namespace App\Models;
use App\Enums\OrderStatus;
use App\Notifications\OrderStatusChanged;
use Illuminate\Database\Eloquent\Relations\HasMany;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'total',
        'status',
         'status' => OrderStatus::class,

    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function canTransitionTo(string $newStatus): bool
{
    return match ($this->status) {
        'pending'   => in_array($newStatus, ['paid', 'cancelled']),
        'paid'      => in_array($newStatus, ['shipped', 'cancelled']),
        'shipped'   => $newStatus === 'completed',
        default     => false,
    };
}
 public function notifyStatusChanged(): void
{
    $this->user->notify(
        new OrderStatusChanged($this)
    );
}


}