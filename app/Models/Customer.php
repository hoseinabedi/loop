<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'job_title',
        'email',
        'fullname',
        'registered_at',
        'phone',
    ];

    public function orders(){
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function payments(){
        return $this->hasMany(Payment::class, 'customer_id');
    }
}
