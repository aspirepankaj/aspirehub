<?php

namespace App\Modules\CRM\ClickUp\Models;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClickUpFolder extends Model
{
    protected $table = 'adspv_clickup_folders';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'clickup_space_id',
        'name',
        'task_count',
        'archived',
        'client_id',
        'website_id',
        'synced_at',
    ];

    protected $casts = [
        'task_count' => 'integer',
        'archived' => 'boolean',
        'synced_at' => 'datetime',
    ];

    public function space(): BelongsTo
    {
        return $this->belongsTo(ClickUpSpace::class, 'clickup_space_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    public function isMapped(): bool
    {
        return !is_null($this->client_id) || !is_null($this->website_id);
    }
}
