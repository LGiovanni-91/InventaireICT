<?php

require_once __DIR__ . '/../../config/database.php';

class Material
{
    //Recherche pour infoUser
    public static function getMachinesByUserName($userFullName)
    {
        $db = Database::getConnection();

        $sql = "
            SELECT 
                m.*,
                s.name AS status,
                mt.model AS modele,
                mt.type AS type_pc,
                si.name AS site,
                u.direction AS unite,
                se.name AS service
            FROM t_material m
            LEFT JOIN mgt_status s ON m.status_fk = s.id_pk
            LEFT JOIN mgt_modeltypepc mt ON m.modelTypeProdCode_fk = mt.id_pk
            LEFT JOIN adm_site si ON m.site_fk = si.id_pk
            LEFT JOIN adm_unite u ON m.unite_fk = u.code_pk
            LEFT JOIN adm_service se ON m.service_fk = se.id_pk
            WHERE m.user = :user
            AND s.name <> 'DISMISSED'
            ORDER BY CAST(SUBSTRING(m.identifiant, 3) AS UNSIGNED) ASC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':user' => $userFullName]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Détail de toutes les machines d’un utilisateur
    public static function getMachinesByUser($user)
    {
        $db = Database::getConnection();

        $sql = "
            SELECT 
                m.*,
                s.name AS status,
                mt.model AS modele,
                mt.type AS type_pc,
                si.name AS site,
                u.direction AS unite,
                se.name AS service
            FROM t_material m
            LEFT JOIN mgt_status s ON m.status_fk = s.id_pk
            LEFT JOIN mgt_modeltypepc mt ON m.modelTypeProdCode_fk = mt.id_pk
            LEFT JOIN adm_site si ON m.site_fk = si.id_pk
            LEFT JOIN adm_unite u ON m.unite_fk = u.code_pk
            LEFT JOIN adm_service se ON m.service_fk = se.id_pk
            WHERE m.user = :user
            AND s.name <> 'DISMISSED'
            AND mt.type IN ('LPT', 'CPU')
        ";

        $params = [
            ':user' => $user
        ];

        // 🔐 Restriction unité sauf admin (right 1)
        if ($_SESSION['user']['right_fk'] != 1) {
            $sql .= " AND m.unite_fk = :unite";
            $params[':unite'] = $_SESSION['user']['unite_fk'];
        }

        $sql .= " ORDER BY CAST(SUBSTRING(m.identifiant, 3) AS UNSIGNED) ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Affichage complet
    public static function getAll()
    {
        $db = Database::getConnection();

        $sql = "
            SELECT 
                m.*,
                s.name AS status,
                si.name AS site,
                u.direction AS unite,
                se.name AS service,
                mk.name AS marque,
                mt.model AS modele,
                mt.type AS type_pc,
                usa.name AS usage_name,
                us.matricule_pk AS user_fk,
                net.name AS network
            FROM t_material m
            LEFT JOIN mgt_status s ON m.status_fk = s.id_pk
            LEFT JOIN adm_site si ON m.site_fk = si.id_pk
            LEFT JOIN adm_unite u ON m.unite_fk = u.code_pk
            LEFT JOIN adm_service se ON m.service_fk = se.id_pk
            LEFT JOIN mgt_mark mk ON m.mark_fk = mk.id_pk
            LEFT JOIN mgt_modeltypepc mt ON m.modelTypeProdCode_fk = mt.id_pk
            LEFT JOIN mgt_usage usa ON m.usage_fk = usa.id_pk
            LEFT JOIN mgt_network net ON m.network_fk = net.id_pk
            LEFT JOIN adm_user us ON m.user_fk = us.matricule_pk
            WHERE m.deactivation_date IS NULL
        ";

        $params = [];

        // 🔐 Restriction unité sauf admin
        if ($_SESSION['user']['right_fk'] != 1) {
            $sql .= " AND m.unite_fk = :unite";
            $params[':unite'] = $_SESSION['user']['unite_fk'];
        }

        $sql .= " ORDER BY m.identifiant DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Filtrage dynamique
    public static function getFiltered($filters)
    {
        $db = Database::getConnection();

        $sql = "
            SELECT 
                m.*,
                s.name AS status,
                si.name AS site,
                u.direction AS unite,
                se.name AS service,
                mk.name AS marque,
                mt.model AS modele,
                mt.type AS type_pc,
                usa.name AS usage_name,
                us.matricule_pk AS user_fk,
                net.name AS network
            FROM t_material m
            LEFT JOIN mgt_status s ON m.status_fk = s.id_pk
            LEFT JOIN adm_site si ON m.site_fk = si.id_pk
            LEFT JOIN adm_unite u ON m.unite_fk = u.code_pk
            LEFT JOIN adm_service se ON m.service_fk = se.id_pk
            LEFT JOIN mgt_mark mk ON m.mark_fk = mk.id_pk
            LEFT JOIN mgt_modeltypepc mt ON m.modelTypeProdCode_fk = mt.id_pk
            LEFT JOIN mgt_usage usa ON m.usage_fk = usa.id_pk
            LEFT JOIN mgt_network net ON m.network_fk = net.id_pk
            LEFT JOIN adm_user us ON m.user_fk = us.matricule_pk
            WHERE m.deactivation_date IS NULL
        ";

        $params = [];

        if (!empty($filters['note'])) {
            $sql .= " AND m.note LIKE :note";
            $params[':note'] = '%' . $filters['note'] . '%';
        }

        if (!empty($filters['matricule_user'])) {
            $sql .= " AND m.matricule_user = :matricule_user";
            $params[':matricule_user'] = $filters['matricule_user'];
        }

        if (!empty($filters['pe'])) {
            $sql .= " AND m.identifiant LIKE :pe";
            $params[':pe'] = '%' . $filters['pe'] . '%';
        }

        if (!empty($filters['type'])) {
            $sql .= " AND mt.type = :type";
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['unite'])) {
            $sql .= " AND m.unite_fk LIKE :unite";
            $params[':unite'] = $filters['unite'];
        }   

        if (!empty($filters['site'])) {
            $sql .= " AND m.site_fk = :site";
            $params[':site'] = $filters['site'];
        }

        if (!empty($filters['user'])) {
            $sql .= " AND m.user LIKE :user";
            $params[':user'] = '%' . $filters['user'] . '%';
        }

        if (!empty($filters['service'])) {
            $sql .= " AND m.service_fk = :service";
            $params[':service'] = $filters['service'];
        }     

        if (!empty($filters['status'])) {
            $sql .= " AND m.status_fk = :status";
            $params[':status'] = $filters['status'];
        }     

        // PE de ... à ...
        if (!empty($filters['identifiant_from'])) {
            $sql .= " AND CAST(SUBSTRING(m.identifiant, 3) AS UNSIGNED) >= :pe_from";
            $params[':pe_from'] = (int) preg_replace('/\D/', '', $filters['identifiant_from']);
        }

        if (!empty($filters['identifiant_to'])) {
            $sql .= " AND CAST(SUBSTRING(m.identifiant, 3) AS UNSIGNED) <= :pe_to";
            $params[':pe_to'] = (int) preg_replace('/\D/', '', $filters['identifiant_to']);
        }

         // 🔐 Restriction unité sauf admin
        if ($_SESSION['user']['right_fk'] != 1) {
            $sql .= " AND m.unite_fk = :unite";
            $params[':unite'] = $_SESSION['user']['unite_fk'];
        }

        $sql .= " ORDER BY CAST(SUBSTRING(m.identifiant, 3) AS UNSIGNED) DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    // Récupérer par ID
    public static function getById($id)
    {
        $db = Database::getConnection();

        $sql = "
            SELECT 
                m.*,
                s.name AS status,
                si.name AS site,
                u.direction AS unite,
                se.name AS service,
                mk.name AS marque,
                mt.model AS modele,
                mt.type AS type_pc,
                usa.name AS usage_name,
                us.matricule_pk AS user_fk,
                net.name AS network
            FROM t_material m
            LEFT JOIN mgt_status s ON m.status_fk = s.id_pk
            LEFT JOIN adm_site si ON m.site_fk = si.id_pk
            LEFT JOIN adm_unite u ON m.unite_fk = u.code_pk
            LEFT JOIN adm_service se ON m.service_fk = se.id_pk
            LEFT JOIN mgt_mark mk ON m.mark_fk = mk.id_pk
            LEFT JOIN mgt_modeltypepc mt ON m.modelTypeProdCode_fk = mt.id_pk
            LEFT JOIN mgt_usage usa ON m.usage_fk = usa.id_pk
            LEFT JOIN mgt_network net ON m.network_fk = net.id_pk
            LEFT JOIN adm_user us ON m.user_fk = us.matricule_pk
            WHERE m.id_pk = :id
            LIMIT 1
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Modifier un matériel
    public static function update($id, $data)
    {
        $db = Database::getConnection();

        $sql = "
            UPDATE t_material SET
                identifiant = :identifiant,
                imei = :imei,
                serial_nr = :serial_nr,
                tei = :tei,
                local = :local,
                status_fk = :status_fk,
                user = :user,
                site_fk = :site_fk,
                mark_fk = :mark_fk,
                hasFeature = :hasFeature,
                unite_fk = :unite_fk,
                matricule_user = :matricule_user,
                service_fk = :service_fk,
                modelTypeProdCode_fk = :modelTypeProdCode_fk,
                network_fk = :network_fk,
                usage_fk = :usage_fk,
                note = :note,
                user_fk = :user_fk,          
                update_date = NOW()
            WHERE id_pk = :id
        ";

        $stmt = $db->prepare($sql);
        $data['id'] = $id;

        return $stmt->execute($data);
    }
    
    // Ajouter un nouveau matériel
    public static function create($data)
    {
        $db = Database::getConnection();

        $sql = "
            INSERT INTO t_material (
                identifiant, imei, serial_nr, tei, local, status_fk, user, site_fk,
                hasFeature, unite_fk, service_fk, mark_fk, matricule_user, modelTypeProdCode_fk,
                network_fk, usage_fk, note, user_fk, creation_date
            ) VALUES (
                :identifiant, :imei, :serial_nr, :tei, :local, :status_fk, :user,
                :site_fk, :hasFeature, :unite_fk, :service_fk, :mark_fk,
                :matricule_user, :modelTypeProdCode_fk, :network_fk, :usage_fk,
                :note, :user_fk, NOW()
            )
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute($data);
    }

    //Vérifier les doublons dans l'ajout
    public static function VerifDoublons($identifiant, $imei, $serial_nr, $tei) {
        $db = Database::getConnection();
        $errors = [];

        // Identifiant obligatoire → toujours vérifier
        if (!empty($identifiant)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM t_material WHERE identifiant = ?");
            $stmt->execute([$identifiant]);
            if ($stmt->fetchColumn() > 0) {
                $errors['identifiant'] = "Erreur : cet identifiant existe déjà !";
            }
        }

        // IMEI → vérifier seulement si rempli
        if (!empty($imei)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM t_material WHERE imei = ?");
            $stmt->execute([$imei]);
            if ($stmt->fetchColumn() > 0) {
                $errors['imei'] = "Erreur : cet IMEI existe déjà !";
            }
        }

        // Serial Nr → vérifier seulement si rempli
        if (!empty($serial_nr)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM t_material WHERE serial_nr = ?");
            $stmt->execute([$serial_nr]);
            if ($stmt->fetchColumn() > 0) {
                $errors['serial_nr'] = "Erreur : ce Serial Nr existe déjà !";
            }
        }

        // TEI → vérifier seulement si rempli
        if (!empty($tei)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM t_material WHERE tei = ?");
            $stmt->execute([$tei]);
            if ($stmt->fetchColumn() > 0) {
                $errors['tei'] = "Erreur : ce TEI existe déjà !";
            }
        }
        return $errors; // tableau vide si tout est OK
    }

    // Vérifier les doublons pour la modification (ignore l'enregistrement actuel)
    public static function VerifDoublonsUpdate($id, $identifiant, $imei, $serial_nr, $tei) {
        $db = Database::getConnection();
        $errors = [];

        // Identifiant → vérifier seulement si rempli
        if (!empty($identifiant)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM t_material WHERE identifiant = ? AND id_pk != ?");
            $stmt->execute([$identifiant, $id]);
            if ($stmt->fetchColumn() > 0) {
                $errors['identifiant'] = "Erreur : cet identifiant existe déjà !";
            }
        }

        // IMEI → vérifier seulement si rempli
        if (!empty($imei)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM t_material WHERE imei = ? AND id_pk != ?");
            $stmt->execute([$imei, $id]);
            if ($stmt->fetchColumn() > 0) {
                $errors['imei'] = "Erreur : cet IMEI existe déjà !";
            }
        }

        // Serial Nr → vérifier seulement si rempli
        if (!empty($serial_nr)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM t_material WHERE serial_nr = ? AND id_pk != ?");
            $stmt->execute([$serial_nr, $id]);
            if ($stmt->fetchColumn() > 0) {
                $errors['serial_nr'] = "Erreur : ce Serial Nr existe déjà !";
            }
        }

        // TEI → vérifier seulement si rempli
        if (!empty($tei)) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM t_material WHERE tei = ? AND id_pk != ?");
            $stmt->execute([$tei, $id]);
            if ($stmt->fetchColumn() > 0) {
                $errors['tei'] = "Erreur : ce TEI existe déjà !";
            }
        }
        return $errors; // tableau vide si tout est OK
    }

    //recherche des machines utilisateurs sauf les DISMISSED
    public static function getUsersWithMultipleMachines($searchUser = null)
    {
        $db = Database::getConnection();

        $sql = "
            SELECT
                m.user,
                mt.type AS type_pc,
                COUNT(m.id_pk) AS nb_machines,
                si.name AS site,
                u.direction AS unite
            FROM t_material m
            LEFT JOIN adm_site si ON m.site_fk = si.id_pk
            LEFT JOIN adm_unite u ON m.unite_fk = u.code_pk
            LEFT JOIN mgt_status s ON m.status_fk = s.id_pk
            LEFT JOIN mgt_modeltypepc mt ON m.modelTypeProdCode_fk = mt.id_pk
            WHERE m.deactivation_date IS NULL
            AND m.user IS NOT NULL
            AND mt.type IN ('LPT', 'CPU')
            AND m.user <> ''
            AND s.name <> 'DISMISSED'
        ";

        $params = [];
        if ($searchUser) {
            $sql .= " AND m.user LIKE :user";
            $params[':user'] = '%' . $searchUser . '%';
        }

        // 🔐 Restriction unité sauf admin
        if ($_SESSION['user']['right_fk'] != 1) {
            $sql .= " AND m.unite_fk = :unite";
            $params[':unite'] = $_SESSION['user']['unite_fk'];
        }

        $sql .= "
            GROUP BY m.user, si.name, u.direction
            HAVING COUNT(m.id_pk) > 1
            ORDER BY m.user ASC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
