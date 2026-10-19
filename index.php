<?php
    session_start();
    //ini_set('session.gc_maxlifetime', 1800); // 30 minutes
    require("./public/bin/Require_files.php");
    if($_SESSION['logout']==1){
        $_SESSION['logout']=0;
        print "<meta http-equiv=\"refresh\": Content=\"0;URL=index.php\">";
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inventaire ICT</title>
    <link rel="stylesheet" href="./public/css/style.css">
</head>
    <body>
        
        <?php require './src/views/machines/Menu.php'; ?>       
        <?php
                if(!isset($_SESSION['Page2'])){//La variable page stocke le nom de la page à laquelle on veut accèder.
                    $_SESSION['Page2']="Connexion.php";
                }
                if(!empty($_GET['Page'])){
                    $_SESSION['Page2']= $_GET['Page'];
                }
                if (file_exists("./public/".$_SESSION['Page2'])){
                    include("./public/".$_SESSION["Page2"]);
                }else{
                    include("./public/404.php");
                }
            ?>            
    </body>
</html>