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
        return $this->hasMany(ClientPhone::class, 'client_id');
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
}
