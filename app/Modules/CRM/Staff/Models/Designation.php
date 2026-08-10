<?php

namespace App\Modules\CRM\Staff\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Modules\Core\Activity\Traits\LogsActivity;

class Designation extends Model
{
    use LogsActivity;

    protected $table = 'adspv_designations';

    protected $fillable = [
        'name',
        'description',
        'added_by',
        'edited_by',
    ];

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'adspv_staff_designation', 'designation_id', 'staff_id');
    }

    protected function getActivityDescription(string $action): string
    {
        $userName = auth()->user()->name ?? 'System';
        
        switch ($action) {
            case 'created':
                return "{$userName} added new designation: '{$this->name}'";
            case 'updated':
                return "{$userName} updated designation: '{$this->name}'";
            case 'deleted':
                return "{$userName} deleted designation: '{$this->name}'";
            default:
                return "{$userName} performed action '{$action}' on designation: '{$this->name}'";
        }
    }
}
