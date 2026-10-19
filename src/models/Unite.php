<?php
require_once __DIR__ . '/../../config/database.php';

class Unite
{
    private static $table = 'adm_unite';

    // Récupère toutes les unités actives
    public static function getAll()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT * 
            FROM " . self::$table . " 
            WHERE deactivation_date IS NULL
            ORDER BY direction
        ");

        // 👑 Admin ICT
        if ($_SESSION['user']['right_fk'] == 1) {
            $stmt = $db->prepare("
                SELECT *
                FROM adm_unite
                ORDER BY direction
            ");
            $stmt->execute();
        } 
        // 👤 Autres utilisateurs
        else {
            $stmt = $db->prepare("
                SELECT *
                FROM adm_unite
                WHERE code_pk = :unite
                ORDER BY direction
            ");
            $stmt->execute([
                ':unite' => $_SESSION['user']['unite_fk']
            ]);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère une unité par son code
    public static function getById($code)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT * 
            FROM " . self::$table . " 
            WHERE code_pk = :code
            LIMIT 1
        ");
        $stmt->execute([':code' => $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
