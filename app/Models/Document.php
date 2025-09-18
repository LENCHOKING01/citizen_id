<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 'document_type', 'file_path',
        'original_name', 'mime_type', 'size'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}