<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateways extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'app_key',
        'app_secret',
        'username',
        'password',
        'base_url',
        'success_url',
        'return_url',
        'prefix',
        'status',
    ];
}
