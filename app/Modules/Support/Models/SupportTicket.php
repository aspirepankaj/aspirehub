<?php

namespace App\Modules\Support\Models;

use App\Models\User;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Staff\Models\Staff;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Core\Activity\Traits\LogsActivity;

class SupportTicket extends Model
{
    use LogsActivity;

    protected $table = 'adspv_support_tickets';

    protected $fillable = [
        'ticket_number',
        'client_id',
        'website_id',
        'subject',
        'category',
        'priority',
        'status',
        'assigned_staff_id',
        'created_by_user_id',
        'last_reply_at',
    ];

    protected $casts = [
        'last_reply_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function latestMessage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SupportTicketMessage::class, 'ticket_id')->latestOfMany();
    }

    /**
     * Generate unique ticket number (e.g. ADS-TICK-2026-0001)
     */
    public static function generateTicketNumber(): string
    {
        $year = date('Y');
        $lastTicket = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastTicket && preg_match('/(\d{4})$/', $lastTicket->ticket_number, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }
        
        return sprintf('ADS-TICK-%s-%04d', $year, $nextNumber);
    }

    protected function getActivityDescription(string $action): string
    {
        $userName = auth()->user()->name ?? 'System';
        return "{$userName} {$action} support ticket '{$this->ticket_number}' ({$this->subject})";
    }
}
