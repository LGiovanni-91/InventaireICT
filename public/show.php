<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID invalide');
}

$machine = Material::getById($_GET['id']);
$users = Material::getUsersWithMultipleMachines($searchUser);

if (!$machine) {
    die('Machine introuvable');
}
?>

<div class="container">
<h2 class="pageSL">💻 Détail de la machine</h2>
    <div class="form-group" style="text-align:right;">
        <?php $from = $_GET['from'] ?? null; ?>
        <button type="button" onclick="window.location.href='<?= $from === 'edit' ? "index.php?Page=inventaire.php" : "javascript:history.back()" ?>'"> ⬅ Retour </button>
    </div>        
    <div class="machine-detail">
        <div class="row">
            <span>Utilisateur</span>
            <span>
                <a href="index.php?Page=showMachineUser.php&amp;user=<?= urlencode($machine['user']) ?>">
                    <?= htmlspecialchars($machine['user']) ?>
                </a>
            </span>
        </div>
        <div class="row"><span>PE</span><span><?= htmlspecialchars($machine['identifiant']) ?></span></div>
        <div class="row"><span>IMEI</span><span><?= htmlspecialchars($machine['imei']) ?></span></div>
        <div class="row"><span>Serial</span><span><?= htmlspecialchars($machine['serial_nr']) ?></span></div>
        <div class="row"><span>TEI</span><span><?= htmlspecialchars($machine['tei']) ?></span></div>
        <div class="row"><span>Status</span><span><?= htmlspecialchars($machine['status']) ?></span></div>
        <div class="row"><span>Type</span><span><?= htmlspecialchars($machine['type_pc']) ?></span></div>
        <div class="row"><span>Modèle</span><span><?= htmlspecialchars($machine['modele']) ?></span></div>
        <div class="row"><span>Marque</span><span><?= htmlspecialchars($machine['marque']) ?></span></div>
        <div class="row"><span>Unité</span><span><?= htmlspecialchars($machine['unite']) ?></span></div>
        <div class="row"><span>Service</span><span><?= htmlspecialchars($machine['service']) ?></span></div>
        <div class="row"><span>Site</span><span><?= htmlspecialchars($machine['site']) ?></span></div>
        <div class="row"><span>Local</span><span><?= htmlspecialchars($machine['local']) ?></span></div>
        <div class="row"><span>Matricule_user</span><span><?= htmlspecialchars($machine['matricule_user']) ?></span></div>
        <div class="row"><span>Network</span><span><?= htmlspecialchars($machine['network']) ?></span></div>
        <div class="row"><span>HasFeature</span><span><?= htmlspecialchars($machine['hasFeature']) ?></span></div>
        <div class="row"><span>Usage</span><span><?= htmlspecialchars($machine['usage_name']) ?></span></div>
        <div class="row full"><span>Note</span><span><?= htmlspecialchars($machine['note']) ?></span></div><br>
        <p style="text-align:left;">
            
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['right_fk'] == 1): ?>
            <button type="button" onclick="window.location.href='index.php?Page=edit.php&amp;id=<?= $machine['id_pk'] ?>'">💻 Modifier</button>
        <?php else: ?>
        <!-- Non-admin : boutons désactivés (grisés et non cliquables) -->
            <button type="button" onclick="window.location.href='index.php?Page=edit.php&amp;id=<?= $machine['id_pk'] ?>'" style="pointer-events: none; opacity: 0.5;">💻 Modifier</button>
        <?php endif; ?>
        </p>
    </div>
</div>