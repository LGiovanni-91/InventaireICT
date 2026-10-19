<div class="container">
    <h1 class="pageSL">💻 Accueil</h1>
    <table class="tabAcc" cellpadding="5">
        <tr>
            <!--<th>ID</th>-->
            <th><button type="button" onclick="window.location.href='index.php?Page=inventaire.php'">📋 Inventaire des machines</button></th>
            <th><button type="button" onclick="window.location.href='index.php?Page=search.php'">🔍 Recherche avancée</button></th>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['right_fk'] == 1): ?>
                <th><button type="button" onclick="window.location.href='index.php?Page=create.php'">➕ Ajouter une machine</button></th>
            <?php else: ?>
            <!-- Non-admin : boutons désactivés (grisés et non cliquables) -->
                <th><button type="button" onclick="window.location.href='index.php?Page=create.php'" style="pointer-events: none; opacity: 0.5;">➕ Ajouter une machine</th>
            <?php endif; ?>
        </tr>
        <tr>
            <th><button type="button" onclick="window.location.href='index.php?Page=machineUser.php'">👤 Nombre machines utilisateurs</button></th>
            <th><button type="button" onclick="window.location.href='index.php?Page=infoUser.php'">💻 Informations utilisateurs</button></th>
        </tr>
    </table>
</div>