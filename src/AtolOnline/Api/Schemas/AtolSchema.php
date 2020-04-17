<?php

namespace AtolOnline\Api;

abstract class AtolSchema
{
    /**
     * @return mixed
     */
    public static function get()
    {
        return static::$json
            ?? static::$json = json_decode(file_get_contents(static::$URL));
    }
    
    /**
     * @return false|string
     */
    public static function json()
    {
        return json_encode(static::get());
    }
}