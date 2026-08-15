<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
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
    ];
}