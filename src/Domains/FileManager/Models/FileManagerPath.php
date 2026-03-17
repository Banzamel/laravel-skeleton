<?php

namespace FileManager\Models;

use FileManager\Enums\EntityTypeEnum;
use FileManager\Enums\StoragesEnum;
use FileManager\Factories\FileManagerPathFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shared\Traits\BelongsToCompany;
use Shared\Traits\GenealogyTrait;

class FileManagerPath extends Model
{
    use HasFactory, GenealogyTrait, BelongsToCompany;

    protected $table = 'mgr_file_paths';

    protected $fillable = [
        'company_id',
        'hash',
        'parent_id',
        'owner_type',
        'owner_id',
        'type',
        'storage',
        'name',
        'path',
        'size',
    ];

    /**
     * @return array
     */
    protected function casts(): array
    {
        return [
            'type' => EntityTypeEnum::class,
            'storage' => StoragesEnum::class,
            'size' => 'integer',
        ];
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        Factory::guessFactoryNamesUsing(fn() => FileManagerPathFactory::class);
    }

    /**
     * Parent directory.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Child files and directories.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * File metadata (mime type, extension, checksum).
     *
     * @return HasOne
     */
    public function meta(): HasOne
    {
        return $this->hasOne(FileManagerMeta::class, 'path_id');
    }

    /**
     * Shared links for this file.
     *
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(FileManagerLink::class, 'path_id');
    }

    /**
     * Polymorphic owner of this path.
     *
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if this path is a directory.
     *
     * @return bool
     */
    public function isDirectory(): bool
    {
        return $this->type === EntityTypeEnum::dir;
    }

    /**
     * Check if this path is a file.
     *
     * @return bool
     */
    public function isFile(): bool
    {
        return $this->type === EntityTypeEnum::file;
    }
}
