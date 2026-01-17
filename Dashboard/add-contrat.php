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
    <style>
        .add-term-btn {
    margin: 10px 0 20px;
    padding: 8px 12px;
    background: #2c7be5;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.add-term-btn:hover {
    background: #1a5dcc;
}

    </style>
    <title>Ajouter une contrat</title>
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
                    echo "<li><a href='./me-reservations.php'>Réservations</a></li>";
                }
                else{
                    echo "<li><a href='./index.php'>Accueil</a></li>";
                    if($info["role"] == "admin"){
                        echo "<li><a href='./users.php'>Utilisteurs</a></li>";
                    }
                    else{
                        echo "
                        <li><a href='./users.php'>Proprietaires</a></li>
                        ";
                    }
                    echo "
                        <li><a href='./reservations.php'>Réservations</a></li>
                        <li><a href='./contrats.php'>Contrat</a></li>
                        <li><a href='./localion.php'>Location</a></li>
                    ";
                }
            ?>
<li class="has-submenu">
        <a href="#" onclick="toggleSettings(event)">Paramètres ▾</a>
        <ul class="submenu" id="settingsMenu">
        <li><a href="./paramètres/index.php">Informations</a></li>
        <li><a href="./paramètres/mot_passe.php">Mot de passe</a></li>
    </ul>
</li>        </ul>
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
<input type="hidden" id="clientId" name="clientId"/>
<div id="clientResults" class="results"></div>

    <label>location</label>
    <select name="location" id="location" required>
        <option value="">-- choisir --</option>
    </select>


    <label>durée contrat
    </label>
    <input type="text" name="duree" required>

    <label>Termes du contrat</label>

<div id="termsContainer">
    <input type="text" name="terms[]" placeholder="Terme 1" required>
</div>

<button type="button" class="add-term-btn" onclick="addTerm()">+ Ajouter un terme</button>


    <button name="btn" type="submit">Créez</button>

    <?php
    if(isset($_POST["btn"])){
        $location = $_POST["location"];
        $client_id = $_POST["clientId"];
        $dure = $_POST["duree"];
            $terms = $_POST["terms"]; // array

        $sql = "INSERT INTO contrat VALUES(NULL,now(),'$dure',$client_id,$location,'en cours')";
        $res = mysqli_query($conn,$sql);
        $contrat_id = mysqli_insert_id($conn);

    foreach($terms as $term){
        echo $term;
        $term = mysqli_real_escape_string($conn, $term);
        mysqli_query($conn, "INSERT INTO terms VALUES(NULL, $contrat_id, '$term')");
    }

        $sqlLoc = "UPDATE location SET etat = 'occupé' WHERE id = $location";
        $resLoc = mysqli_query($conn,$sqlLoc);
        header("location:contrats.php");
    }
    
    ?>


</form>
</main>
<script src="./main.js"></script>
<script>
let termCount = 1;

function addTerm() {
    termCount++;

    const container = document.getElementById("termsContainer");

    const input = document.createElement("input");
    input.type = "text";
    input.name = "terms[]";
    input.placeholder = "Terme " + termCount;
    input.style.marginTop = "10px";
    input.required = true;

    container.appendChild(input);
}

</script>
</body>
</html>
