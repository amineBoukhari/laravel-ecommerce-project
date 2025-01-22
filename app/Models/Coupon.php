<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'discount_percentage', 'min_order_amount', 'usage_limit', 'used', 'expires_at'];

    public function isValid()
    {
        return (!$this->expires_at || $this->expires_at >= now()) && 
               (!$this->usage_limit || $this->used < $this->usage_limit);
    }
}
