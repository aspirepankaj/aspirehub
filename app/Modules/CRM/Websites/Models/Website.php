<?php

namespace App\Modules\CRM\Websites\Models;

use App\Modules\CRM\Clients\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

use App\Modules\Core\Activity\Traits\LogsActivity;

class Website extends Model
{
    use LogsActivity;

    protected $table = 'adspv_websites';

    protected $fillable = [
        'client_id',
        'site_name',
        'url',
        'image',
        'status',
        'admin_url',
        'admin_username',
        'admin_password',
        'hosting_provider',
        'server_ip',
        'notes',
        'added_by',
        'edited_by',
    ];

    /**
     * Encrypt password before saving.
     */
    public function setAdminPasswordAttribute(?string $value): void
    {
        if (!empty($value)) {
            $this->attributes['admin_password'] = Crypt::encryptString($value);
        } else {
            $this->attributes['admin_password'] = null;
        }
    }

    /**
     * Decrypt password when reading.
     */
    public function getAdminPasswordAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    // ─── Relationships ──────────────────────────────────────────────────────────

    public function maintenanceReports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Modules\CRM\Maintenance\Models\MaintenanceReport::class, 'website_id');
    }

    public function latestMaintenanceReport(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Modules\CRM\Maintenance\Models\MaintenanceReport::class, 'website_id')->latestOfMany();
    }

    public function serviceTypes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ServiceType::class, 'adspv_website_service_type', 'website_id', 'service_type_id')->withTimestamps();
    }

    public function plans(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Modules\CRM\Clients\Models\Plan::class, 'adspv_website_plan', 'website_id', 'plan_id')->withTimestamps();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function editedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    protected function getActivityDescription(string $action): string
    {
        $userName = auth()->user()->name ?? 'System';
        
        switch ($action) {
            case 'created':
                return "{$userName} added new website: '{$this->site_name}'";
            case 'updated':
                return "{$userName} updated website parameters: '{$this->site_name}'";
            case 'deleted':
                return "{$userName} deleted website profile: '{$this->site_name}'";
            default:
                return "{$userName} performed action '{$action}' on website '{$this->site_name}'";
        }
    }
}
