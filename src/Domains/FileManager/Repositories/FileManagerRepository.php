<?php

namespace FileManager\Repositories;

use FileManager\Enums\EntityTypeEnum;
use FileManager\Models\FileManagerPath;
use FileManager\Models\FileManagerMeta;
use Illuminate\Database\Eloquent\Collection;

class FileManagerRepository implements FileManagerRepositoryInterface
{
    public function listDirectory(?int $parentId = null): Collection
    {
        return FileManagerPath::where('parent_id', $parentId)
            ->with('meta')
            ->orderByRaw("FIELD(type, ?, ?) ASC", [EntityTypeEnum::dir->value, EntityTypeEnum::file->value])
            ->orderBy('name')
            ->get();
    }

    public function findOrFail(int $pathId, array $with = []): FileManagerPath
    {
        return FileManagerPath::query()
            ->when(!empty($with), fn($q) => $q->with($with))
            ->findOrFail($pathId);
    }

    public function createPath(array $data): FileManagerPath
    {
        return FileManagerPath::create($data);
    }

    public function createMeta(array $data): FileManagerMeta
    {
        return FileManagerMeta::create($data);
    }

    public function updatePath(FileManagerPath $path, array $data): FileManagerPath
    {
        $path->update($data);
        return $path->fresh();
    }

    public function delete(FileManagerPath $path): void
    {
        $path->delete();
    }
}
