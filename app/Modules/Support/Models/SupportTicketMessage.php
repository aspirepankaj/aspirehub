<?php

namespace App\Modules\Support\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketMessage extends Model
{
    protected $table = 'adspv_support_ticket_messages';

    protected $fillable = [
        'ticket_id',
        'sender_type',
        'sender_id',
        'message',
        'attachments',
        'is_read_by_client',
        'is_read_by_staff',
        'is_read_by_admin',
    ];

    protected $casts = [
        'attachments'       => 'array',
        'is_read_by_client' => 'boolean',
        'is_read_by_staff'  => 'boolean',
        'is_read_by_admin'  => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
