<?php

namespace App\Modules\Core\Activity\Models;

use App\Models\User;
use App\Modules\CRM\Maintenance\Models\MaintenanceReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $table = 'adspv_email_logs';

    protected $fillable = [
        'sender_id',
        'recipient_email',
        'subject',
        'status',
        'error_message',
        'report_id',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(MaintenanceReport::class, 'report_id');
    }
}
