<?php

namespace App\Modules\CRM\Media\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'adspv_media';

    protected $fillable = [
        'file_name',
        'file_path',
        'mime_type',
        'size',
    ];
}
