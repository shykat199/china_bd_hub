<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsGateways extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'api_key',
        'serderid',
        'order',
        'forget_pass',
        'password_g',
        'status',
    ];

}
