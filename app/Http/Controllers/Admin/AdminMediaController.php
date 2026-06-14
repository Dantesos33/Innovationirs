<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMediaController extends Controller
{
    public function __construct(protected MediaUploadService $mediaService)
    {}

    public function index(Request $request)
    {
        $query = MediaLibrary::with('uploader')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('original_name', 'like', '%' . $request->search . '%')
                    ->orWhere('alt_text', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('directory')) {
            $query->where('directory', $request->directory);
        }
        if ($request->boolean('images_only')) {
            $query->images();
        }

        $files       = $query->paginate(30)->withQueryString();
        $directories = MediaLibrary::distinct()->pluck('directory')->filter()->sort()->values();

        return view('admin.media.index', compact('files', 'directories'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file'      => 'required|file|max:10240',
            'directory' => 'nullable|string|max:100',
            'alt_text'  => 'nullable|string|max:255',
        ]);

        $directory = $request->get('directory', 'gallery');
        $adminId   = Auth::guard('admin')->id();

        $media = $this->mediaService->upload(
            $request->file('file'),
            $directory,
            $adminId,
            $request->alt_text
        );

        return response()->json([
            'success' => true,
            'media'   => [
                'id'            => $media->id,
                'url'           => $media->public_url,
                'filename'      => $media->filename,
                'original_name' => $media->original_name,
                'alt_text'      => $media->alt_text,
                'file_size'     => $media->file_size_formatted,
                'mime_type'     => $media->mime_type,
                'width'         => $media->width,
                'height'        => $media->height,
            ],
        ]);
    }

    public function update(Request $request, MediaLibrary $media)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'title'    => 'nullable|string|max:255',
            'caption'  => 'nullable|string|max:500',
        ]);

        $media->update($request->only('alt_text', 'title', 'caption'));

        return response()->json(['success' => true]);
    }

    // public function destroy(MediaLibrary $media)
    // {
    //     $this->mediaService->delete($media);

    //     if (request()->expectsJson()) {
    //         return response()->json(['success' => true]);
    //     }

    //     return redirect()->route('admin.media.index')->with('success', 'File deleted.');
    // }

    public function destroy($id)
    {
        $media = MediaLibrary::findOrFail($id);

        $this->mediaService->delete($media);

        return redirect()->route('admin.media.index')->with('success', 'File deleted.');
    }
}
