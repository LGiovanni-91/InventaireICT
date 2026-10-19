<?php
$services = Service::getAll();
$types = ModelTypePC::getAllSearch();
$unites = Unite::getAll();
$sites = Site::getAll();
$statuses = Status::getAll();

//Récupération des machines
$filters = [
    'pe'    => $_GET['pe'] ?? null,
    'type'  => $_GET['type'] ?? null,
    'unite' => $_GET['unite'] ?? null,
    'site'  => $_GET['site'] ?? null,
    'user'  => $_GET['user'] ?? null,
    'service' => $_GET['service'] ?? null,
    'status' => $_GET['status'] ?? null,
];

if (array_filter($filters)) {
    $machines = Material::getFiltered($filters);
} else {
    $machines = Material::getAll();
}

?>

<div class="container">
    <h1 class="pageSL">💻 Inventaire des machines</h1>  
    <div class="form-group" style="text-align:right;">
        <button type="button" onclick="window.location.href='/Test_InventaireICT/public/export/export_excel.php?<?= http_build_query($_GET) ?>'">📊 Export Excel</button>
        <button type="button" onclick="window.open('/Test_InventaireICT/public/export/export_pdf.php?<?= http_build_query($_GET) ?>&source=inventaire', '_blank')">🧾 Export PDF</button>
    </div>
<!-- Formulaire de filtre -->
<form method="GET">
    <label>Filtrage du matériel</label><br>
    <input type="text" name="pe" placeholder="PE" value="<?= $_GET['pe'] ?? '' ?>">

    <select name="type">
        <option value="">-- Tous les types --</option>
        <?php foreach ($types as $type): ?>
            <option
                value="<?= htmlspecialchars($type['type']) ?>"
                <?= (isset($_GET['type']) && $_GET['type'] == $type['type']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($type['type']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="status">
        <option value="">-- Tous les status --</option>
        <?php foreach ($statuses as $status): ?>
            <option 
                value="<?= $status['id_pk'] ?>"
                <?= (isset($_GET['status']) && $_GET['status'] == $status['id_pk']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($status['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="site" id="siteSelect">
        <option value="">-- Tous les sites --</option>
        <?php foreach ($sites as $site): ?>
            <option 
                value="<?= $site['id_pk'] ?>"
                <?= (isset($_GET['site']) && $_GET['site'] == $site['id_pk']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($site['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="unite" id="uniteSelect">
        <option value="">-- Toutes les unités --</option>
        <?php foreach ($unites as $unite): ?>
            <option 
                value="<?= $unite['code_pk'] ?>"
                <?= (isset($_GET['unite']) && $_GET['unite'] == $unite['code_pk']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($unite['direction']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="service" id="serviceSelect">
        <option value="">-- Tous les services --</option>
        <?php foreach ($services as $service): ?>
            <option 
                value="<?= $service['id_pk'] ?>"
                <?= (isset($_GET['service']) && $_GET['service'] == $service['id_pk']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($service['name'] . ' (' . $service['code_pk'] . ')') ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="user" placeholder="User" value="<?= $_GET['user'] ?? '' ?>">

    <div style="text-align:center;">
        <button type="submit">Filtrer</button>
        <a href="index.php?Page=inventaire.php">Réinitialiser</a>
    </div>
</form>

<!-- Tableau -->
<h3>Résultats (<?= count($machines) ?>)</h3>

<table border="1" cellpadding="5">
    <tr>
        <!--<th>ID</th>-->
        <th>PE</th>
        <th>Type</th>
        <th>Modèle</th>
        <th>Marque</th>
        <th>Status</th>
        <th>Direction</th>
        <th>Site</th>
        <th>Service</th>
        <th>User</th>
        <th>Action</th>
    </tr>
</div>
    <?php foreach ($machines as $machine): ?>
        <tr>
            <td><?= htmlspecialchars($machine['identifiant']) ?></td>
            <td><?= htmlspecialchars($machine['type_pc']) ?></td>
            <td><?= htmlspecialchars($machine['modele']) ?></td>
            <td><?= htmlspecialchars($machine['marque']) ?></td>
            <td><?= htmlspecialchars($machine['status']) ?></td>
            <td><?= htmlspecialchars($machine['unite']) ?></td>
            <td><?= htmlspecialchars($machine['site']) ?></td>
            <td><?= htmlspecialchars($machine['service']) ?></td>
            <td><?= htmlspecialchars($machine['user']) ?></td>
            <td>
                <a href="index.php?Page=show.php&amp;id=<?= $machine['id_pk'] ?>">Voir</a> |                 
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['right_fk'] == 1): ?>
                    <a href="index.php?Page=edit.php&amp;id=<?= $machine['id_pk'] ?>">Modifier</a>
                <?php else: ?>
                    <!-- Non-admin : boutons désactivés (grisés et non cliquables) -->
                    <a href="#" title="Modif" style="pointer-events: none; opacity: 0.5;">Modifier</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

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