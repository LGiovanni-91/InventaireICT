<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
    // Récupérer tous les utilisateurs
    public static function getAll()
    {
        $db = Database::getConnection();

        $sql = "SELECT matricule_pk, name, first_name FROM adm_user ORDER BY name ASC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un utilisateur par matricule
    public static function getById($id)
    {
        $db = Database::getConnection();

        $sql = "SELECT matricule_pk, name, first_name FROM adm_user WHERE matricule_pk = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //pour se connecter
    public static function findByMatricule($matricule)
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM adm_user
            WHERE matricule_pk = :matricule
            LIMIT 1
        ");
        $stmt->execute([':matricule' => $matricule]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
