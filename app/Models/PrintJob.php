<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 'printing_officer_id', 'status', 'error_message', 'printed_at'
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function printingOfficer()
    {
        return $this->belongsTo(User::class, 'printing_officer_id');
    }
}