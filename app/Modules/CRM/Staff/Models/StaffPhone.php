<?php

namespace App\Modules\CRM\Staff\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffPhone extends Model
{
    protected $table = 'adspv_staff_phones';

    protected $fillable = [
        'staff_id',
        'phone',
        'label',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}