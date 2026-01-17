<?php
require_once("../connect.php");
session_start();

if(!isset($_SESSION["info"])){
    header("location:../login.php");
    exit;
}

if(!isset($_SESSION["info"]["role"]) == "client"){
    header("location:../index.php");
    exit;
}

/* LOGOUT */
if(isset($_GET['logout'])){
    session_destroy();
    header("location:../login.php");
    exit;
}


/* ADD LOCATION */
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $description = mysqli_real_escape_string($conn,$_POST["description"]);
    $etat = $_POST["etat"];
    $type = $_POST["type"];
    $adresse = mysqli_real_escape_string($conn,$_POST["adresse"]);
    $prix = $_POST["prix"];
    $classification = $_POST["classification"];
    $proprietaire_id = $_POST["proprietaire_id"];
    $gouvernorat = $_POST["gouvernorat"];

    // Insert locationproprietaire_id
    
    $sql = "INSERT INTO location(description,etat,type,adresse,prix,classification,prop_id,gouvernorat)
            VALUES('$description','$etat','$type','$adresse','$prix','$classification','$proprietaire_id','$gouvernorat')";
    
    if(mysqli_query($conn,$sql)){
        $location_id = mysqli_insert_id($conn);

        // Upload images
        foreach($_FILES["images"]["tmp_name"] as $key => $tmp){
            $name = time() . "_" . $_FILES["images"]["name"][$key];
            move_uploaded_file($tmp,"../img/location/".$name);

            mysqli_query($conn,"INSERT INTO images(location_id,img_url) VALUES('$location_id','$name')");
        }

        header("location: locations.php?success=1");
        exit;
    }
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
        <a href="../index.php">
            <h2>LUXELOC</h2>
        </a>
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
                    echo "<li><a href='./me-reservations.php'>Réservations</a></li>";
                }
                else{
                    echo "<li><a href='./index.php'>Accueil</a></li>";
                    if($info["role"] == "admin"){
                        echo "<li><a href='./users.php'>utilisteurs</a></li>";
                    }
                    else{
                        echo "
                        <li><a href='./users.php'>proprietaires</a></li>
                        ";
                    }
                    echo "
                        <li><a href='./reservations.php'>Réservations</a></li>
                        <li><a href='./contrats.php'>Contrat</a></li>
                        <li><a href='./locations.php'>Location</a></li>
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
<h2>Ajouter une Localion</h2>

<form method="POST" enctype="multipart/form-data" class="property-form">

<label>Rechercher un propriétaire</label>
<input type="text" id="adminSearch" a placeholder="Tapez le nom..." autocomplete="off">
<input type="hidden" name="proprietaire_id" id="proprietaireId"/>
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

    <select name="gouvernorat" id="gouvernorat">
            <option value="">📍 Toutes les localisations</option>
            <option value="Tunis">Tunis</option>
            <option value="Ariana">Ariana</option>
            <option value="Ben Arous">Ben Arous</option>
            <option value="Manouba">Manouba</option>

            <option value="Nabeul">Nabeul</option>
            <option value="Zaghouan">Zaghouan</option>
            <option value="Bizerte">Bizerte</option>

            <option value="Béja">Béja</option>
            <option value="Jendouba">Jendouba</option>
            <option value="Le Kef">Le Kef</option>
            <option value="Siliana">Siliana</option>

            <option value="Sousse">Sousse</option>
            <option value="Monastir">Monastir</option>
            <option value="Mahdia">Mahdia</option>

            <option value="Kairouan">Kairouan</option>
            <option value="Kasserine">Kasserine</option>
            <option value="Sidi Bouzid">Sidi Bouzid</option>

            <option value="Sfax">Sfax</option>

            <option value="Gabès">Gabès</option>
            <option value="Médenine">Médenine</option>
            <option value="Tataouine">Tataouine</option>

            <option value="Gafsa">Gafsa</option>
            <option value="Tozeur">Tozeur</option>
            <option value="Kébili">Kébili</option>
        </select>

     <label>Images (multiple)</label>
    <input type="file" name="images[]" multiple accept="image/*" required>

    <button type="submit">Ajouter</button>

</form>
</main>
<script src="./main.js"></script>
</body>
</html>
