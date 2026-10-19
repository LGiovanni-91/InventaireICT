<?php

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule = trim($_POST['matricule'] ?? '');

    if ($matricule) {
        $user = User::findByMatricule($matricule);

        if ($user) {
            // Connexion OK
            $_SESSION['user'] = [
                'matricule' => $user['matricule_pk'],
                'nom'       => $user['name'],
                'prenom'    => $user['first_name'],
                'unite_fk'  => $user['unite_fk'],
                'right_fk'  => $user['right_fk'],
            ];

            $_SESSION['Page2']="accueil.php";
            print "<meta http-equiv=\"refresh\": Content=\"0;URL=index.php\">";
            exit;
        } else {
            $error = "Matricule incorrect";
        }
    } else {
        $error = "Veuillez entrer un matricule";
    }
}
?>
<div class="containerConnect">
<h1 class="pageSL" style="text-align:center;">💻 Connexion</h1>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" class="login-form">
    <div class="form-inline">
        <label for="matricule">Identifiant : </label>
        <input type="text" id="matricule" name="matricule" placeholder="Matricule" required>
    </div>
    <div class="form-inline">
        <label for="mdp">Mot de passe : </label>
        <input type="" id="" name="Mot de passe" placeholder="" required>
    </div>
    <div class="form-bouton">
    <button type="submit">Se connecter</button>
    </div>
</form>
