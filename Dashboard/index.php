<?php
require_once("../connect.php");
session_start();
if(!isset($_SESSION["info"])){
        header("location:../login.php");
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
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
                    echo "<li><a href='#'>Réservations</a></li>";
                }
                else{
                    echo "<li><a href='#'>Accueil</a></li>";
                    if($info["role"] == "admin"){
                        echo "<li><a href='#'>utilisteurs</a></li>";
                    }
                    else{
                        echo "
                        <li><a href='#'>proprietaires</a></li>
                        ";
                    }
                    echo "
                        <li><a href='#'>Réservations</a></li>
                        <li><a href='#'>Contrat</a></li>
                        <li><a href='./location.php'>Location</a></li>
                    ";
                }
            ?>
            <li><a href="#">Paramètres</a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="content">
        <h1>Bienvenue Kais 👋</h1>
        <p>Voici votre tableau de bord</p>
    </main>

</div>

<script src="main.js"></script>
</body>
</html>
