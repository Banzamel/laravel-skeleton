<?php

namespace App\Http\Controllers\FileManager;

use FileManager\Services\Interfaces\FileManagerServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

readonly class DownloadFileController
{
    /**
     * @param FileManagerServiceInterface $fileManager
     */
    public function __construct(private FileManagerServiceInterface $fileManager)
    {
    }

    /**
     * Download a file from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $pathId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function __invoke(Request $request, int $pathId): StreamedResponse
    {
        return $this->fileManager->downloadFile($pathId);
    }
}
