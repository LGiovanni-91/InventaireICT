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

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Recherche exporté</title>          
        <link rel="stylesheet" href="/Test_InventaireICT/public/css/style.css">
        <style>
            body { font-family: Arial; font-size: 11px; }
            table { width:100%; border-collapse: collapse; }
            th, td { border:1px solid #444; padding:4px; }
            th { background:#1e293b; color:white; }

            @media print {
                button { display:none; }
                @page {
                    size: auto;   /* taille de la page auto */
                    margin: 10; 
                }
                body {
                    margin: 0;
                }
            }
        </style>
    </head>

    <body style="background-color: white;">
            <h1 style="text-align:right;">
                <?= htmlspecialchars($_SESSION['user']['prenom']) ?>
                <?= htmlspecialchars($_SESSION['user']['nom']) ?><br>
                <p>Matricule : <?= htmlspecialchars($_SESSION['user']['matricule']) ?></p>
            </h1>
            <img src="/Test_InventaireICT/src/img/police.png" height="40">
            <h2 class="pageSL">Inventaire ICT</h2>

            <div class="form-group" style="text-align:right;">
                <button onclick="window.print()">🖨️ Export PDF</button>
            </div>

            <h3>Résultats (<?= count($data) ?>)</h3>

            <table>
                <tr>
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
                </tr>

                <?php foreach ($data as $m): ?>
                    <tr>
                        <td><?= $m['identifiant'] ?></td>
                        <td><?= $m['imei'] ?></td>
                        <td><?= $m['serial_nr'] ?></td>
                        <td><?= $m['tei'] ?></td>
                        <td><?= $m['status'] ?></td>
                        <td><?= $m['type_pc'] ?></td>
                        <td><?= $m['modele'] ?></td>
                        <td><?= $m['marque'] ?></td>       
                        <td><?= $m['unite'] ?></td>
                        <td><?= $m['site'] ?></td>
                        <td><?= $m['service'] ?></td>
                        <td><?= $m['local'] ?></td>
                        <td><?= $m['user'] ?></td>
                        <td><?= $m['matricule_user'] ?></td>
                        <td><?= $m['network'] ?></td>
                        <td><?= $m['hasFeature'] ?></td>
                        <td><?= $m['usage_name'] ?></td>
                        <td><?= $m['note'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
    </body>
</html>
