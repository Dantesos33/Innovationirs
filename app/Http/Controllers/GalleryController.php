<?php
namespace App\Http\Controllers;

use App\Models\MediaLibrary;

class GalleryController extends Controller
{
    public function index()
    {
        $images = MediaLibrary::where('directory', 'gallery')
            ->images()
            ->latest()
            ->paginate(30);

        return view('pages.gallery', compact('images'));
    }
}
