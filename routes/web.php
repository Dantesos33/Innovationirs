<?php

use App\Http\Controllers\Admin\AdminBlogCategoriesController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminBlogTagsController;
use App\Http\Controllers\Admin\AdminCareersController;
use App\Http\Controllers\Admin\AdminCategoriesController;
use App\Http\Controllers\Admin\AdminContactsController;
use App\Http\Controllers\Admin\AdminEquipmentModelsController;
use App\Http\Controllers\Admin\AdminEquipmentTypesController;
use App\Http\Controllers\Admin\AdminFaqsController;
use App\Http\Controllers\Admin\AdminGalleryController;
use App\Http\Controllers\Admin\AdminHeavyDutyToolsController;
use App\Http\Controllers\Admin\AdminJobApplicationsController;
use App\Http\Controllers\Admin\AdminMakesController;
use App\Http\Controllers\Admin\AdminMediaController;
use App\Http\Controllers\Admin\AdminNewsletterController;
use App\Http\Controllers\Admin\AdminPartsController;
use App\Http\Controllers\Admin\AdminQuotesController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminTestimonialsController;
use App\Http\Controllers\Admin\AdminToolOrdersController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ToolsController;
use Illuminate\Support\Facades\Route;

/* ══════════════════════════════════════════════════════════
   PUBLIC FRONTEND ROUTES
══════════════════════════════════════════════════════════ */

// ── Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Parts catalog + detail
Route::prefix('parts')->name('parts.')->group(function () {
    Route::get('/', [PartsController::class, 'index'])->name('index');
    Route::get('/new', [PartsController::class, 'newParts'])->name('new');
    Route::get('/used', [PartsController::class, 'usedParts'])->name('used');
    Route::get('/rebuilt', [PartsController::class, 'rebuiltParts'])->name('rebuilt');
    Route::get('/{slug}', [PartsController::class, 'show'])->name('show');
});

// ── Site-wide search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// ── Makes (brands)
Route::get('/makes', [PartsController::class, 'makesIndex'])->name('makes.index');
Route::get('/makes/{slug}', [PartsController::class, 'makeShow'])->name('makes.show');

// ── Part Categories
Route::get('/categories', [PartsController::class, 'categoriesIndex'])->name('categories.index');
Route::get('/categories/{slug}', [PartsController::class, 'categoryShow'])->name('categories.show');

// ── Equipment Types
Route::get('/equipment', [PartsController::class, 'equipmentTypesIndex'])->name('equipment-types.index');
Route::get('/equipment/{slug}', [PartsController::class, 'equipmentTypeShow'])->name('equipment-types.show');

// ── Quotes
Route::get('/quote', [QuoteController::class, 'create'])->name('quote.create');
Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store');

// ── Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// ── Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// ── Gallery
Route::get('/galleries', [GalleryController::class, 'index'])->name('gallery');

// ── Heavy Duty Tools (public catalog)
Route::prefix('tools')->name('tools.')->group(function () {
    Route::get('/', [ToolsController::class, 'index'])->name('index');
    Route::get('/{slug}', [ToolsController::class, 'show'])->name('show');
});

// ── Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/summary', [CartController::class, 'summary'])->name('summary');
});

// ── Checkout (Stripe)
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('payment-intent');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place-order');
    Route::get('/confirmation', [CheckoutController::class, 'confirmation'])->name('confirmation');
});

// ── Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// ── Static pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/careers/{career}/apply', [JobApplicationController::class, 'show'])->name('careers.apply');
Route::post('/careers/{career}/apply', [JobApplicationController::class, 'store'])->name('careers.apply.store');
Route::get('/warranty', [PageController::class, 'warranty'])->name('warranty');
Route::get('/shipping', [PageController::class, 'shipping'])->name('shipping');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/prop65', [PageController::class, 'prop65'])->name('prop65');

/* ══════════════════════════════════════════════════════════
   ADMIN AUTH ROUTES
══════════════════════════════════════════════════════════ */

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

