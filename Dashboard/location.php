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
                        <li><a href='./location.php'>Location</a></li>
                    ";
                }
            ?>
            <li><a href="#">Paramètres</a></li>
        </ul>
    </aside>
        <main class="content">
<h2>Ajouter une Localion</h2>

<form method="POST" enctype="multipart/form-data" class="property-form">

<label>Rechercher un admin</label>
<input type="text" id="adminSearch" a placeholder="Tapez le nom..." autocomplete="off">
<input type="hidden" id="proprietaireId"/>
<div id="adminResults" class="results"></div>

    <label>Description</label>
    <textarea name="description" required></textarea>

    <label>État</label>
    <select name="etat" required>
        <option value="">-- choisir --</option>
        <option value="libre">Libre</option>
        <option value="occupé">Occupé</option>
    </select>

    <label>Type</label>
    <select name="type" required>
        <option value="">-- choisir --</option>
        <option value="villa">Villa</option>
        <option value="appartement">Appartement</option>
    </select>

    <label>Adresse</label>
    <input type="text" name="adresse" required>

    <label>Prix (DT)</label>
    <input type="number" name="prix" step="0.01" required>

    <label>Classification</label>
    <input type="text" name="classification" placeholder="Ex: S0, S1">
     <label>Images (multiple)</label>
    <input type="file" name="images[]" multiple accept="image/*" required>

    <button type="submit">Ajouter</button>

</form>
</main>
<script src="./main.js"></script>
</body>
</html>
