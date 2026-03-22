<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description',
        'price', 'image', 'is_veg',
        'is_available', 'is_special'
    ];

    protected $casts = [
        'is_veg' => 'boolean',
        'is_available' => 'boolean',
        'is_special' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}