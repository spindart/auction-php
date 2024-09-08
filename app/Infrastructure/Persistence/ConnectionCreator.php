<?php

namespace Auction\Infrastructure\Persistence;

class ConnectionCreator
{
    private static $pdo = null;

    public static function getConnection(): \PDO
    {
        if (is_null(self::$pdo)) {
            $pathDatabase = __DIR__ . '/../../database.sqlite';
            self::$pdo = new \PDO('sqlite:' . $pathDatabase);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$pdo;
    }
}
