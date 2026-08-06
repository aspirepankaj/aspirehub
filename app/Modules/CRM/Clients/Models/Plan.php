<?php

namespace App\Modules\CRM\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Core\Activity\Traits\LogsActivity;

class Plan extends Model
{
    use LogsActivity;

    protected $table = 'adspv_plans';

    protected $fillable = [
        'name',
        'price',
        'color',
    ];

    public function clients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'adspv_client_plan', 'plan_id', 'client_id')->withTimestamps();
    }

    public function getActivityDescription(string $event): string
    {
        return match ($event) {
            'created' => "Plan '{$this->name}' was created",
            'updated' => "Plan '{$this->name}' was modified",
            'deleted' => "Plan '{$this->name}' was deleted",
            default   => "Plan '{$this->name}' event '{$event}' occurred",
        };
    }
}
