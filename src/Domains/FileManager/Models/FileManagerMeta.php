<?php

namespace FileManager\Models;

use FileManager\Factories\FileManagerMetaFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shared\Traits\GenealogyTrait;

class FileManagerMeta extends Model
{
    use HasFactory, GenealogyTrait;

    protected $table = 'mgr_file_metas';

    protected $fillable = [
        'path_id',
        'hash',
        'mime_type',
        'extension',
        'metadata',
        'checksum',
    ];

    /**
     * @return array
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        Factory::guessFactoryNamesUsing(fn() => FileManagerMetaFactory::class);
    }

    /**
     * Parent file path record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function path(): BelongsTo
    {
        return $this->belongsTo(FileManagerPath::class, 'path_id');
    }
}
