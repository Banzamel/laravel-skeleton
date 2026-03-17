<?php

namespace FileManager\Resources;

use FileManager\Models\FileManagerPath;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin FileManagerPath */
class FileManagerPathResource extends JsonResource
{
    /**
     * Transform the file path into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hash' => $this->hash,
            'parent_id' => $this->parent_id,
            'type' => $this->type->value,
            'storage' => $this->storage->value,
            'name' => $this->name,
            'size' => $this->size,
            'meta' => new FileManagerMetaResource($this->whenLoaded('meta')),
            'children' => FileManagerPathResource::collection($this->whenLoaded('children')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
