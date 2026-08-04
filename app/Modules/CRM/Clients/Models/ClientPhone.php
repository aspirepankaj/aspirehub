<?php

namespace App\Modules\CRM\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPhone extends Model
{
    protected $table = 'adspv_client_phones';

    protected $fillable = [
        'client_id',
        'phone',
        'label',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
