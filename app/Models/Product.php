<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) return 'Out of Stock';
        if ($this->stock <= 5) return 'Low Stock';
        return 'In Stock';
    }

    public function getStockBadgeClassAttribute(): string
    {
        if ($this->stock <= 0) return 'badge-danger';
        if ($this->stock <= 5) return 'badge-warning';
        return 'badge-success';
    }
}