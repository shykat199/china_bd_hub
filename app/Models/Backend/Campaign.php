<?php

namespace App\Models\Backend;

use App\Models\Backend\CampaignReview;
use App\Models\Backend\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'banner_title',
        'video',
        'banner',
        'slug',
        'short_description',
        'description',
        'review',
        'product_id',
        'image_one',
        'image_two',
        'image_three',
        'status',
        'deadline',
        'top_title_1',
        'top_title_2',
        'heading_1',
        'feature_1',
        'feature_2',
        'heading_2',
        'heading_3',
        'heading_4',
        'note',
        'billing_details',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Product::class, 'id','product_id');
    }
    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'campaign_products', 'campaign_id', 'product_id');
    }
    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CampaignReview::class, 'campaign_id')->select('id','image','campaign_id');
    }
}
