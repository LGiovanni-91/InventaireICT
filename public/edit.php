<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID invalide');
}

$id = $_GET['id'];
$machine = Material::getById($id);

if (!$machine) {
    die('Machine introuvable');
}

// Récupérer toutes les options pour les select
$statuses = Status::getAll();
$sites = Site::getAll();
$unites = Unite::getAll();
$services = Service::getAll();
$marks = Mark::getAll();
$models = ModelTypePC::getAll();
$networks = Network::getAll();
$usages = Usage::getAll();
$errorMessage = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'];
    $imei        = $_POST['imei'];
    $serial_nr   = $_POST['serial_nr'];
    $tei         = $_POST['tei'];

    // Vérification des doublons
    $errorMessage = Material::VerifDoublonsUpdate($id, $identifiant, $imei, $serial_nr, $tei);

    if (empty($errorMessage)) {
        try {
            Material::update($id, [
                'identifiant' => $identifiant,
                'imei'        => $imei,
                'serial_nr'   => $serial_nr,
                'tei'         => $tei,
                'local'       => $_POST['local'],
                'status_fk'   => $_POST['status_fk'],
                'user'        => $_POST['user'],
                'site_fk'     => $_POST['site_fk'],
                'hasFeature'  => $_POST['hasFeature'],
                'unite_fk'    => $_POST['unite_fk'],
                'service_fk'  => $_POST['service_fk'],
                'mark_fk'     => $_POST['mark_fk'],
                'matricule_user' => $_POST['matricule_user'],
                'modelTypeProdCode_fk' => $_POST['modelTypeProdCode_fk'],
                'network_fk'  => $_POST['network_fk'],
                'usage_fk'    => $_POST['usage_fk'],
                'note'        => $_POST['note'],
                'user_fk' => $_SESSION['user']['matricule'],
            ]);
            header("Location: index.php?Page=show.php&id=$id&from=edit");
            exit;
        } catch (PDOException $e) {
            $errorMessage['general'] = "Erreur lors de la modification : " . $e->getMessage();
        }
    }

    if (!ctype_digit($_POST['imei'])) {
    $errorMessage['imei'] = "L'IMEI doit contenir uniquement des chiffres.";
    }
}
?>

