<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'website',
        'requirements',
        'website_title',
        'website_description',
        'website_headings',
        'ai_summary',
        'industry',
        'target_audience',
        'tech_stack',
        'lead_score',
        'generated_pitch',
        'status',
    ];

    protected $casts = [
        'lead_score' => 'integer',
    ];

    public function getScoreBadgeClassAttribute(): string
    {
        if ($this->lead_score >= 80) return 'badge-hot';
        if ($this->lead_score >= 50) return 'badge-warm';
        return 'badge-cold';
    }

    public function getScoreLabelAttribute(): string
    {
        if ($this->lead_score >= 80) return '🔥 Hot Lead';
        if ($this->lead_score >= 50) return '⚡ Warm Lead';
        return '❄️ Cold Lead';
    }
}