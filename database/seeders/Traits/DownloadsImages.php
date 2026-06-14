<?php
namespace Database\Seeders\Traits;

use App\Models\MediaLibrary;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait DownloadsImages
{
    /**
     * Download a remote image, store it on the public disk, and create a MediaLibrary record.
     * url column is ALWAYS null — the public_url accessor on MediaLibrary generates it dynamically.
     */
    protected function downloadImage(string $url, string $directory, string $altText): ?int
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 20,
                    'follow_location' => true,
                    // Replaced user_agent with an array of realistic headers:
                    'header' => [
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
                        "Accept: image/avif,image/webp,image/apng,image/*,*/*;q=0.8",
                        "Accept-Language: en-US,en;q=0.9",
                        "Connection: keep-alive"
                    ]
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);


            $imageData = @file_get_contents($url, false, $context);

            if ($imageData === false || strlen($imageData) < 100) {
                if (isset($this->command)) {
                    $this->command->warn("  ⚠  Could not download: {$url}");
                }
                return null;
            }

            $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
            $filename = Str::uuid() . '.' . $ext;
            $filePath = $directory . '/' . $filename;

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
                'bmp' => 'image/bmp',
            ];
            $mimeType = $mimeMap[$ext] ?? 'image/jpeg';

            $media = MediaLibrary::create([
                'admin_id' => null,
                'disk' => 'public',
                'directory' => $directory,
                'filename' => $filename,
                'original_name' => basename(parse_url($url, PHP_URL_PATH)),
                'file_path' => $filePath,
                'url' => null, // NEVER hardcode — accessor generates dynamically
                'mime_type' => $mimeType,
                'extension' => $ext,
                'file_size' => strlen($imageData),
                'width' => $width,
                'height' => $height,
                'alt_text' => $altText,
                'title' => $altText,
                'caption' => null,
            ]);

            return $media->id;

        } catch (\Exception $e) {
            if (isset($this->command)) {
                $this->command->warn("  ✗ Image error [{$url}]: " . $e->getMessage());
            }
            return null;
        }
    }
}
