<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'invoice_number',
        'amount',
        'invoice_date',
        'due_date',
        'status',
        'items',
        'tax_rate',
        'discount',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'discount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'items' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'Pending' && $this->due_date && $this->due_date->isPast();
    }
}