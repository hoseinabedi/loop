<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'order_id',
        'customer_id',
        'total_price',
        'status',
        'response_message',
        'paid_ip',
    ];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
