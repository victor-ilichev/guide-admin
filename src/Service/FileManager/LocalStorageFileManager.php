<?php

declare(strict_types=1);

namespace App\Service\FileManager;

use App\Service\FileManager\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class LocalStorageFileManager implements FileManager
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @var string
     */
    private $uploadDirectory;

    public function __construct(SluggerInterface $slugger, string $uploadDirectory)
    {
        $this->slugger = $slugger;
        $this->uploadDirectory = $uploadDirectory;
    }

    public function upload(UploadedFile $file, ?string $additionalPath = null): string
    {
        $newFilename = $this->createFileName($file->getClientOriginalName(), $file->guessExtension());
        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                $this->uploadDirectory,
                $newFilename
            );

            return $newFilename;
        } catch (FileException $exception) {
            // ... handle exception if something happens during file upload
            throw
                new Exception\UploadException(
                    'Upload file failed',
                    Exception\FileManagerException::OUTER_LOCAL_CODE,
                    $exception
                );
        }
    }

    public function delete(string $filePath): void
    {
        // TODO: Implement delete() method.
    }

    private function createFileName(string $fileName, string $fileExtension): string
    {
        $originalFilename = pathinfo($fileName, PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);

        return $safeFilename.'-'.uniqid().'.'.$fileExtension;
    }
}
