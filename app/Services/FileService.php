<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileService
{
    protected $allowedPaths;

    public function __construct(array $allowedPaths = null)
    {
        $this->allowedPaths = $allowedPaths ?? Config::get('filemanager.allowed_paths', [
            base_path('app'),
            base_path('resources'),
            base_path('config'),
        ]);
    }

    public function readFile(string $path)
    {
        $this->validatePath($path);

        if (!File::exists($path)) {
            Log::error("File tidak ditemukan: {$path}");
            throw new FileException("File tidak ditemukan.");
        }

        return File::get($path);
    }

    public function writeFile(string $path, string $content)
    {
        $this->validatePath($path);

        if (!File::put($path, $content)) {
            Log::error("Gagal Membaca File: {$path}");
            throw new FileException("Gagal Membaca File.");
        }

        return true;
    }

    protected function validatePath(string &$path)
    {
        $realPath = realpath($path);
        if (!$realPath) {
            throw new \InvalidArgumentException("Invalid path.");
        }

        $isAllowed = array_reduce($this->allowedPaths, function ($carry, $allowedPath) use ($realPath) {
            return $carry || strpos($realPath, $allowedPath) === 0;
        }, false);

        if (!$isAllowed) {
            Log::warning("Tidak ada Akses: {$path}");
            throw new \InvalidArgumentException("Akses ditolak.");
        }

        $path = $realPath;
    }
}
