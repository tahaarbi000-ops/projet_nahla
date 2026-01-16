<?php
require_once("../connect.php");
session_start();
if(!isset($_SESSION["info"])){
        header("location:../login.php");
    }
    if(isset($_GET["logout"])){
            session_unset();
            session_destroy();
            header("location:./index.php");
        }
    $userInfo = $_SESSION["info"];
    if(isset($_POST["btn"])){
    
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
                    echo "<li><a href='./me-reservations.php'>Réservations</a></li>";
                }
                else{
                    echo "<li><a href='./index.php'>Accueil</a></li>";
                    if($info["role"] == "admin"){
                        echo "<li><a href='./users.php'>utilisateurs</a></li>";
                    }
                    else{
                        echo "
                        <li><a href='./users'>proprietaires</a></li>
                        ";
                    }
                    echo "
                        <li><a href='./reservations.php'>Réservations</a></li>
                        <li><a href='./contrats.php'>Contrat</a></li>
                        <li><a href='./locations.php'>Location</a></li>
                    ";
                }
            ?>
            <li><a href=".parametres.php">Paramètres</a></li>
        </ul>
    </aside>
        <main class="content">
<h2>parametres</h2>

<form method="POST" enctype="multipart/form-data" class="property-form">

<label>Cin</label>
<input type="text" name="cin" placeholder="Tapez le Cin...">

<label>Nom</label>
<input type="text" name="nom" placeholder="Tapez le Nom...">

    <label>Prénom</label>
    <input type="text" name="prenom" placeholder="Tapez le Prénom..." required />

    <label>Email</label>
    <input type="text" name="email" placeholder="Tapez le Email..." required>

    <label>Numéro de Télephone</label>
    <input type="text" name="num" placeholder="Tapez le Numéro de Télephone..." required>

    <label>Mot de passe</label>
    <input type="password" name="password" placeholder="Tapez le mot de passe">
   

    <button name="btn" type="submit">Mettre a jour</button>

</form>
</main>
    <script>
function togglePassword() {
    const role = document.querySelector("#role").value;
    const passwordDiv = document.getElementById("passwordDiv");

    if(role === "agent") {
        passwordDiv.style.display = "block";
    } else {
        passwordDiv.style.display = "none";
    }
}

</script>
</body>
</html>
