<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function activities()
    {
        return $this->hasMany(ClientActivity::class)->orderBy('performed_at', 'desc');
    }

    public function getLifetimeValueAttribute(): float
    {
        return (float) $this->invoices()->where('status', 'Paid')->sum('amount');
    }

    public function getPendingInvoicesValueAttribute(): float
    {
        return (float) $this->invoices()->where('status', 'Pending')->sum('amount');
    }

    public function getOpenDealsValueAttribute(): float
    {
        return (float) $this->deals()->whereNotIn('stage', ['won', 'lost'])->sum('amount');
    }
}