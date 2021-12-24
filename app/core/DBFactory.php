<?php

namespace core;

use React\MySQL\ConnectionInterface;
use core\Env;

class DBFactory
{
    public static $db;

    public static function getConnection()
    {
        if (!self::$db) {
            try {
                self::$db = (new \React\MySQL\Factory())->createLazyConnection(Env::get('DB_CREDENTIALS'));
            } catch (\Exception $err) {
                throw new \BadMethodCallException('DB connection error');
            }
        }

        return self::$db;
    }
}