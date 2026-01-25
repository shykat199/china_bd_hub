<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignReview extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id','image'];
}
