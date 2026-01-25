<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcomPixel extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'status'];

}
