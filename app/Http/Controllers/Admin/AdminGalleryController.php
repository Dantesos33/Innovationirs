<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGalleryController extends Controller
{
    public function __construct(protected MediaUploadService $mediaService)
    {}

    public function index(Request $request)
    {
        $query = MediaLibrary::where('directory', 'gallery')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('original_name', 'like', '%' . $request->search . '%')
                    ->orWhere('alt_text', 'like', '%' . $request->search . '%')
                    ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }

        $images = $query->paginate(30)->withQueryString();
        $total  = MediaLibrary::where('directory', 'gallery')->count();

        return view('admin.gallery.index', compact('images', 'total'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files'    => 'required|array',
            'files.*'  => 'file|image|mimes:jpg,jpeg,png,webp,gif|max:8192',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $adminId  = Auth::guard('admin')->id();
        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $media      = $this->mediaService->upload($file, 'gallery', $adminId, $request->alt_text);
            $uploaded[] = [
                'id'            => $media->id,
                'url'           => $media->public_url,
                'filename'      => $media->filename,
                'original_name' => $media->original_name,
                'alt_text'      => $media->alt_text,
                'file_size'     => $media->file_size_formatted,
                'width'         => $media->width,
                'height'        => $media->height,
            ];
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'uploaded' => $uploaded]);
        }

        return redirect()->route('admin.gallery.index')
            ->with('success', count($uploaded) . ' image(s) uploaded successfully.');
    }

    public function update(Request $request, MediaLibrary $image)
    {
        // Only allow updating gallery images
        if ($image->directory !== 'gallery') {
            abort(403);
        }

        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'title'    => 'nullable|string|max:255',
            'caption'  => 'nullable|string|max:500',
        ]);

        $image->update($request->only('alt_text', 'title', 'caption'));

        return response()->json(['success' => true]);
    }

    public function destroy(MediaLibrary $image)
    {
        if ($image->directory !== 'gallery') {
            abort(403);
        }

        $this->mediaService->delete($image);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Image deleted.');
    }
}
