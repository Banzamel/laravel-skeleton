<?php

namespace FileManager\Repositories;

use FileManager\Models\FileManagerPath;
use FileManager\Models\FileManagerMeta;
use Illuminate\Database\Eloquent\Collection;

interface FileManagerRepositoryInterface
{
    public function listDirectory(?int $parentId = null): Collection;
    public function findOrFail(int $pathId, array $with = []): FileManagerPath;
    public function createPath(array $data): FileManagerPath;
    public function createMeta(array $data): FileManagerMeta;
    public function updatePath(FileManagerPath $path, array $data): FileManagerPath;
    public function delete(FileManagerPath $path): void;
}
