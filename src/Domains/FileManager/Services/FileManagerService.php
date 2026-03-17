<?php

namespace FileManager\Services;

use FileManager\Enums\EntityTypeEnum;
use FileManager\Enums\StoragesEnum;
use FileManager\Events\FileUploadEvent;
use FileManager\Models\FileManagerPath;
use FileManager\Repositories\FileManagerRepositoryInterface;
use FileManager\Services\Interfaces\FileManagerServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class FileManagerService implements FileManagerServiceInterface
{
    public function __construct(
        private FileManagerRepositoryInterface $fileManagerRepository,
    ) {}

    /**
     * List directory contents for the company.
     *
     * @param int|null $parentId
     * @return \Illuminate\Support\Collection
     */
    public function listDirectory(?int $parentId = null): Collection
    {
        return $this->fileManagerRepository->listDirectory($parentId);
    }

    /**
     * Get a single file or directory with relations.
     *
     * @param int $pathId
     * @return \FileManager\Models\FileManagerPath
     */
    public function getItem(int $pathId): FileManagerPath
    {
        return $this->fileManagerRepository->findOrFail($pathId, ['meta', 'children', 'links']);
    }

    /**
     * Create a new directory on storage and in database.
     *
     * @param string $name
     * @param int|null $parentId
     * @param string|null $storage
     * @return \FileManager\Models\FileManagerPath
     */
    public function createDirectory(string $name, ?int $parentId = null, ?string $storage = null): FileManagerPath
    {
        $companyId = auth()->user()->company_id;
        $storageDisk = StoragesEnum::tryFrom($storage ?? 'local') ?? StoragesEnum::local;
        $parentPath = $this->resolveParentPath($parentId);
        $directoryPath = $parentPath . '/' . Str::slug($name);

        Storage::disk($storageDisk->value)->makeDirectory($directoryPath);

        return $this->fileManagerRepository->createPath([
            'company_id' => $companyId,
            'hash' => Str::random(31),
            'parent_id' => $parentId,
            'type' => EntityTypeEnum::dir->value,
            'storage' => $storageDisk->value,
            'name' => $name,
            'path' => $directoryPath,
            'size' => 0,
        ]);
    }

    /**
     * Upload a file to storage and create path with meta record.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int|null $parentId
     * @param string|null $storage
     * @return \FileManager\Models\FileManagerPath
     */
    public function uploadFile(UploadedFile $file, ?int $parentId = null, ?string $storage = null): FileManagerPath
    {
        $companyId = auth()->user()->company_id;
        $storageDisk = StoragesEnum::tryFrom($storage ?? 'local') ?? StoragesEnum::local;
        $parentPath = $this->resolveParentPath($parentId);

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $hash = Str::random(31);
        $storedName = $hash . '.' . $extension;

        $storedPath = $file->storeAs($parentPath, $storedName, $storageDisk->value);

        $filePath = $this->fileManagerRepository->createPath([
            'company_id' => $companyId,
            'hash' => $hash,
            'parent_id' => $parentId,
            'type' => EntityTypeEnum::file->value,
            'storage' => $storageDisk->value,
            'name' => $originalName,
            'path' => $storedPath,
            'size' => $file->getSize(),
        ]);

        $this->fileManagerRepository->createMeta([
            'path_id' => $filePath->id,
            'hash' => $hash,
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'checksum' => md5_file($file->getRealPath()),
        ]);

        event(new FileUploadEvent(auth()->user(), $filePath));

        return $filePath->load('meta');
    }

    /**
     * Update file or directory properties (rename, move).
     *
     * @param int $pathId
     * @param array $data
     * @return \FileManager\Models\FileManagerPath
     */
    public function updateItem(int $pathId, array $data): FileManagerPath
    {
        $item = $this->fileManagerRepository->findOrFail($pathId);

        $updateData = [];
        if (isset($data['name']) && $data['name'] !== $item->name) {
            $updateData['name'] = $data['name'];
        }
        if (isset($data['parent_id'])) {
            $updateData['parent_id'] = $data['parent_id'];
        }

        if (!empty($updateData)) {
            $item = $this->fileManagerRepository->updatePath($item, $updateData);
        }

        return $item->load('meta');
    }

    /**
     * Delete a file or directory from storage and database.
     *
     * @param int $pathId
     * @return void
     */
    public function deleteItem(int $pathId): void
    {
        $item = $this->fileManagerRepository->findOrFail($pathId);

        if ($item->isFile()) {
            Storage::disk($item->storage->value)->delete($item->path);
        } elseif ($item->isDirectory()) {
            Storage::disk($item->storage->value)->deleteDirectory($item->path);
        }

        $this->fileManagerRepository->delete($item);
    }

    /**
     * Download a file from storage as streamed response.
     *
     * @param int $pathId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadFile(int $pathId): StreamedResponse
    {
        $item = $this->fileManagerRepository->findOrFail($pathId, ['meta']);

        if ($item->isDirectory()) {
            throw new NotFoundHttpException('Cannot download a directory.');
        }

        $disk = Storage::disk($item->storage->value);

        if (!$disk->exists($item->path)) {
            throw new NotFoundHttpException('File not found on storage.');
        }

        return $disk->download($item->path, $item->name);
    }

    /**
     * Resolve parent path for file storage.
     *
     * @param int|null $parentId
     * @return string
     */
    private function resolveParentPath(?int $parentId): string
    {
        $companyId = auth()->user()->company_id;
        $basePath = 'companies/' . $companyId;

        if ($parentId) {
            $parent = $this->fileManagerRepository->findOrFail($parentId);
            return $parent->path;
        }

        return $basePath;
    }
}
