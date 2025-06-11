<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get Settings
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set Settings
     * @param $key
     * @param $value
     * @return void
     */
    public static function set($key, $value): void
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            self::create(['key' => $key, 'value' => $value]);
        }
    }
}
