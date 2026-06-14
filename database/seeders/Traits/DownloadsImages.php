<?php
namespace Database\Seeders\Traits;

use App\Models\MediaLibrary;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait DownloadsImages
{
    /**
     * Download a remote image, store it on the public disk, and create a MediaLibrary record.
     * Reuses existing MediaLibrary records if original_name matches to save time.
     */
    protected function downloadImage(string $url, string $directory, string $altText): ?int
    {
        $filename = basename(parse_url($url, PHP_URL_PATH));
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)) ?: 'jpg';
        
        // 1. Check database first to avoid redundant downloads
        $existing = MediaLibrary::where('original_name', $filename)
            ->where('directory', $directory)
            ->first();

        if ($existing) {
            if (isset($this->command)) {
                $this->command->info("  ✓ Reusing existing media: {$filename}");
            }
            return $existing->id;
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5, // Faster timeout
                    'follow_location' => true,
                    'header' => [
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
                        "Accept: image/avif,image/webp,image/apng,image/*,*/*;q=0.8",
                    ]
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            if (isset($this->command)) {
                $this->command->line("  ↓ Downloading: {$url}");
            }

            $imageData = @file_get_contents($url, false, $context);

            if ($imageData === false || strlen($imageData) < 100) {
                // Fallback to placeholder if download fails
                $placeholderPath = public_path('images/placeholder-part.jpg');
                if (file_exists($placeholderPath)) {
                    $imageData = file_get_contents($placeholderPath);
                    $filename = 'placeholder-' . Str::random(8) . '.jpg';
                    $ext = 'jpg';
                    if (isset($this->command)) {
                        $this->command->info("  → Download failed or blocked. Using placeholder.");
                    }
                } else {
                    return null;
                }
            }

            $newFilename = Str::uuid() . '.' . $ext;
            $filePath = $directory . '/' . $newFilename;

            Storage::disk('public')->put($filePath, $imageData);

            // Attempt to read dimensions
            $width = $height = null;
            try {
                $tmp = tempnam(sys_get_temp_dir(), 'ams_img_');
                file_put_contents($tmp, $imageData);
                $info = @getimagesize($tmp);
                if ($info) {
                    [$width, $height] = $info;
                }
                @unlink($tmp);
            } catch (\Exception) {
                // Non-critical
            }

            $mimeMap = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
            ];
            $mimeType = $mimeMap[$ext] ?? 'image/jpeg';

            $media = MediaLibrary::create([
                'disk' => 'public',
                'directory' => $directory,
                'filename' => $newFilename,
                'original_name' => basename(parse_url($url, PHP_URL_PATH)),
                'file_path' => $filePath,
                'mime_type' => $mimeType,
                'extension' => $ext,
                'file_size' => strlen($imageData),
                'width' => $width,
                'height' => $height,
                'alt_text' => $altText,
                'title' => $altText,
            ]);

            return $media->id;

        } catch (\Exception $e) {
            if (isset($this->command)) {
                $this->command->warn("  ✗ Error [{$url}]: " . $e->getMessage());
            }
            return null;
        }
    }
}
