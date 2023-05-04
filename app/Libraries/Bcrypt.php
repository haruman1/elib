<?php

namespace App\Libraries;

class Bcrypt
{
    public static function hash($value)
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    public static function verify($value, $hashedValue)
    {
        return password_verify($value, $hashedValue);
    }
}
