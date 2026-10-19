<?php
require_once __DIR__ . '/../../config/database.php';

class Service
{
    private static $table = 'adm_service';

    // Récupère tous les services actifs
    public static function getAll()
    {
        $db = Database::getConnection();

        $sql = "
            SELECT 
                s.id_pk,
                s.name,
                u.code_pk
            FROM adm_service s
            LEFT JOIN adm_unite u ON s.unite_fk = u.code_pk
            WHERE 1=1
        ";

        $params = [];

        // 🔐 Restriction unité sauf admin
        if ($_SESSION['user']['right_fk'] != 1) {
            $sql .= " AND s.unite_fk = :unite";
            $params[':unite'] = $_SESSION['user']['unite_fk'];
        }

        $sql .= " ORDER BY s.name";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un service par son ID
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
