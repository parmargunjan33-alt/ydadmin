<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    public static function getValue(string $key, $default = null)
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : $default;
    }
}