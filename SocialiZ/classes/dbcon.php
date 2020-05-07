<?php


class dbcon
{
    private static $_instance;
    public static function pdo_connection($dnsh, $user, $pass)
    {
        if (!self::$_instance)
        {
            self::$_instance = new PDO($dnsh, $user, $pass);
            self::$_instance->setAttribute(PDO::ATTR_PERSISTENT,true);
            self::$_instance->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        return self::$_instance;
    }
    private function __construct(){}
    private function __clone(){}
}