<?php

namespace App\Models\Backend;

use App\Models\Productimage;
use App\Models\Productstock;
use App\Modules\Backend\OrderManagement\Entities\OrderDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'category_id', 'brand_id', 'name', 'unit', 'tags', 'minimum_qty', 'barcode', 'sku', 'is_refundable', 'attributes', 'unit_price', 'purchase_price', 'sale_price', 'discount', 'quantity', 'shipping_cost', 'outside_shipping_cost', 'description', 'pdf_specification', 'meta_title', 'meta_description', 'meta_image', 'slug', 'total_viewed', 'is_active', 'publish_stat', 'warranty', 'return_policy', 'wholesale_status'];

    /**
     * A product is belongs to a category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function image()
    {
        return $this->hasOne(\App\Models\Backend\ProductImage::class, 'product_id')->select('id','image','product_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class)->withTimestamps();
    }

    public function productstock()
    {
        return $this->hasMany(Productstock::class);
    }

    public function images()
    {
        return $this->hasMany(\App\Models\Backend\ProductImage::class, 'product_id', 'id');
    }
    public function details()
    {
        return $this->hasOne(ProductDetail::class, 'product_id', 'id')->withDefault([]);
    }
    public function orders()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
}
