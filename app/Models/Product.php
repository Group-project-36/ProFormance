<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    public $incrementing = true;
    protected $keyType   = 'int';
    public $timestamps   = true;

    protected $fillable = [
        'product_name',
        'brand_id',
        'product_description',
        'base_price',
        'is_active',
        'short_description',
        'category_id',
        'meta_title',
        'meta_description',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'product_category',
            'product_id',
            'category_id'
        );
    }

    public function activeVariants()
    {
        return $this->variants()->where('is_active', true);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }
        
    public function getPrimaryImageUrlAttribute()
    {
        $image = $this->primaryImage;
        return $image ? $image->image_url : null;
    }
    
    public function images()
    {
        return ProductImage::whereIn('variant_id', function($query) {
            $query->select('variant_id')
                  ->from('product_variants')
                  ->where('product_id', $this->product_id);
        });
    }
    
     public function getPrimaryImage()
    {
        return $this->images()
            ->orderBy('sort_order', 'asc')
            ->first();
    }

     public function primaryImage()
    {
        return $this->hasOneThrough(
            ProductImage::class,
            ProductVariant::class,
            'product_id',
            'variant_id',
            'product_id',
            'variant_id'
        )->where('sort_order', 0)->withDefault();
    }

    public function getDefaultVariant()
{
    return $this->activeVariants()->first() ?? $this->variants()->first();
}
    
    public function getAnyImage()
    {
        $firstVariant = $this->variants()->first();
        
        if ($firstVariant) {
            return $firstVariant->getPrimaryImage();
        }
        
        return null;
    }

    public function bestPriceForCurrency(?string $currencyCode = null)
    {
        $currencyCode ??= session('currency', 'GBP');

        $price = VariantCurrencyPrice::query()
            ->whereHas('variant', function ($q) {
                $q->where('is_active', true)
                ->where('product_id', $this->product_id);
            })
            ->where('currency_code', strtoupper($currencyCode))
            ->orderBy('price_penny')
            ->first();

        if (! $price) {
            return null;
        }
        $currency = \App\Models\Currency::where('currency_code', $price->currency_code)->first();
        
        return (object) [
            'price'    => $price->price_penny / 100,
            'was'      => $price->was_price_penny ? $price->was_price_penny / 100 : null,
            'currency' => $price->currency_code,
            'symbol'   => $currency->symbol ?? $price->currency_code,
        ];
    }

    public function getDescriptionAttribute()
    {
        return $this->product_description;
    }
}
