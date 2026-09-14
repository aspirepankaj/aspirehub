<?php
$dir = new RecursiveDirectoryIterator('c:\MAMP\htdocs\NEW_ASPIRE_HUB\aspirehub\resources\views');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        $modified = false;
        
        if (stripos($content, 'aspire hub') !== false) {
            $content = str_ireplace('aspire hub', 'Aspire Digital Solutions', $content);
            $modified = true;
        }
        
        if (stripos($content, 'aspirehub') !== false && stripos($content, 'aspirehub.com') === false) {
            // Be careful not to replace in asset('aspirehub...') if it exists, wait, we might have asset('aspirehub')
        }
        
        if ($modified) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated lowercase " . $file->getPathname() . "\n";
        }
    }
}
echo "Done.\n";
