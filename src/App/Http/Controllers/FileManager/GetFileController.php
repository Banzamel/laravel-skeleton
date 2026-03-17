<?php

namespace App\Http\Controllers\FileManager;

use FileManager\Resources\FileManagerPathResource;
use FileManager\Services\Interfaces\FileManagerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

readonly class GetFileController
{
    /**
     * @param FileManagerServiceInterface $fileManager
     */
    public function __construct(private FileManagerServiceInterface $fileManager)
    {
    }

    /**
     * Display directory contents or item details.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|null $pathId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, ?int $pathId = null): JsonResponse
    {
        if ($pathId) {
            $item = $this->fileManager->getItem($pathId);
            return response()->json(new FileManagerPathResource($item));
        }

        $parentId = $request->query('parent_id') ? (int) $request->query('parent_id') : null;
        $items = $this->fileManager->listDirectory($parentId);

        return response()->json(FileManagerPathResource::collection($items));
    }
}
