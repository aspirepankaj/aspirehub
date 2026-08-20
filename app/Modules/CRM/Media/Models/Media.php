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

    public static function uploadFile($file)
    {
        $year = date('Y');
        $month = date('m');
        $folder = "media/{$year}/{$month}";
        
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // Clean the filename by keeping alphanumeric, dashes and underscores
        $originalName = preg_replace('/[^A-Za-z0-9\-_]/', '-', $originalName);
        $extension = $file->getClientOriginalExtension();
        $fileName = "{$originalName}.{$extension}";
        
        $counter = 1;
        while (\Illuminate\Support\Facades\Storage::disk('public')->exists("{$folder}/{$fileName}")) {
            $fileName = "{$originalName}-{$counter}.{$extension}";
            $counter++;
        }
        
        $path = $file->storeAs($folder, $fileName, 'public');
        
        return self::create([
            'file_name' => $fileName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }
}