/* ══════════════════════════════════════════════════════════
   ADMIN PANEL ROUTES (authenticated)
══════════════════════════════════════════════════════════ */

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ── Parts
    Route::get('/parts/export', [AdminPartsController::class, 'export'])->name('parts.export');
    Route::post('/parts/bulk-action', [AdminPartsController::class, 'bulkAction'])->name('parts.bulk');
    Route::patch('/parts/{part}/toggle', [AdminPartsController::class, 'toggle'])->name('parts.toggle');
    Route::resource('parts', AdminPartsController::class)->names([
        'index' => 'parts.index', 'create' => 'parts.create', 'store' => 'parts.store',
        'show'  => 'parts.show', 'edit'    => 'parts.edit', 'update'  => 'parts.update', 'destroy' => 'parts.destroy',
    ]);

    // ── Categories
    Route::post('/categories/reorder', [AdminCategoriesController::class, 'reorder'])->name('categories.reorder');
    Route::resource('categories', AdminCategoriesController::class)->names([
        'index' => 'categories.index', 'create' => 'categories.create', 'store'   => 'categories.store',
        'edit'  => 'categories.edit', 'update'  => 'categories.update', 'destroy' => 'categories.destroy',
    ]);

    // ── Makes
    Route::resource('makes', AdminMakesController::class)->names([
        'index' => 'makes.index', 'create' => 'makes.create', 'store'   => 'makes.store',
        'edit'  => 'makes.edit', 'update'  => 'makes.update', 'destroy' => 'makes.destroy',
    ]);

    // ── Equipment Types
    Route::resource('equipment-types', AdminEquipmentTypesController::class)->names([
        'index' => 'equipment-types.index', 'create' => 'equipment-types.create', 'store'   => 'equipment-types.store',
        'edit'  => 'equipment-types.edit', 'update'  => 'equipment-types.update', 'destroy' => 'equipment-types.destroy',
    ]);

    // ── Equipment Models
    Route::resource('equipment-models', AdminEquipmentModelsController::class)->names([
        'index' => 'equipment-models.index', 'create' => 'equipment-models.create', 'store'   => 'equipment-models.store',
        'edit'  => 'equipment-models.edit', 'update'  => 'equipment-models.update', 'destroy' => 'equipment-models.destroy',
    ]);

    // ── Quotes
    Route::get('/quotes/export', [AdminQuotesController::class, 'export'])->name('quotes.export');
    Route::patch('/quotes/{quote}/status', [AdminQuotesController::class, 'updateStatus'])->name('quotes.status');
    Route::post('/quotes/{quote}/reply', [AdminQuotesController::class, 'reply'])->name('quotes.reply');
    Route::post('/quotes/bulk-action', [AdminQuotesController::class, 'bulkAction'])->name('quotes.bulk');
    Route::resource('quotes', AdminQuotesController::class)->only(['index', 'show', 'update', 'destroy'])->names([
        'index' => 'quotes.index', 'show' => 'quotes.show', 'update' => 'quotes.update', 'destroy' => 'quotes.destroy',
    ]);

    // ── Contacts
    Route::get('/contacts/export', [AdminContactsController::class, 'export'])->name('contacts.export');
    Route::patch('/contacts/{contact}/status', [AdminContactsController::class, 'updateStatus'])->name('contacts.status');
    Route::post('/contacts/{contact}/reply', [AdminContactsController::class, 'reply'])->name('contacts.reply');
    Route::resource('contacts', AdminContactsController::class)->only(['index', 'show', 'update', 'destroy'])->names([
        'index' => 'contacts.index', 'show' => 'contacts.show', 'update' => 'contacts.update', 'destroy' => 'contacts.destroy',
    ]);

    // ── Blog
    Route::resource('blog', AdminBlogController::class)->names([
        'index' => 'blog.index', 'create' => 'blog.create', 'store'   => 'blog.store', 'show' => 'blog.show',
        'edit'  => 'blog.edit', 'update'  => 'blog.update', 'destroy' => 'blog.destroy',
    ]);

    // ── Blog Categories
    Route::resource('blog-categories', AdminBlogCategoriesController::class)->names([
        'index' => 'blog-categories.index', 'create' => 'blog-categories.create', 'store'   => 'blog-categories.store',
        'edit'  => 'blog-categories.edit', 'update'  => 'blog-categories.update', 'destroy' => 'blog-categories.destroy',
    ]);

    // ── Blog Tags
    Route::resource('blog-tags', AdminBlogTagsController::class)->names([
        'index' => 'blog-tags.index', 'create' => 'blog-tags.create', 'store'   => 'blog-tags.store',
        'edit'  => 'blog-tags.edit', 'update'  => 'blog-tags.update', 'destroy' => 'blog-tags.destroy',
    ]);

    // ── Testimonials
    Route::patch('/testimonials/{testimonial}/toggle', [AdminTestimonialsController::class, 'toggle'])->name('testimonials.toggle');
    Route::resource('testimonials', AdminTestimonialsController::class)->names([
        'index' => 'testimonials.index', 'create' => 'testimonials.create', 'store'   => 'testimonials.store',
        'edit'  => 'testimonials.edit', 'update'  => 'testimonials.update', 'destroy' => 'testimonials.destroy',
    ]);

    // ── FAQs
    Route::post('/faqs/reorder', [AdminFaqsController::class, 'reorder'])->name('faqs.reorder');
    Route::resource('faqs', AdminFaqsController::class)->names([
        'index' => 'faqs.index', 'create' => 'faqs.create', 'store'   => 'faqs.store',
        'edit'  => 'faqs.edit', 'update'  => 'faqs.update', 'destroy' => 'faqs.destroy',
    ]);

    // ── Careers
    Route::patch('/careers/{career}/toggle', [AdminCareersController::class, 'toggle'])->name('careers.toggle');
    Route::resource('careers', AdminCareersController::class)->names([
        'index' => 'careers.index', 'create' => 'careers.create', 'store'   => 'careers.store', 'show' => 'careers.show',
        'edit'  => 'careers.edit', 'update'  => 'careers.update', 'destroy' => 'careers.destroy',
    ]);

    // ── Newsletter
    Route::prefix('newsletter')->name('newsletter.')->group(function () {
        Route::get('/', [AdminNewsletterController::class, 'subscribers'])->name('subscribers');
        Route::get('/campaigns', [AdminNewsletterController::class, 'campaigns'])->name('campaigns');
        Route::get('/campaigns/compose', [AdminNewsletterController::class, 'create'])->name('compose');
        Route::post('/campaigns/send', [AdminNewsletterController::class, 'send'])->name('send');
        Route::get('/campaigns/{campaign}', [AdminNewsletterController::class, 'show'])->name('show');
        Route::get('/export', [AdminNewsletterController::class, 'exportSubscribers'])->name('export');
        Route::delete('/subscribers/{id}', [AdminNewsletterController::class, 'deleteSubscriber'])->name('remove');
    });

    // ── Media Library
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [AdminMediaController::class, 'index'])->name('index');
        Route::post('/upload', [AdminMediaController::class, 'upload'])->name('upload');
        Route::delete('/{id}', [AdminMediaController::class, 'destroy'])->name('destroy');
        Route::get('/picker', [AdminMediaController::class, 'picker'])->name('picker');
    });

    // ── Gallery
    Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery/upload', [AdminGalleryController::class, 'upload'])->name('gallery.upload');
    Route::patch('/gallery/{image}', [AdminGalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{image}', [AdminGalleryController::class, 'destroy'])->name('gallery.destroy');

    // ── Users
    Route::patch('/users/{user}/toggle', [AdminUsersController::class, 'toggle'])->name('users.toggle');
    Route::resource('users', AdminUsersController::class)->names([
        'index' => 'users.index', 'create' => 'users.create', 'store'   => 'users.store',
        'edit'  => 'users.edit', 'update'  => 'users.update', 'destroy' => 'users.destroy',
    ]);

    // ── Job Applications
    Route::get('/job-applications', [AdminJobApplicationsController::class, 'index'])->name('job-applications.index');
    Route::get('/job-applications/{application}', [AdminJobApplicationsController::class, 'show'])->name('job-applications.show');
    Route::patch('/job-applications/{application}', [AdminJobApplicationsController::class, 'update'])->name('job-applications.update');
    Route::patch('/job-applications/{application}/status', [AdminJobApplicationsController::class, 'updateStatus'])->name('job-applications.status');
    Route::delete('/job-applications/{application}', [AdminJobApplicationsController::class, 'destroy'])->name('job-applications.destroy');
    Route::get('/careers/{career}/applications', [AdminJobApplicationsController::class, 'byJob'])->name('job-applications.by-job');

    // ── Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/{group}', [AdminSettingsController::class, 'group'])->name('settings.group');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    // ── Profile
    Route::get('/profile', [AdminSettingsController::class, 'profile'])->name('profile');
    Route::post('/profile', [AdminSettingsController::class, 'updateProfile'])->name('profile.update');

    // ── Cache
    Route::post('/cache/clear', [AdminSettingsController::class, 'clearCache'])->name('cache.clear');

    // ── Heavy Duty Tools
    Route::get('/heavy-duty-tools/export', [AdminHeavyDutyToolsController::class, 'export'])->name('heavy-duty-tools.export');
    Route::post('/heavy-duty-tools/bulk-action', [AdminHeavyDutyToolsController::class, 'bulkAction'])->name('heavy-duty-tools.bulk');
    Route::patch('/heavy-duty-tools/{heavyDutyTool}/toggle', [AdminHeavyDutyToolsController::class, 'toggle'])->name('heavy-duty-tools.toggle');
    Route::post('/heavy-duty-tools/{heavyDutyTool}/remove-image', [AdminHeavyDutyToolsController::class, 'removeImage'])->name('heavy-duty-tools.remove-image');
    Route::resource('heavy-duty-tools', AdminHeavyDutyToolsController::class)->names([
        'index' => 'heavy-duty-tools.index', 'create' => 'heavy-duty-tools.create', 'store'   => 'heavy-duty-tools.store',
        'edit'  => 'heavy-duty-tools.edit', 'update'  => 'heavy-duty-tools.update', 'destroy' => 'heavy-duty-tools.destroy',
    ]);

    // ── Tool Orders
    Route::get('/tool-orders/export', [AdminToolOrdersController::class, 'export'])->name('tool-orders.export');
    Route::patch('/tool-orders/{toolOrder}/status', [AdminToolOrdersController::class, 'updateStatus'])->name('tool-orders.status');
    Route::resource('tool-orders', AdminToolOrdersController::class)->only(['index', 'show'])->names([
        'index' => 'tool-orders.index',
        'show'  => 'tool-orders.show',
    ]);
});
