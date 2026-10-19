<?php
session_start();
require_once __DIR__ . '/../../src/models/Material.php';

$source = $_GET['source'] ?? 'inventaire';

if ($source === 'machineUser' && !empty($_GET['user'])) {
    $data = Material::getMachinesByUser($_GET['user']);
} else {
    $filters = $_POST ?: $_GET;
    $data = array_filter($filters)
        ? Material::getFiltered($filters)
        : Material::getAll();
}

// Headers Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=inventaire_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo '<table border="1">';
echo '
<tr>
    <th>ID</th>
    <th>PE</th>
    <th>imei</th>
    <th>serial</th>
    <th>tei</th>
    <th>Status</th>
    <th>Type</th>
    <th>Modele</th>
    <th>Marque</th>  
    <th>Unite</th>
    <th>Site</th>
    <th>Service</th>
    <th>Local</th>
    <th>User</th>
    <th>matricule_user</th>
    <th>network</th>
    <th>hasFeature</th>
    <th>usage</th>
    <th>Note</th>
</tr>';

foreach ($data as $m) {
    echo '<tr>
        <td>'.$m['id_pk'].'</td>
        <td>'.$m['identifiant'].'</td>
        <td>'.$m['imei'].'</td>
        <td>'.$m['serial_nr'].'</td>
        <td>'.$m['tei'].'</td>
        <td>'.$m['status'].'</td>
        <td>'.$m['type_pc'].'</td>
        <td>'.$m['modele'].'</td>
        <td>'.$m['marque'].'</td>       
        <td>'.$m['unite'].'</td>
        <td>'.$m['site'].'</td>
        <td>'.$m['service'].'</td>
        <td>'.$m['local'].'</td>
        <td>'.$m['user'].'</td>
        <td>'.$m['matricule_user'].'</td>
        <td>'.$m['network'].'</td>
        <td>'.$m['hasFeature'].'</td>
        <td>'.$m['usage_name'].'</td>
        <td>'.$m['note'].'</td>
    </tr>';
}

echo '</table>';
exit;
