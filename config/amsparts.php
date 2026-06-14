<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Company Information
    |--------------------------------------------------------------------------
    */
    'logo_url'    => env('LOGO_URL', 'images/logo.png'),
    'company'     => [
        'name'    => env('COMPANY_NAME', 'Parts Plus Innovation Solutions'),
        'phone'   => env('COMPANY_PHONE', '(917) 640-3410'),
        'email'   => env('COMPANY_EMAIL', 'parts@example.com'),
        'address' => env('COMPANY_ADDRESS', '2710 S. Main Street, Middletown, OH 45044'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Notification Email
    |--------------------------------------------------------------------------
    */
    'admin_email' => env('ADMIN_NOTIFY_EMAIL', 'parts@example.com'),
    'jobs_email'  => env('JOBS_EMAIL', 'jobs@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'per_page'    => [
        'parts' => env('PARTS_PER_PAGE', 20),
        'blog'  => env('BLOG_PER_PAGE', 12),
        'admin' => env('ADMIN_PER_PAGE', 25),
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Upload Limits
    |--------------------------------------------------------------------------
    */
    'media'       => [
        'max_size_kb'   => env('MEDIA_MAX_SIZE_KB', 5120),
        'allowed_mimes' => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
        'image_quality' => env('IMAGE_QUALITY', 85),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache TTLs (seconds)
    |--------------------------------------------------------------------------
    */
    'cache'       => [
        'nav_makes'      => 3600,
        'nav_categories' => 3600,
        'site_settings'  => 3600,
        'admin_badges'   => 120,
    ],

];
