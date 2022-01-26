<?php

declare(strict_types=1);

namespace App\Service\FileManager;

use App\Service\FileManager\Exception\DeleteException;
use App\Service\FileManager\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileManager
{
    /**
     * @throws UploadException
     */
    public function upload(UploadedFile $file, ?string $additionalPath = null): string;

    /**
     * @throws DeleteException
     */
    public function delete(string $filePath): void;
}
