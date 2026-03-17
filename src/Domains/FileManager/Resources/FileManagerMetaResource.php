<?php

namespace FileManager\Resources;

use FileManager\Models\FileManagerMeta;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin FileManagerMeta */
class FileManagerMetaResource extends JsonResource
{
    /**
     * Transform the file meta into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'metadata' => $this->metadata,
            'checksum' => $this->checksum,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
