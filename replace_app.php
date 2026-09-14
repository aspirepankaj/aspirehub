<?php
$dir = new RecursiveDirectoryIterator('c:\MAMP\htdocs\NEW_ASPIRE_HUB\aspirehub\app');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.php')) {
        $content = file_get_contents($file->getPathname());
        $modified = false;
        
        if (stripos($content, 'Aspire Hub') !== false) {
            $content = str_ireplace('Aspire Hub', 'Aspire Digital Solutions', $content);
            $modified = true;
        }
        
        if (stripos($content, 'AspireHub') !== false) {
            $content = str_ireplace('AspireHub', 'AspireDigitalSolutions', $content);
            $modified = true;
        }
        
        if ($modified) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated " . $file->getPathname() . "\n";
        }
    }
}
echo "Done.\n";
