<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_id', 'registrar_id', 'supervisor_id', 'status',
        'rejection_reason', 'submitted_at', 'approved_at', 'printed_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'printed_at' => 'datetime',
    ];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function registrar()
    {
        return $this->belongsTo(User::class, 'registrar_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function biometrics()
    {
        return $this->hasOne(Biometric::class);
    }

    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    public function latestPrintJob()
    {
        return $this->hasOne(PrintJob::class)->latestOfMany();
    }
}