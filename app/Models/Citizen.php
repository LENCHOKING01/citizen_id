<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Citizen extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_number', 'first_name', 'last_name', 'middle_name',
        'date_of_birth', 'gender', 'nationality', 'address', 'phone', 'email',
        'photo_path', 'qr_code_path'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($citizen) {
            if (empty($citizen->id_number)) {
                $citizen->id_number = self::generateCitizenId();
            }
        });
    }

    public static function generateCitizenId()
    {
        $year = date('Y');
        $lastCitizen = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastCitizen ? 
            (int) substr($lastCitizen->id_number, -6) + 1 : 1;
        
        return $year . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function latestApplication()
    {
        return $this->hasOne(Application::class)->latestOfMany();
    }

    public function currentApplication()
    {
        return $this->hasOne(Application::class)->whereIn('status', ['draft', 'pending', 'approved'])->latest();
    }
}