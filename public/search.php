<?php
$machines = [];
$sites = Site::getAll();
$unites = Unite::getAll();
$services = Service::getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filters = [];

    if (!empty($_POST['identifiant_from'])) {
        $filters['identifiant_from'] = $_POST['identifiant_from'];
    }

    if (!empty($_POST['identifiant_to'])) {
        $filters['identifiant_to'] = $_POST['identifiant_to'];
    }

    if (!empty($_POST['note'])) {
        $filters['note'] = $_POST['note'];
    }

    if (!empty($_POST['matricule_user'])) {
        $filters['matricule_user'] = $_POST['matricule_user'];
    }
  
    if (!empty($_POST['site'])) {
        $filters['site'] = $_POST['site'];
    }

    if (!empty($_POST['unite'])) {
        $filters['unite'] = $_POST['unite'];
    }

    if (!empty($_POST['service'])) {
        $filters['service'] = $_POST['service'];
    }

    $machines = Material::getFiltered($filters);

}
?>
<div class="container">
    <h2 class="pageSL">💻 Recherche avancée</h2>
    <div class="form-group" style="text-align:right;">
        <form action="/Test_InventaireICT/public/export/export_excel.php" method="POST" target="_blank" style="display:inline;">
            <?php foreach ($_POST as $key => $value): ?>
                <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
            <?php endforeach; ?>
            <button type="submit">📊 Export Excel</button>
        </form>

        <form action="/Test_InventaireICT/public/export/export_pdf.php" method="POST" target="_blank" style="display:inline;">
            <?php foreach ($_POST as $key => $value): ?>
                <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
            <?php endforeach; ?>
            <button type="submit">🧾 Export PDF</button>
        </form>
        <button type="button" onclick="window.location.href='index.php?Page=machineUser.php'">💻 Nombre machines utilisateurs 👤</button>
    </div>
    
    <form method="POST">
        <label>Identifiant PE :</label>
        <input type="text" name="identifiant_from" placeholder="PE2015" value="<?= $_POST['identifiant_from'] ?? '' ?>">

        <label>à :</label>
        <input type="text" name="identifiant_to" placeholder="PE2017" value="<?= $_POST['identifiant_to'] ?? '' ?>">

        <label>Note :</label>
        <input type="text" name="note" value="<?= $_POST['note'] ?? '' ?>">

        <label>Matricule utilisateur :</label>
        <input type="text" name="matricule_user" value="<?= $_POST['matricule_user'] ?? '' ?>">

        <label>Site</label>
        <select name="site" id="siteSelect">
            <option value="">-- Tous les sites --</option>
            <?php foreach ($sites as $site): ?>
                <option 
                    value="<?= $site['id_pk'] ?>"
                    <?= (isset($_POST['site']) && $_POST['site'] == $site['id_pk']) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($site['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Unité</label>
        <select name="unite" id="uniteSelect">
            <option value="">-- Toutes les unités --</option>
            <?php foreach ($unites as $unite): ?>
                <option 
                    value="<?= $unite['code_pk'] ?>"
                    <?= (isset($_POST['unite']) && $_POST['unite'] == $unite['code_pk']) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($unite['direction']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Service</label>
        <select name="service" id="serviceSelect">
            <option value="">-- Tous les services --</option>
            <?php foreach ($services as $service): ?>
                <option 
                    value="<?= $service['id_pk'] ?>"
                    <?= (isset($_POST['service']) && $_POST['service'] == $service['id_pk']) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($service['name'] . ' (' . $service['code_pk'] . ')') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="form-group" style="text-align:left;">
            <button type="submit">🔍 Rechercher</button>
            <a href="index.php?Page=search.php">Réinitialiser</a>
        </div>               
    </form>
    <!-- Résultats -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <h3>Résultats (<?= count($machines) ?>)</h3>

    <?php if (!empty($machines)): ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>PE</th>
            <th>User</th>
            <th>Modèle</th>
            <th>Site</th>
            <th>Service</th>
            <th>Local</th>
            <th>Unité</th>
            <th>Action</th>
        </tr>
    </div>                       
        <?php foreach ($machines as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['identifiant']) ?></td>
                <td><?= htmlspecialchars($m['user']) ?></td>
                <td><?= htmlspecialchars($m['modele']) ?></td>
                <td><?= htmlspecialchars($m['site']) ?></td>
                <td><?= htmlspecialchars($m['service']) ?></td>
                <td><?= htmlspecialchars($m['local']) ?></td>
                <td><?= htmlspecialchars($m['unite']) ?></td>
                <td>
                    <a href="index.php?Page=show.php&amp;id=<?= $m['id_pk'] ?>">Voir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>Aucun résultat trouvé.</p>
    <?php endif; ?>
<?php endif; ?> 

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