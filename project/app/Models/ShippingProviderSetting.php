<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingProviderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_name',
        'api_key',
        'account_number',
        'secret_key',
        'default_service_type_code',
        'is_active',
        'other_settings',
    ];
}
