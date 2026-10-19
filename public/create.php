<?php
$statusList = Status::getAll();
$siteList   = Site::getAll();
$uniteList  = Unite::getAll();
$serviceList = Service::getAll();
$markList   = Mark::getAll();
$modelList  = ModelTypePc::getAll();
$networkList = Network::getAll();
$usageList  = Usage::getAll();
$userListe = User::getAll();
$errors = [];

$id = $_GET['id'];
$machine = Material::getById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'];
    $imei        = $_POST['imei'];
    $serial_nr   = $_POST['serial_nr'];
    $tei         = $_POST['tei'];

    // Vérification des doublons
    $errors = Material::VerifDoublons($identifiant, $imei, $serial_nr, $tei);

    if (empty($errors)) {
        try {
            Material::create([
                'identifiant'          => $identifiant,
                'imei'                 => $imei,
                'serial_nr'            => $serial_nr,
                'tei'                  => $tei,
                'status_fk'            => $_POST['status_fk'],
                'modelTypeProdCode_fk' => $_POST['modelTypeProdCode_fk'],
                'mark_fk'              => $_POST['mark_fk'],
                'unite_fk'             => $_POST['unite_fk'],
                'service_fk'           => $_POST['service_fk'],
                'site_fk'              => $_POST['site_fk'],
                'local'                => $_POST['local'],
                'user'                 => $_POST['user'],
                'matricule_user'       => $_POST['matricule_user'],
                'network_fk'           => $_POST['network_fk'],
                'usage_fk'             => $_POST['usage_fk'],
                'hasFeature'           => $_POST['hasFeature'],
                'note'                 => $_POST['note'],
                'user_fk'              => $_SESSION['user']['matricule'],
            ]);
            header('Location: index.php?Page=inventaire.php');
            exit;
        } catch (PDOException $e) {
            $errors['sql'] = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
    
    if (!ctype_digit($_POST['imei'])) {
    $errors['imei'] = "L'IMEI doit contenir uniquement des chiffres.";
    }
}
?>

<div class="container">   
<h1 class="pageSL">💻 Ajouter du matériel</h1>

<form method="POST">
    <?php if(!empty($errors['identifiant'])): ?>
        <p style="color:red;"><?= $errors['identifiant'] ?></p>
    <?php endif; ?>
    <label>PE</label><br>
    <input type="text" name="identifiant" ><br><br>

    <?php if(!empty($errors['imei'])): ?>
        <p style="color:red;"><?= $errors['imei'] ?></p>
    <?php endif; ?>
    <label>IMEI - (Chiffre uniquement)</label><br>
    <input type="text" name="imei" value="<?= isset($machine) ? htmlspecialchars($machine['imei']) : '' ?>" pattern="[0-9]+" inputmode="numeric" maxlength="20" required><br><br>

    <?php if(!empty($errors['serial_nr'])): ?>
        <p style="color:red;"><?= $errors['serial_nr'] ?></p>
    <?php endif; ?>
    <label>Serial Nr</label><br>
    <input type="text" name="serial_nr"><br><br>

    <?php if(!empty($errors['tei'])): ?>
        <p style="color:red;"><?= $errors['tei'] ?></p>
    <?php endif; ?>
    <label>TEI</label><br>
    <input type="text" name="tei"><br><br>

    <label>Status</label><br>
    <select name="status_fk" required>
        <option value="" disabled selected>-- Sélectionnez un status --</option>
        <?php foreach($statusList as $status): ?>
            <option value="<?= $status['id_pk'] ?>"><?= htmlspecialchars($status['name']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Modèle / Type</label><br>
    <select name="modelTypeProdCode_fk">
        <option value="" disabled selected>-- Sélectionnez une marque --</option>
        <?php foreach ($modelList as $modelLists): ?>
            <option value="<?= $modelLists['id_pk'] ?>" <?= $machine['modelTypeProdCode_fk'] == $modelLists['id_pk'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($modelLists['model']) ?> - <?= htmlspecialchars($modelLists['type']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Marque</label><br>
    <select name="mark_fk" required>
        <option value="" disabled selected>-- Sélectionnez une marque --</option>
        <?php foreach($markList as $mark): ?>
            <option value="<?= $mark['id_pk'] ?>"><?= htmlspecialchars($mark['name']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Site</label><br>
    <select name="site_fk" id="siteSelect">
        <option value="">-- Tous les sites --</option>
        <?php foreach ($siteList as $site): ?>
            <option 
                value="<?= $site['id_pk'] ?>"
                <?= (isset($_GET['site']) && $_GET['site'] == $site['id_pk']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($site['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Unité</label><br>
    <select name="unite_fk" id="uniteSelect">
        <option value="">-- Toutes les unités --</option>
        <?php foreach ($uniteList as $unite): ?>
            <option 
                value="<?= $unite['code_pk'] ?>"
                <?= (isset($_GET['unite']) && $_GET['unite'] == $unite['code_pk']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($unite['direction']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Service</label><br>
    <select name="service_fk" id="serviceSelect">
        <option value="">-- Tous les services --</option>
        <?php foreach ($serviceList as $service): ?>
            <option 
                value="<?= $service['id_pk'] ?>"
                <?= (isset($_GET['service']) && $_GET['service'] == $service['id_pk']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($service['name'] . ' (' . $service['code_pk'] . ')') ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Local</label><br>
    <input type="text" name="local"><br><br>

    <label>User</label><br>
    <input type="text" name="user"><br><br>

    <label>Matricule User</label><br>
    <input type="text" name="matricule_user"><br><br>

    <label>Network</label><br>
    <select name="network_fk">
        <option value="" disabled selected>-- Sélectionnez un réseau --</option>
        <?php foreach($networkList as $net): ?>
            <option value="<?= $net['id_pk'] ?>"><?= htmlspecialchars($net['name']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Usage</label><br>
    <select name="usage_fk">
        <option value="" disabled selected>-- Sélectionnez un usage --</option>
        <?php foreach($usageList as $usage): ?>
            <option value="<?= $usage['id_pk'] ?>"><?= htmlspecialchars($usage['name']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>HasFeature</label><br>
    <select name="hasFeature"><br>
            <option value ="1">1</option>
            <option value ="2">0</option>
    </select><br><br>

    <label>Note</label><br>
    <textarea name="note"></textarea><br><br>

    <label>Créé par </label><br>
    <input type="text" value="<?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>" disabled><br><br>

    <button type="submit">Ajouter</button>
    <a href="index.php?Page=accueil.php">Annuler</a>
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