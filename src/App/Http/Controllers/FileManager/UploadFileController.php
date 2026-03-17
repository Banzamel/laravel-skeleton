<?php

namespace App\Http\Controllers\FileManager;

use App\Http\Requests\FileManager\CreatePathRequest;
use FileManager\Resources\FileManagerPathResource;
use FileManager\Services\Interfaces\FileManagerServiceInterface;
use Illuminate\Http\JsonResponse;

readonly class UploadFileController
{
    /**
     * @param FileManagerServiceInterface $fileManager
     */
    public function __construct(private FileManagerServiceInterface $fileManager)
    {
    }

    /**
     * Upload a file to storage and create a path with metadata.
     *
     * @param \App\Http\Requests\FileManager\CreatePathRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreatePathRequest $request): JsonResponse
    {
        $filePath = $this->fileManager->uploadFile(
            $request->file('file'),
            $request->input('parent_id') ? (int) $request->input('parent_id') : null,
            $request->input('storage'),
        );

        return response()->json(new FileManagerPathResource($filePath), 201);
    }
}
