<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biometric extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 'photo_path', 'fingerprint_data', 'signature_path'
    ];

    protected $casts = [
        'fingerprint_data' => 'encrypted',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}