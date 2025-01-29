<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paketler';

    protected $fillable = [
        'paketadi',
        'kontoradeti',
        'kontorbedeli',
        'paketbedeli',
    ];

}
