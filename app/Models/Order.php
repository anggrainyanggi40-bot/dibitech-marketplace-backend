<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
        protected $fillable = [
        'user_id',
        'order_status',
        'order_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

}
