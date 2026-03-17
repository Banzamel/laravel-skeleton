<?php

namespace FileManager\Models;

use FileManager\Factories\FileManagerLinkFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shared\Traits\GenealogyTrait;

class FileManagerLink extends Model
{
    use HasFactory, GenealogyTrait;

    protected $table = 'mgr_file_links';

    protected $fillable = [
        'path_id',
        'target_type',
        'target_id',
        'url',
    ];

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        Factory::guessFactoryNamesUsing(fn() => FileManagerLinkFactory::class);
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

    /**
     * Polymorphic target of this link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
