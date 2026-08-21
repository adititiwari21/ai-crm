<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'title',
        'amount',
        'stage',
        'probability',
        'expected_close_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'probability' => 'integer',
        'expected_close_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
