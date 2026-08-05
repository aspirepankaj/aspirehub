<?php

namespace App\Modules\CRM\Websites\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Core\Activity\Traits\LogsActivity;

class ServiceType extends Model
{
    use LogsActivity;

    protected $table = 'adspv_service_types';

    protected $fillable = [
        'name',
        'color',
    ];

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class, 'service_type_id');
    }

    public function getActivityDescription(string $event): string
    {
        return match ($event) {
            'created' => "Service Type '{$this->name}' was added",
            'updated' => "Service Type '{$this->name}' was modified",
            'deleted' => "Service Type '{$this->name}' was deleted",
            default   => "Service Type '{$this->name}' event '{$event}' occurred",
        };
    }
}
