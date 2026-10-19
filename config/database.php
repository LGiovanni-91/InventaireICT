<?php

class Database
{
    private static $instance = null;

    //Méthode de connexion
    public static function getConnection()
    {
        if (self::$instance === null) {
            $host = '16.61.3.190';
            $db   = 'Student_Test_InventaireICT';
            $user = 'TestUser';
            $pass = 'M@riaDB_01';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            try {
                self::$instance = new PDO($dsn, $user, $pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Erreur DB : ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}