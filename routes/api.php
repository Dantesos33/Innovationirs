<?php

use App\Http\Controllers\Api\PartsApiController;
use App\Http\Controllers\PartsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — lightweight AJAX endpoints for the frontend
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:60,1')->group(function () {
    Route::prefix('parts')->group(function () {
        Route::get('/search-suggestions', [PartsApiController::class, 'searchSuggestions']);
        Route::get('/lookup', [PartsApiController::class, 'lookup']);
        Route::get('/featured', [PartsApiController::class, 'featured']);
        Route::get('/counts', [PartsApiController::class, 'counts']);
    });
    Route::get('/makes/slug/{slug}/models', [PartsApiController::class, 'modelsByMakeSlug']);
    Route::get('/makes/{makeId}/models', [PartsApiController::class, 'modelsByMake']);
});

Route::prefix('v1')->group(function () {

    // Get equipment models by make slug (for Quick Quote dropdowns)
    Route::get('/models', [PartsController::class, 'getModels'])
        ->name('api.models');

    // Autocomplete: part name/number suggestions
    Route::get('/parts/suggest', function (\Illuminate\Http\Request $request) {
        $request->validate(['q' => 'required|string|min:2|max:100']);

        $results = \App\Models\Part::active()
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', $request->q . '%')
                    ->orWhere('part_number', 'like', $request->q . '%');
            })
            ->with('primaryImage')
            ->take(8)
            ->get(['id', 'name', 'part_number', 'slug', 'primary_image_id']);

        return response()->json($results);
    })->name('api.parts.suggest');

});
