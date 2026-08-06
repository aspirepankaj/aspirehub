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

    public function websites(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Website::class, 'adspv_website_service_type', 'service_type_id', 'website_id')->withTimestamps();
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
