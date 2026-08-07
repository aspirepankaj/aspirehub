<?php

namespace App\Modules\CRM\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceReportPlugin extends Model
{
    protected $table = 'adspv_maintenance_report_plugins';

    protected $fillable = [
        'report_id',
        'plugin_name',
        'old_version',
        'new_version',
        'status',
        'notes',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(MaintenanceReport::class, 'report_id');
    }
}
