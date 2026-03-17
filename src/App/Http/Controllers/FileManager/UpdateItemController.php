<?php

namespace App\Http\Controllers\FileManager;

use App\Http\Requests\FileManager\UpdatePathRequest;
use FileManager\Resources\FileManagerPathResource;
use FileManager\Services\Interfaces\FileManagerServiceInterface;
use Illuminate\Http\JsonResponse;

readonly class UpdateItemController
{
    /**
     * @param FileManagerServiceInterface $fileManager
     */
    public function __construct(private FileManagerServiceInterface $fileManager)
    {
    }

    /**
     * Update a file or directory (rename, move).
     *
     * @param \App\Http\Requests\FileManager\UpdatePathRequest $request
     * @param int $pathId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdatePathRequest $request, int $pathId): JsonResponse
    {
        $item = $this->fileManager->updateItem(
            $pathId,
            $request->validated(),
        );

        return response()->json(new FileManagerPathResource($item));
    }
}
