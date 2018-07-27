<?php

namespace App\Property;

class SettingProperty
{
    private static $setting = array();

    public static function set($name, $value)
    {
        self::$setting[$name] = $value;
    }

    public static function get($name)
    {
        if (isset(self::$setting[$name])) {
            return self::$setting[$name];
        } else {
            return null;
        }
    }
}
