<?php

namespace App\Modules\CRM\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use \App\Modules\Core\Activity\Traits\LogsActivity;

    protected $table = 'adspv_media';

    protected $fillable = [
        'file_name',
        'file_path',
        'mime_type',
        'size',
    ];

    protected function getActivityDescription(string $action): string
    {
        $userName = auth()->user()->name ?? 'System';
        $fileName = $this->file_name ?? 'a file';
        if ($action === 'created') {
            return "{$userName} uploaded new media: {$fileName}";
        } elseif ($action === 'updated') {
            return "{$userName} updated media: {$fileName}";
        } elseif ($action === 'deleted') {
            return "{$userName} deleted media: {$fileName}";
        }
        return "{$userName} {$action} media: {$fileName}";
    }

    public static function syncStorageFiles()
    {
        // Automatically delete any historically synced adscljson media records
        self::where('file_path', 'like', 'adscljson/%')->delete();

        $allFiles = Storage::disk('public')->allFiles();
        $existingPathsMap = array_flip(self::pluck('file_path')->toArray());

        foreach ($allFiles as $filePath) {
            // Ignore adscljson integration directory files
            if (str_starts_with($filePath, 'adscljson/')) {
                continue;
            }

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

    public static function uploadFileFromUrl($url)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get($url);
            if (!$response->successful()) {
                return null;
            }
            $contents = $response->body();
        } catch (\Exception $e) {
            return null;
        }

        $year = date('Y');
        $month = date('m');
        $folder = "media/{$year}/{$month}";
        
        $originalName = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
        if (!$originalName) {
            $originalName = 'downloaded_image_' . time();
        }
        $originalName = preg_replace('/[^A-Za-z0-9\-_]/', '-', $originalName);
        
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (!$extension) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($contents);
            $mimeTypes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                'image/svg+xml' => 'svg',
                'application/pdf' => 'pdf',
            ];
            $extension = $mimeTypes[$mime] ?? 'jpg';
        }

        $fileName = "{$originalName}.{$extension}";
        
        $counter = 1;
        while (\Illuminate\Support\Facades\Storage::disk('public')->exists("{$folder}/{$fileName}")) {
            $fileName = "{$originalName}-{$counter}.{$extension}";
            $counter++;
        }
        
        $path = "{$folder}/{$fileName}";
        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $contents);
        
        $mimeType = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($path);
        $size = \Illuminate\Support\Facades\Storage::disk('public')->size($path);

        return self::create([
            'file_name' => $fileName,
            'file_path' => $path,
            'mime_type' => $mimeType ?: 'application/octet-stream',
            'size'      => $size ?: 0,
        ]);
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
