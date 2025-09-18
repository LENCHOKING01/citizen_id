<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Biometric extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_id',
        'application_id',
        'fingerprint_data',
        'facial_recognition_data',
        'iris_scan_data',
        'signature_path',
        'captured_by'
    ];

    protected $casts = [
        'fingerprint_data' => 'encrypted',
        'facial_recognition_data' => 'encrypted',
        'iris_scan_data' => 'encrypted',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    public function capturedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captured_by');
    }
}