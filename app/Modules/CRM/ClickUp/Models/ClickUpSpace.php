<?php

namespace App\Modules\CRM\ClickUp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClickUpSpace extends Model
{
    protected $table = 'adspv_clickup_spaces';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'color',
        'archived',
        'synced_at',
    ];

    protected $casts = [
        'archived' => 'boolean',
        'synced_at' => 'datetime',
    ];

    public function folders(): HasMany
    {
        return $this->hasMany(ClickUpFolder::class, 'clickup_space_id', 'id');
    }
}
