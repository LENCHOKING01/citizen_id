<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrintJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_id',
        'application_id',
        'printing_officer_id',
        'status',
        'attempts',
        'error_message',
        'printed_at'
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function printingOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'printing_officer_id');
    }
}