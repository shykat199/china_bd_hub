<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierApis extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'api_key',
        'secret_key',
        'url',
        'token',
        'status',
        'store_id'
    ];
}
