<?php

namespace App\Modules\CRM\Staff\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Core\Activity\Traits\LogsActivity;

class Staff extends Model
{
    use LogsActivity;

    protected $table = 'adspv_staff';

    protected $fillable = [
        'user_id',
        'company_name',
        'role',
        'department',
        'status',
        'notes',
        'added_by',
        'edited_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
        return $this->hasMany(StaffPhone::class, 'staff_id');
    }

    protected function getActivityDescription(string $action): string
    {
        $userName = auth()->user()->name ?? 'System';
        $staffName = $this->user->name ?? ($this->company_name ?: 'Staff');

        switch ($action) {
            case 'created':
                return "{$userName} added new staff member: '{$staffName}'";
            case 'updated':
                return "{$userName} updated staff details: '{$staffName}'";
            case 'deleted':
                return "{$userName} removed staff member: '{$staffName}'";
            default:
                return "{$userName} {$action} staff member: '{$staffName}'";
        }
    }
}