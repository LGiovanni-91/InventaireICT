<?php
$user = $_GET['user'] ?? null;
$users = Material::getUsersWithMultipleMachines($searchUser);

if (!$user) {
    header('Location: machineUser.php');
    exit;
}

$machines = Material::getMachinesByUser($user);
?>

<div class="container">
    <h2 class="pageSL">💻 Machines de <?= htmlspecialchars($user) ?></h2>

    <div class="form-group" style="text-align:right;">
        <button type="button" onclick="window.location.href='/Test_InventaireICT/public/export/export_excel.php?user=<?= urlencode($user) ?>&source=machineUser'">
            📊 Export Excel
        </button>
        
        <button type="button" 
            onclick="window.open('/Test_InventaireICT/public/export/export_pdf.php?user=<?= urlencode($user) ?>&source=machineUser', '_blank')">
            🧾 Export PDF
        </button>

        <button type="button" onclick="window.location.href='index.php?Page=machineUser.php'">
            ⬅ Retour
        </button>
    </div>

    <?php if (!empty($machines)): ?>
        <table border="1" cellpadding="6" width="100%">
            <tr>
                <th>PE</th>
                <th>IMEI</th>
                <th>Serial</th>
                <th>TEI</th>
                <th>Statut</th>
                <th>Type</th>
                <th>Modèle</th>
                <th>Site</th>
                <th>Unité</th>
                <th>Service</th>
                <th>Local</th>
            </tr>

            <?php foreach ($machines as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['identifiant']) ?></td>
                    <td><?= htmlspecialchars($m['imei']) ?></td>
                    <td><?= htmlspecialchars($m['serial_nr']) ?></td>
                    <td><?= htmlspecialchars($m['tei']) ?></td>
                    <td><?= htmlspecialchars($m['status']) ?></td>
                    <td><?= htmlspecialchars($m['type_pc']) ?></td>
                    <td><?= htmlspecialchars($m['modele']) ?></td>
                    <td><?= htmlspecialchars($m['site']) ?></td>
                    <td><?= htmlspecialchars($m['unite']) ?></td>
                    <td><?= htmlspecialchars($m['service']) ?></td>
                    <td><?= htmlspecialchars($m['local']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune machine trouvée pour cet utilisateur.</p>
    <?php endif; ?>
</div>