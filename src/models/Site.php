<?php
require_once __DIR__ . '/../../config/database.php';

class Site
{
    private static $table = 'adm_site';

    // Récupère tous les sites actifs
    public static function getAll()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT * 
            FROM " . self::$table . " 
            WHERE deactivation_date IS NULL
            ORDER BY name
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un site par son ID
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
}
