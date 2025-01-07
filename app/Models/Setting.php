<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'value'];

    /**
     * Ottieni il valore di un'impostazione.
     */
    public static function getValue($name, $default = null)
    {
        $setting = self::where('name', $name)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Aggiorna il valore di un'impostazione.
     */
    public static function setValue($name, $value)
    {
        return self::updateOrCreate(['name' => $name], ['value' => $value]);
    }
}
