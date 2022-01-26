<?php

declare(strict_types=1);

namespace App\Service\FileManager\Exception;

use Exception;

class FileManagerException extends Exception
{
    public const OUTER_ERROR_CODE = 300;
    public const OUTER_LOCAL_CODE = 301;
}
