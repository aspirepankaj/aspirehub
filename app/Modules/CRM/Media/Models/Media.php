<?php

namespace App\Modules\CRM\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'adspv_media';

    protected $fillable = [
        'file_name',
        'file_path',
        'mime_type',
        'size',
    ];

    public static function syncStorageFiles()
    {
        $allFiles = Storage::disk('public')->allFiles();
        $existingPathsMap = array_flip(self::pluck('file_path')->toArray());

        foreach ($allFiles as $filePath) {
            $baseName = basename($filePath);
            if (in_array($baseName, ['.DS_Store', '.gitignore']) || str_starts_with($baseName, '.')) {
                continue;
            }

            if (!isset($existingPathsMap[$filePath])) {
                $mimeType = Storage::disk('public')->mimeType($filePath) ?: 'application/octet-stream';
                $size = Storage::disk('public')->size($filePath) ?: 0;

                self::create([
                    'file_name' => $baseName,
                    'file_path' => $filePath,
                    'mime_type' => $mimeType,
                    'size'      => $size,
                ]);
            }
        }
    }

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
        while (Storage::disk('public')->exists("{$folder}/{$fileName}")) {
            $fileName = "{$originalName}-{$counter}.{$extension}";
            $counter++;
        }
        
        $path = $file->storeAs($folder, $fileName, 'public');
        
        return self::create([
            'file_name' => $fileName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size'      => $file->getSize(),
        ]);
    }
}
