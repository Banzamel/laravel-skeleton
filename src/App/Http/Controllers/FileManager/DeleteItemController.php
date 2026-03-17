<?php

namespace App\Http\Controllers\FileManager;

use FileManager\Services\Interfaces\FileManagerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

readonly class DeleteItemController
{
    /**
     * @param FileManagerServiceInterface $fileManager
     */
    public function __construct(private FileManagerServiceInterface $fileManager)
    {
    }

    /**
     * Delete a file or directory from storage and database.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $pathId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, int $pathId): JsonResponse
    {
        $this->fileManager->deleteItem($pathId);

        return response()->json(null, 204);
    }
}
