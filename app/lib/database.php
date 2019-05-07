<?php
namespace App\Lib;

use Exception;
use PDO;

class Database
{
    public static function StartUp()
    {
        require_once './Config.php';

        try {
            $pdo = new PDO('mysql:host=' . DB_SERVERNAME . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET . '', DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}