<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'paket_id',
        'user_id',
        'order_id',
        'price',
        'currency',
        'status',
    ];

    // Paket ilişkisi
    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    // Kullanıcı ilişkisi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
