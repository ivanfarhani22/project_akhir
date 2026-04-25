<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Upload Limits (in KB, for Laravel validation)
    |--------------------------------------------------------------------------
    |
    | These limits are used in request validation rules (max:<KB>)
    | and should be kept <= server limits (upload_max_filesize/post_max_size)
    |
    */

    // Materials uploaded by admin/guru (docs/images)
    'material_max_kb' => env('UPLOAD_MATERIAL_MAX_KB', 10 * 1024), // 10 MB

    // Assignment attachments uploaded by admin/guru
    'assignment_max_kb' => env('UPLOAD_ASSIGNMENT_MAX_KB', 10 * 1024), // 10 MB

    // Student submissions (often zip/rar)
    'submission_max_kb' => env('UPLOAD_SUBMISSION_MAX_KB', 20 * 1024), // 20 MB

    // Login banners (images)
    'banner_max_kb' => env('UPLOAD_BANNER_MAX_KB', 5 * 1024), // 5 MB
];
