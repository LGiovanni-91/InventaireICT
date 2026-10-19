<?php
$searchUser = $_POST['user'] ?? '';
$users = Material::getUsersWithMultipleMachines($searchUser);
?>

<div class="container">
    <h2 class="pageSL">💻 Nombre de machines qu'un utilisateur possède</h2>
    <div class="form-group" style="text-align:right;">
        <button type="button" onclick="window.location.href='index.php?Page=accueil.php'">
            ⬅ Retour
        </button>
    </div>

    <form method="POST">
        <label>Utilisateur :</label>
        <input type="text" name="user" value="<?= $_POST['user'] ?? '' ?>" placeholder="Nom d'utilisateur">

        <div class="form-group" style="text-align:center;">
            <button type="submit">🔍 Rechercher</button>
            <a href="index.php?Page=machineUser.php">Réinitialiser</a>
        </div>               
    </form>            

    <h3>Utilisateurs avec plusieurs machines (<?= count($users) ?>)</h3>

    <?php if (!empty($users)): ?>
    <table border="1" cellpadding="6" width="100%">
        <tr>
            <th>User</th>
            <th>Nombre de machines</th>
            <th>Site</th>
            <th>Unité</th>
            <th>Action</th>
        </tr>

        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['user']) ?></td>
                <td style="text-align:center;"><?= $u['nb_machines'] ?></td>               
                <td><?= htmlspecialchars($u['site']) ?></td>
                <td><?= htmlspecialchars($u['unite']) ?></td>
                <td style="text-align:center;">
                    <a href="index.php?Page=showMachineUser.php&amp;user=<?= urlencode($u['user']) ?>">
                        Voir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>Aucun utilisateur avec plusieurs machines.</p>
    <?php endif; ?>
</div>
