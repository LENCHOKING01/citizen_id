<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'description'];

    public static function getValue($key, $default = null)
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    public static function setValue($key, $value, $description = null)
    {
        $config = self::firstOrNew(['key' => $key]);
        $config->value = $value;
        if ($description) {
            $config->description = $description;
        }
        $config->save();
        return $config;
    }
}