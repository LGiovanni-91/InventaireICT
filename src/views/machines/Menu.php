<?php 
    $machines = Material::getAll();
?>
<!-- Barre menu H-->
<header class="navbar">
    <div class="navbar-left">               
        <div class="dropdown">
            <?php if (isset($_SESSION['Page2']) && $_SESSION['Page2'] !== 'Connexion.php'): ?>
                <?php if (basename($_SESSION['Page2']) !== 'Connexion.php'): ?>
                <button class="dropbtn">☰</button>
                <div class="wrap">
                    <div class="dropdown-content">
                        <a href="index.php?Page=accueil.php">Accueil</a>
                        <a href="index.php?Page=logout.php">Déconnexion</a>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>                
        <a href="<?= basename ($_SESSION['Page2']) !== 'Connexion.php' ? 'index.php?Page=accueil.php' : '#' ?>" 
        class="logo"
        <?= basename($_SESSION['Page2']) === 'Connexion.php' ? 'style="pointer-events: none;"' : '' ?>>
            <img src="/Test_InventaireICT/src/img/police.png" alt="Logo" height="25">
            Inventaire ICT
        </a>
        <?php if (isset($_SESSION['user'])): ?>
            <span class="user-info">
                @<?= htmlspecialchars($_SESSION['user']['nom']) ?>
                <?= htmlspecialchars($_SESSION['user']['prenom']) ?>
                [<?= htmlspecialchars($_SESSION['user']['matricule']) ?>]
            </span>
        <?php endif; ?>
    </div>
</header>

<!-- Barre menu V -->
<aside class="sidebar">
    <?php if (isset($_SESSION['Page2']) && $_SESSION['Page2'] !== 'Connexion.php'): ?>
        <a href="index.php?Page=inventaire.php" title="Inventaire">📋</a>
        <a href="index.php?Page=search.php" title="Recherche">🔍</a>
        <!-- Boutons admin uniquement -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['right_fk'] == 1): ?>
            <a href="index.php?Page=create.php" title="Ajout">➕</a>
            
        <?php else: ?>
            <!-- Non-admin : boutons désactivés (grisés et non cliquables) -->
            <a href="#" title="Ajout" style="pointer-events: none; opacity: 0.5;">➕</a>
        <?php endif; ?>
    <?php endif; ?>
</aside>
