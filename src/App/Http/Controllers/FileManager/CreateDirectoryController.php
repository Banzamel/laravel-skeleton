<?php

namespace App\Http\Controllers\FileManager;

use FileManager\Resources\FileManagerPathResource;
use FileManager\Services\Interfaces\FileManagerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

readonly class CreateDirectoryController
{
    /**
     * @param FileManagerServiceInterface $fileManager
     */
    public function __construct(private FileManagerServiceInterface $fileManager)
    {
    }

    /**
     * Create a new directory in the file manager.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:mgr_file_paths,id'],
            'storage' => ['nullable', 'string'],
        ]);

        $directory = $this->fileManager->createDirectory(
            $request->input('name'),
            $request->input('parent_id'),
            $request->input('storage'),
        );

        return response()->json(new FileManagerPathResource($directory), 201);
    }
}
