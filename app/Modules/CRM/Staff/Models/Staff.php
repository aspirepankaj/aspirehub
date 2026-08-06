<?php

namespace App\Modules\CRM\Staff\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
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
}