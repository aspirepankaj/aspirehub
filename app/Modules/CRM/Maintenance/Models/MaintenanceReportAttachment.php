<?php

namespace App\Modules\CRM\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceReportAttachment extends Model
{
    protected $table = 'adspv_maintenance_report_attachments';

    protected $fillable = [
        'report_id',
        'file_path',
        'file_name',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(MaintenanceReport::class, 'report_id');
    }
}
