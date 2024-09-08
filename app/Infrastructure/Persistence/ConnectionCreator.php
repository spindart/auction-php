<?php

namespace Auction\Infrastructure\Persistence;

class ConnectionCreator
{
    private static $pdo = null;

    public static function getConnection(): \PDO
    {
        if (is_null(self::$pdo)) {
            // Carregar configurações
            $config = require __DIR__ . '/../../../config/database.php';

            $driver = $config['db']['driver'];

            if ($driver === 'sqlite') {
                $pathDatabase = $config['db']['sqlite']['database'];
                self::$pdo = new \PDO('sqlite:' . $pathDatabase);
            } elseif ($driver === 'mysql') {
                $host = $config['db']['mysql']['host'];
                $dbname = $config['db']['mysql']['dbname'];
                $user = $config['db']['mysql']['user'];
                $password = $config['db']['mysql']['password'];
                $charset = $config['db']['mysql']['charset'];
                $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
                self::$pdo = new \PDO($dsn, $user, $password);
            }
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$pdo;
    }
}
