<?php

namespace App\Modules\CRM\Websites\Models;

use App\Modules\CRM\Clients\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class Website extends Model
{
    protected $table = 'adspv_websites';

    protected $fillable = [
        'client_id',
        'site_name',
        'url',
        'site_type',
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
     * Service type labels and their badge colours.
     */
    public static array $siteTypes = [
        'maintenance'       => ['label' => 'Maintenance',       'color' => 'emerald'],
        'design'            => ['label' => 'Design',            'color' => 'pink'],
        'development'       => ['label' => 'Development',       'color' => 'indigo'],
        'speed_optimisation'=> ['label' => 'Speed Optimisation','color' => 'amber'],
        'other'             => ['label' => 'Other',             'color' => 'slate'],
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
}
