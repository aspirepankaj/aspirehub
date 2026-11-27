Add-Type -AssemblyName System.Drawing
$img = [System.Drawing.Bitmap]::FromFile('C:/Users/ACSS/.gemini/antigravity/brain/dce13d4e-6043-4cf1-9733-d94ec910d46c/.user_uploaded/media_1795782250441_7bac84b1.png')
$crop = New-Object System.Drawing.Bitmap 400, 160
$g = [System.Drawing.Graphics]::FromImage($crop)
$rect = New-Object System.Drawing.Rectangle 120, 200, 400, 160
$dest = New-Object System.Drawing.Rectangle 0, 0, 400, 160
$g.DrawImage($img, $dest, $rect, [System.Drawing.GraphicsUnit]::Pixel)
$crop.Save('scratch/crop_key_events2.png', [System.Drawing.Imaging.ImageFormat]::Png)
$g.Dispose()
$crop.Dispose()
$img.Dispose()
Write-Host "Saved scratch/crop_key_events2.png"
