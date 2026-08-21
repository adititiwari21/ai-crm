<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_name',
        'admin_role',
        'company_name',
        'company_email',
        'company_phone',
        'currency',
        'currency_symbol',
        'gemini_api_key',
        'gemini_model',
        'webhook_secret',
    ];

    /**
     * Get initials of Administrator Name (e.g. "Aditi Tiwari" -> "AT")
     */
    public function getAdminInitialsAttribute()
    {
        $name = trim($this->admin_name ?: 'Administrator');
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }
}
