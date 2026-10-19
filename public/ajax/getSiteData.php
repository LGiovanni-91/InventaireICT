<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$db = Database::getConnection();
$siteId = $_GET['site_id'] ?? null;

$unites = [];
$services = [];

if ($siteId) {

    // UNITÉS liées au site via t_material
    $stmt = $db->prepare("
        SELECT DISTINCT u.code_pk, u.direction
        FROM t_material m
        JOIN adm_unite u ON m.unite_fk = u.code_pk
        WHERE m.site_fk = :site
        AND m.deactivation_date IS NULL
    ");
    $stmt->execute(['site' => $siteId]);
    $unites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // SERVICES liés au site via t_material
    $stmt = $db->prepare("
        SELECT DISTINCT s.id_pk, s.name
        FROM t_material m
        JOIN adm_service s ON m.service_fk = s.id_pk
        WHERE m.site_fk = :site
        AND m.deactivation_date IS NULL
    ");
    $stmt->execute(['site' => $siteId]);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode([
    'unites' => $unites,
    'services' => $services
]);
