<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Allowed file types
     */
    const ALLOWED_MATERIAL_TYPES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif'];
    const ALLOWED_ASSIGNMENT_TYPES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif'];
    const ALLOWED_SUBMISSION_TYPES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'txt', 'zip', 'rar'];

    const MAX_MATERIAL_SIZE = 10 * 1024 * 1024; // 10 MB
    const MAX_ASSIGNMENT_SIZE = 10 * 1024 * 1024; // 10 MB
    const MAX_SUBMISSION_SIZE = 20 * 1024 * 1024; // 20 MB

    /**
     * Upload materi
     */
    public static function uploadMaterial(UploadedFile $file): ?string
    {
        return self::upload($file, 'materials', self::ALLOWED_MATERIAL_TYPES, self::MAX_MATERIAL_SIZE);
    }

    /**
     * Upload soal tugas
     */
    public static function uploadAssignment(UploadedFile $file): ?string
    {
        return self::upload($file, 'assignments', self::ALLOWED_ASSIGNMENT_TYPES, self::MAX_ASSIGNMENT_SIZE);
    }

    /**
     * Upload jawaban siswa
     */
    public static function uploadSubmission(UploadedFile $file): ?string
    {
        return self::upload($file, 'submissions', self::ALLOWED_SUBMISSION_TYPES, self::MAX_SUBMISSION_SIZE);
    }

    /**
     * Generic upload method
     */
    private static function upload(UploadedFile $file, string $folder, array $allowedTypes, int $maxSize): ?string
    {
        // Validate file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception("Tipe file '{$extension}' tidak diizinkan. Hanya: " . implode(', ', $allowedTypes));
        }

        // Validate file size
        if ($file->getSize() > $maxSize) {
            $maxSizeMB = round($maxSize / (1024 * 1024), 0);
            throw new \Exception("Ukuran file tidak boleh melebihi {$maxSizeMB} MB");
        }

        // Validate MIME type
        if (!self::isValidMimeType($file)) {
            throw new \Exception("File tidak valid atau mungkin file berbahaya");
        }

        // Generate unique filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = self::sanitizeFilename($originalName) . '_' . uniqid() . '.' . $extension;

        // Store file
        $path = $file->storeAs($folder, $filename, 'public');

        return $path ? 'storage/' . $path : null;
    }

    /**
     * Validate MIME type
     */
    private static function isValidMimeType(UploadedFile $file): bool
    {
        $mimeType = $file->getMimeType();
        
        // Whitelist MIME types
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'image/jpeg',
            'image/png',
            'image/gif',
            'text/plain',
            'application/zip',
            'application/x-rar-compressed',
        ];

        return in_array($mimeType, $allowedMimes);
    }

    /**
     * Sanitize filename
     */
    private static function sanitizeFilename(string $filename): string
    {
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        // Limit length
        return substr($filename, 0, 100);
    }

    /**
     * Delete file
     */
    public static function deleteFile(string $path): bool
    {
        $path = trim((string) $path);
        if ($path === '') {
            return false;
        }

        // Normalize common formats:
        // - 'storage/assignments/xxx.pdf' (public URL style)
        // - '/storage/assignments/xxx.pdf'
        // - full URL: https://domain.tld/storage/assignments/xxx.pdf
        // - disk path: 'assignments/xxx.pdf'
        $path = preg_replace('#^https?://[^/]+/#i', '', $path); // strip domain
        $path = ltrim($path, '/');
        $path = preg_replace('#^storage/#', '', $path);

        if ($path === '') {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return (bool) Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Get file size in human readable format
     */
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
