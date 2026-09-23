<?php

namespace App\Modules\CRM\Websites\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteIntegration extends Model
{
    protected $table = 'adspv_website_integrations';

    protected $fillable = [
        'website_id',
        'integration_type',
        'status',
        'api_credentials',
        'auth_credentials',
        'last_sync_at',
    ];

    protected $casts = [
        'api_credentials' => 'encrypted:array',
        'auth_credentials' => 'encrypted:array',
        'last_sync_at' => 'datetime',
    ];

    public function getAttribute($key)
    {
        try {
            return parent::getAttribute($key);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            \Illuminate\Support\Facades\Log::warning("WebsiteIntegration ID " . ($this->id ?? 'new') . " attribute '{$key}' decryption failed: " . $e->getMessage());
            return null;
        }
    }

    public function fromEncryptedString($value)
    {
        try {
            return parent::fromEncryptedString($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            \Illuminate\Support\Facades\Log::warning("WebsiteIntegration ID " . ($this->id ?? 'new') . " decryption failed: " . $e->getMessage());
            return null;
        }
    }

    protected function castAttribute($key, $value)
    {
        try {
            return parent::castAttribute($key, $value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            \Illuminate\Support\Facades\Log::warning("WebsiteIntegration ID " . ($this->id ?? 'new') . " castAttribute '{$key}' decryption failed: " . $e->getMessage());
            return null;
        }
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