<div class="container">
    <h1 class="pageSL">💻 Modifier le matériel</h1>
    <?php if (!empty($errorMessage)): ?>
        <div class="errors">
            <?php foreach ($errorMessage as $field => $msg): ?>
                <p style="color:red;"><?= htmlspecialchars($msg) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <label>PE</label><br>
        <input type="text" name="identifiant" value="<?= htmlspecialchars($machine['identifiant']) ?>"><br><br>

        <label>IMEI - (Chiffre uniquement) </label><br>
        <input type="text" name="imei" value="<?= isset($machine) ? htmlspecialchars($machine['imei']) : '' ?>" pattern="[0-9]+" inputmode="numeric" maxlength="20" required><br><br>

        <label>Serial_nr</label><br>
        <input type="text" name="serial_nr" value="<?= htmlspecialchars($machine['serial_nr']) ?>"><br><br>

        <label>TEI</label><br>
        <input type="text" name="tei" value="<?= htmlspecialchars($machine['tei']) ?>"><br><br>

        <label>Local</label><br>
        <input type="text" name="local" value="<?= htmlspecialchars($machine['local']) ?>"><br><br>

        <label>Status</label><br>
        <select name="status_fk">
            <?php foreach ($statuses as $status): ?>
                <option value="<?= $status['id_pk'] ?>" <?= $machine['status_fk'] == $status['id_pk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($status['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Site</label><br>
        <select name="site_fk" id="siteSelect">
            <option value="">-- Tous les sites --</option>
            <?php foreach ($sites as $site): ?>
                <option 
                    value="<?= $site['id_pk'] ?>"
                    <?= $machine['site_fk'] == $site['id_pk'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($site['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Unité</label><br>
        <select name="unite_fk" id="uniteSelect">
            <option value="">-- Toutes les unités --</option>
            <?php foreach ($unites as $unite): ?>
                <option 
                    value="<?= $unite['code_pk'] ?>"
                    <?= $machine['unite_fk'] == $unite['code_pk'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($unite['direction']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Service</label><br>
        <select name="service_fk" id="serviceSelect">
            <option value="">-- Tous les services --</option>
            <?php foreach ($services as $service): ?>
                <option 
                    value="<?= $service['id_pk'] ?>"
                    <?= $machine['service_fk'] == $service['id_pk'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($service['name'] . ' (' . $service['code_pk'] . ')') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>HasFeature</label><br>
        <input type="text" name="hasFeature" value="<?= htmlspecialchars($machine['hasFeature']) ?>"><br><br>

        <label>User</label><br>
        <input type="text" name="user" value="<?= htmlspecialchars($machine['user']) ?>"><br><br>

        <label>Matricule_user</label><br>
        <input type="text" name="matricule_user" value="<?= htmlspecialchars($machine['matricule_user']) ?>"><br><br>

        <label>Marque</label><br>
        <select name="mark_fk">
            <?php foreach ($marks as $mark): ?>
                <option value="<?= $mark['id_pk'] ?>" <?= $machine['mark_fk'] == $mark['id_pk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($mark['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Modèle / Type</label><br>
        <select name="modelTypeProdCode_fk">
            <?php foreach ($models as $model): ?>
                <option value="<?= $model['id_pk'] ?>" <?= $machine['modelTypeProdCode_fk'] == $model['id_pk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($model['model']) ?> - <?= htmlspecialchars($model['type']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Network</label><br>
        <select name="network_fk">
            <?php foreach ($networks as $net): ?>
                <option value="<?= $net['id_pk'] ?>" <?= $machine['network_fk'] == $net['id_pk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($net['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Usage</label><br>
        <select name="usage_fk">
            <?php foreach ($usages as $usage): ?>
                <option value="<?= $usage['id_pk'] ?>" <?= $machine['usage_fk'] == $usage['id_pk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($usage['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Note</label><br>
        <textarea name="note"><?= htmlspecialchars($machine['note']) ?></textarea><br><br>

        <label>Modifié par </label><br>
        <input type="text" value="<?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>" disabled><br><br>

        <button type="submit">Enregistrer</button>
        <button type="button" onclick="history.back()"> ⬅ Retour </button>
        <a href="index.php?Page=show.php&amp;id=<?= $id ?>">Détails Machine</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const siteSelect = document.getElementById('siteSelect');
    const uniteSelect = document.getElementById('uniteSelect');
    const serviceSelect = document.getElementById('serviceSelect');

    siteSelect.addEventListener('change', function () {
        const siteId = this.value;

        // Si aucun site → on désactive et on vide
        if (!siteId) {
            uniteSelect.disabled = true;
            serviceSelect.disabled = true;

            uniteSelect.innerHTML = '<option value="">-- Toutes les unités --</option>';
            serviceSelect.innerHTML = '<option value="">-- Tous les services --</option>';
            return;
        }

        // Site sélectionné → on active
        uniteSelect.disabled = false;
        serviceSelect.disabled = false;

        fetch('/Test_InventaireICT/public/ajax/getSiteData.php?site_id=' + siteId)
            .then(response => response.json())
            .then(data => {

                uniteSelect.innerHTML = '<option value="">-- Toutes les unités --</option>';
                serviceSelect.innerHTML = '<option value="">-- Tous les services --</option>';

                data.unites.forEach(u => {
                    uniteSelect.innerHTML += `<option value="${u.code_pk}">${u.direction}</option>`;
                });

                data.services.forEach(s => {
                    serviceSelect.innerHTML += `<option value="${s.id_pk}">${s.name}</option>`;
                });
            });
    });
});
</script>