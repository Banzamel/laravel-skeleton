<?php

namespace FileManager\Services\Interfaces;

use FileManager\Models\FileManagerPath;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileManagerServiceInterface
{
    /**
     * List directory contents for the company.
     *
     * @param int|null $parentId
     * @return \Illuminate\Support\Collection
     */
    public function listDirectory(?int $parentId = null): Collection;

    /**
     * Get a single file or directory with relations.
     *
     * @param int $pathId
     * @return \FileManager\Models\FileManagerPath
     */
    public function getItem(int $pathId): FileManagerPath;

    /**
     * Create a new directory on storage and in database.
     *
     * @param string $name
     * @param int|null $parentId
     * @param string|null $storage
     * @return \FileManager\Models\FileManagerPath
     */
    public function createDirectory(string $name, ?int $parentId = null, ?string $storage = null): FileManagerPath;

    /**
     * Upload a file to storage and create path with meta record.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int|null $parentId
     * @param string|null $storage
     * @return \FileManager\Models\FileManagerPath
     */
    public function uploadFile(UploadedFile $file, ?int $parentId = null, ?string $storage = null): FileManagerPath;

    /**
     * Update file or directory properties (rename, move).
     *
     * @param int $pathId
     * @param array $data
     * @return \FileManager\Models\FileManagerPath
     */
    public function updateItem(int $pathId, array $data): FileManagerPath;

    /**
     * Delete a file or directory from storage and database.
     *
     * @param int $pathId
     * @return void
     */
    public function deleteItem(int $pathId): void;

    /**
     * Download a file from storage as streamed response.
     *
     * @param int $pathId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadFile(int $pathId): StreamedResponse;
}
