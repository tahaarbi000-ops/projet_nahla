<?php
require_once("../../connect.php");
session_start();

if(!isset($_SESSION["info"])){
    header("location:../login.php");
    exit;
}

if(isset($_GET["logout"])){
    session_unset();
    session_destroy();
    header("location:../index.php");
    exit;
}

$info = $_SESSION["info"];
$userId = $info["email"];

$message = "";

if(isset($_POST["change_password"])){

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $sql = "SELECT password FROM users WHERE email = '".$info['email']."'";
    $res = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($res);

    if(!password_verify($old, $user['password'])){
        $message = "<p style='color:red'>Mot de passe actuel incorrect</p>";
    }
    elseif($new !== $confirm){
        $message = "<p style='color:red'>La confirmation ne correspond pas</p>";
    }
    else{
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = "UPDATE users SET password='$hashed' WHERE email='".$info['email']."'";
        mysqli_query($conn, $update);
        $message = "<p style='color:green'>Mot de passe modifié avec succès</p>";
    }
}



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Change Mot de Passe</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<!-- TOPBAR -->
<header class="topbar">
    <div class="top-left">
        <h2>LUXELOC</h2>
    </div>

    <div class="top-right">
        <div class="user-menu" onclick="toggleDropdown()">
            <?php
                $info = $_SESSION["info"];
                $sql = "SELECT nom,prenom FROM users WHERE email = '" . $info["email"] ." ' ";
                $res = mysqli_query($conn,$sql);
                $user = mysqli_fetch_assoc($res);
                echo "<span>" . $user['nom'] . " " . $user['prenom'] . "</span>";

            ?>
            
            <div class="dropdown" id="dropdownMenu">
                <a href="?logout" class="logout">Déconnexion</a>
            </div>
        </div>
    </div>
</header>

<!-- CONTAINER -->
<div class="container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <ul>
            <?php
                if($info["role"] == "client"){
                    echo "<li><a href='../me-reservations.php'>Réservations</a></li>";
                }
                else{
                    echo "<li><a href='../index.php'>Accueil</a></li>";
                    if($info["role"] == "admin"){
                        echo "<li><a href='../users.php'>Utilisateurs</a></li>";
                    }
                    else{
                        echo "
                        <li><a href='../users'>proprietaires</a></li>
                        ";
                    }
                    echo "
                        <li><a href='../reservations.php'>Réservations</a></li>
                        <li><a href='../contrats.php'>Contrat</a></li>
                        <li><a href='../locations.php'>Location</a></li>
                    ";
                }
            ?>
<li class="has-submenu">
    <a href="#" onclick="toggleSettings(event)">Paramètres ▾</a>
    <ul class="submenu" id="settingsMenu">
        <li><a href="./paramètres/index.php">Informations</a></li>
        <li><a href="./paramètres/mot_passe.php">Mot de passe</a></li>
    </ul>
</li>
        </ul>
    </aside>
       <main class="content">
<h2>Paramètres du compte</h2>

<form method="POST" class="property-form">

    <label>Mot de passe actuel</label>
    <input type="password" name="old_password" required>

    <label>Nouveau mot de passe</label>
    <input type="password" name="new_password" required>

    <label>Confirmer le mot de passe</label>
    <input type="password" name="confirm_password" required>
    <?php
     if(isset($_POST["change_password"])){
        echo $message;
     }
    ?>

    <button name="change_password" type="submit">Mettre à jour</button>
</form>
</main>

<script src="../main.js"></script>
        <script>

</script>
</body>
</html>
