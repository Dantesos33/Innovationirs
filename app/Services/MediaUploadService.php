<?php
namespace App\Services;

use App\Models\MediaLibrary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploadService
{
    protected string $disk = 'public';

    /**
     * Upload a file and create a MediaLibrary record.
     */
    public function upload(
        UploadedFile $file,
        string $directory,
        ?int $adminId = null,
        ?string $altText = null
    ): MediaLibrary {
        $filename     = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath     = $directory . '/' . $filename;
        $originalName = $file->getClientOriginalName();
        $mimeType     = $file->getMimeType();
        $extension    = strtolower($file->getClientOriginalExtension());
        $fileSize     = $file->getSize();

        // Store file on disk
        Storage::disk($this->disk)->putFileAs($directory, $file, $filename);

        // Get image dimensions if applicable
        $width = $height = null;
        if (str_starts_with($mimeType, 'image/')) {
            try {
                $imgInfo = getimagesize($file->getRealPath());
                if ($imgInfo) {
                    [$width, $height] = $imgInfo;
                }
            } catch (\Exception $e) {
                // Dimension read failed — non-critical
            }
        }

        $publicUrl = Storage::disk($this->disk)->url($filePath);

        return MediaLibrary::create([
            'admin_id'      => $adminId,
            'disk'          => $this->disk,
            'directory'     => $directory,
            'filename'      => $filename,
            'original_name' => $originalName,
            'file_path'     => $filePath,
            'url'           => null, // ← don't store URL; accessor generates it dynamically
            'mime_type'     => $mimeType,
            'extension'     => $extension,
            'file_size'     => $fileSize,
            'width'         => $width,
            'height'        => $height,
            'alt_text'      => $altText ?? pathinfo($originalName, PATHINFO_FILENAME),
        ]);
    }

    /**
     * Delete a media record and its file from disk.
     */
    public function delete(MediaLibrary $media): void
    {
        \Log::info('Deleting media', [
            'id'        => $media->id,
            'disk'      => $media->disk,
            'file_path' => $media->file_path,
        ]);

        if (
            ! empty($media->disk) &&
            ! empty($media->file_path) &&
            Storage::disk($media->disk)->exists($media->file_path)
        ) {
            Storage::disk($media->disk)->delete($media->file_path);
        }

        $media->delete();
    }

    /**
     * Replace an existing media record with a new upload.
     */
    public function replace(
        MediaLibrary $existing,
        UploadedFile $newFile,
        string $directory
    ): MediaLibrary {
        $this->delete($existing);
        return $this->upload($newFile, $directory);
    }
}
