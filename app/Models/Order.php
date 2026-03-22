<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'customer_name', 'customer_phone',
        'table_number', 'delivery_address',
        'order_type', 'total_amount',
        'status', 'notes',
        'payment_method', 'payment_status',
        'tracking_token',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->tracking_token = Str::random(12);
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'order_items')
                    ->withPivot('quantity', 'price');
    }
}