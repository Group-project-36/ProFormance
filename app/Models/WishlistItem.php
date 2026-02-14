<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    protected $table = 'wishlist_items';
    public $incrementing = false;
    public $timestamps = false;

    // Only has created_at, no updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'wishlists_id',
        'variant_id',
    ];

    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class, 'wishlists_id', 'wishlist_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'variant_id');
    }
}
