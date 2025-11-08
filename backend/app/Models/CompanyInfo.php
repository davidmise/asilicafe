<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    public static function getValue($key, $default = null)
    {
        $info = static::where('key', $key)->first();
        return $info ? $info->value : $default;
    }

    public static function setValue($key, $value, $type = 'text', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );
    }
}
