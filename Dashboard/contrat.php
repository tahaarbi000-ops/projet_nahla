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
    <title>Ajouter une propriété</title>
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
                        <li><a href='./localion.php'>Location</a></li>
                    ";
                }
            ?>
            <li><a href="#">Paramètres</a></li>
        </ul>
    </aside>
        <main class="content">
<h2>Créez contrat</h2>

<form method="POST" class="property-form">

<label>Rechercher un proprietaire</label>
<input type="text" id="adminSearch" a placeholder="Tapez le nom..." autocomplete="off">
<input type="hidden" id="proprietaireId"/>
<div id="adminResults" class="results"></div>

<label>Rechercher un client</label>
<input type="text" id="clientSearch" a placeholder="Tapez le nom..." autocomplete="off">
<input type="hidden" id="clientId"/>
<div id="clientResults" class="results"></div>

    <label>location</label>
    <select name="etat" id="location" required>
        <option value="">-- choisir --</option>
    </select>


    <label>durée contrat
    </label>
    <input type="text" name="duree" required>


    <button type="submit">Créez</button>

</form>
</main>
<script src="./main.js"></script>
</body>
</html>
