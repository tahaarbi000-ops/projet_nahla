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
    header("location:../../index.php");
    exit;
}

$info = $_SESSION["info"];
$userId = $info["email"]; // لازم id يكون مخزّن في session

// جلب بيانات المستخدم
$sql = "SELECT * FROM users WHERE email = '$userId'";
$res = mysqli_query($conn, $sql);
$userSetting = mysqli_fetch_assoc($res);
if(isset($_POST["btn"])){
    $cin = mysqli_real_escape_string($conn, $_POST["cin"]);
    $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
    $prenom = mysqli_real_escape_string($conn, $_POST["prenom"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $num = mysqli_real_escape_string($conn, $_POST["num"]);

    $updateSql = "UPDATE users 
                  SET cin='$cin', nom='$nom', prenom='$prenom', email='$email', num_tel='$num'";

    $updateSql .= " WHERE email ='$userId'";

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une propriété</title>
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
        <li><a href="./index.php">Informations</a></li>
        <li><a href="./mot_passe.php">Mot de passe</a></li>
    </ul>
</li>
        </ul>
    </aside>
       <main class="content">
<h2>Paramètres du compte</h2>

<form method="POST" class="property-form">

    <label>CIN</label>
    <input type="text" name="cin" value="<?= $userSetting['cin'] ?>" required>

    <label>Nom</label>
    <input type="text" name="nom" value="<?= $userSetting['nom'] ?>" required>

    <label>Prénom</label>
    <input type="text" name="prenom" value="<?= $userSetting['prenom'] ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= $userSetting['email'] ?>" required>

    <label>Numéro de Téléphone</label>
    <input type="text" name="num" value="<?= $userSetting['num_tel'] ?>" required>
    <?php 
    if(isset($updateSql)){
        if(mysqli_query($conn, $updateSql)){

        $_SESSION["info"]["email"] = $email;
        header("location:./parametres.php");
    } else {
        echo "<p style='color:red'>Erreur lors de la mise à jour</p>";
    }
    }
    ?>

    <button name="btn" type="submit">Mettre à jour</button>
</form>
</main>

<script src="../main.js"></script>
        <script>

</script>
</body>
</html>
