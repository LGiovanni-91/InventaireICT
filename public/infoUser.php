<?php
$nom = $_SESSION['user']['nom'] ?? null;
$prenom = $_SESSION['user']['prenom'] ?? null;

if (!$nom || !$prenom) {
    header('Location: index.php');
    exit;
}

$userFullName = $nom . ' ' . $prenom;
$machines = Material::getMachinesByUserName($userFullName);
?>

<div class="container">
    <h2 class="pageSL">💻 Mes machines</h2>

    <div class="form-group" style="text-align:right;">
        <button type="button" onclick="window.location.href='index.php?Page=accueil.php'">⬅ Retour</button>
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
                <th>Action</th>
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
                    <td style="text-align:center;">
                        <a href="index.php?Page=show.php&amp;id=<?= $m['id_pk'] ?>">Voir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune machine associée à votre compte.</p>
    <?php endif; ?>
</div>
