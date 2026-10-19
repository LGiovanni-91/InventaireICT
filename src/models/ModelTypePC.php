<?php
require_once __DIR__ . '/../../config/database.php';

class ModelTypePC
{
    private static $table = 'mgt_modeltypepc';

    // Récupère tous les modèles actifs
    public static function getAll()
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("
            SELECT id_pk, model, type
            FROM " . self::$table . "
            WHERE deactivation_date IS NULL
            ORDER BY type, model
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un modèle par son ID
    public static function getById($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT * 
            FROM " . self::$table . " 
            WHERE id_pk = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllsearch()
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("
            SELECT DISTINCT type
            FROM " . self::$table . "
            WHERE deactivation_date IS NULL
            ORDER BY type, model
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
