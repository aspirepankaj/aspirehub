<?php

namespace App\Modules\CRM\Documents\Models;

use App\Models\User;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Core\Activity\Traits\LogsActivity;

class Document extends Model
{
    use LogsActivity;

    protected $table = 'adspv_documents';

    protected $fillable = [
        'resource_type',
        'url',
        'client_id',
        'website_id',
        'title',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'added_by',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    protected function getActivityDescription(string $action): string
    {
        $userName = auth()->user()->name ?? 'System';
        $docTitle = $this->title ?? 'Document';

        switch ($action) {
            case 'created':
                return "{$userName} uploaded new document: '{$docTitle}'";
            case 'updated':
                return "{$userName} updated document details: '{$docTitle}'";
            case 'deleted':
                return "{$userName} deleted document: '{$docTitle}'";
            default:
                return "{$userName} performed action '{$action}' on document: '{$docTitle}'";
        }
    }
}
