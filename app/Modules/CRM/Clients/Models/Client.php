<?php

namespace App\Modules\CRM\Clients\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Modules\Core\Activity\Traits\LogsActivity;

class Client extends Model
{
    use LogsActivity;

    protected $table = 'adspv_clients';

    protected $fillable = [
        'user_id',
        'company_name',
        'profile_image',
        'status',
        'notes',
        'added_by',
        'edited_by',
        'address',
        'landmark',
        'state',
        'country',
        'region',
        'zip_code',
        'last_login_at',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedStaff(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Modules\CRM\Staff\Models\Staff::class, 'adspv_client_staff', 'client_id', 'staff_id')->withTimestamps();
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function editedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(ClientPhone::class, 'client_id');
    }

    public function plans(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'adspv_client_plan', 'client_id', 'plan_id')->withTimestamps();
    }

    public function websites(): HasMany
    {
        return $this->hasMany(\App\Modules\CRM\Websites\Models\Website::class, 'client_id');
    }

    protected function getActivityDescription(string $action): string
    {
        $userName = auth()->user()->name ?? 'System';
        $clientName = $this->company_name ?: ($this->user->name ?? 'Client');
        
        switch ($action) {
            case 'created':
                return "{$userName} added new client: '{$clientName}'";
            case 'updated':
                return "{$userName} updated client details: '{$clientName}'";
            case 'deleted':
                return "{$userName} deleted client: '{$clientName}'";
            default:
                return "{$userName} performed action '{$action}' on client: '{$clientName}'";
        }
    }

    public function getInitials(): string
    {
        $name = $this->user->name ?? '';
        $words = preg_split("/\s+/", trim($name));
        $initials = "";
        foreach ($words as $w) {
            $initials .= mb_substr($w, 0, 1);
        }
        return mb_strtoupper(mb_substr($initials, 0, 2));
    }
}
