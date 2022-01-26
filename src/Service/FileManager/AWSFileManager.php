<?php

namespace App\Service\FileManager;

use App\Service\FileManager\Exception\DeleteException;
use App\Service\FileManager\Exception\FileManagerException;
use App\Service\FileManager\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AWSFileManager implements FileManager
{
    /**
     * @var AwsS3ClientInterface
     */
    private $awsS3Client;

    /**
     * @var StringGenerator
     */
    private $stringGenerator;

    /**
     * @var string
     */
    private $awsCdn;

    /**
     * @var string
     */
    private $awsBucket;

    public function __construct(
        AwsS3ClientInterface $awsS3Client,
        StringGenerator $stringGenerator,
        string $awsCamModelPostImageCdn,
        string $awsCamModelPostImageBucket
    ) {
        $this->awsS3Client = $awsS3Client;
        $this->stringGenerator = $stringGenerator;
        $this->awsCdn = $awsCamModelPostImageCdn;
        $this->awsBucket = $awsCamModelPostImageBucket;
    }

    public function upload(UploadedFile $file, ?string $additionalPath = null): string
    {
        $fileName = $this->createFileName($file->guessExtension());

        try {
            $this->awsS3Client->putObject($this->awsBucket, $file->getPathname(), $fileName);
        } catch (AwsUtilException $exception) {
            throw
            new UploadException(
                '',
                FileManagerException::OUTER_ERROR_CODE,
                $exception
            );
        }

        return $this->createFileUrl($fileName, $additionalPath);
    }

    private function createFileName(string $fileExtension): string
    {
        return $this->stringGenerator->generate() . '.' . $fileExtension;
    }

    private function createFileUrl(string $fileName, ?string $additionalPath): string
    {
        return
            $this->awsCdn
            . '/'
            . ($additionalPath
                ? $additionalPath . '/'
                : '')
            . $fileName;
    }

    public function delete(string $filePath): void
    {
        try {
            $this->awsS3Client->removeObject($filePath, $this->awsBucket);
        } catch (AwsUtilException $exception) {
            throw
            new DeleteException(
                '',
                FileManagerException::OUTER_ERROR_CODE,
                $exception
            );
        }
    }
}
