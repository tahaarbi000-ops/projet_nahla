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
    $role = $userInfo["role"];
    if($role == "client"){
        header("location:../index.php");
    }
    if(isset($_POST["btn"])){
    $cin = mysqli_real_escape_string($conn, $_POST["cin"]);
    $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
    $prenom = mysqli_real_escape_string($conn, $_POST["prenom"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $num = mysqli_real_escape_string($conn, $_POST["num"]);
    
    $newRole = ($role == 'admin' && isset($_POST["role"])) ? $_POST["role"] : 'prop';

    // password فقط لو agent
    if($newRole === 'agent' && isset($_POST['password']) && !empty($_POST['password'])){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        $password = NULL; // pas de password pour propriétaire
    }

    $sqlUser = "INSERT INTO users (cin, nom, prenom, email, num_tel, role, password) 
                VALUES ('$cin', '$nom', '$prenom', '$email', '$num', '$newRole', ".($password ? "'$password'" : "NULL").")";
    
    if(mysqli_query($conn, $sqlUser)){
        echo "<p style='color:green'>Utilisateur ajouté avec succès !</p>";
    } else {
        echo "<p style='color:red'>Erreur: " . mysqli_error($conn) . "</p>";
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
                        echo "<li><a href='./users.php'>utilisteurs</a></li>";
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
<h2>Ajouter une <?php if($role == "admin") : echo "utlisteurs";else : echo "propriétaire"; endif ;?></h2>

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
    <?php if($role == "admin"): ?>
    <label>Rôle</label>
    <select name="role" id="role" required onchange="togglePassword()">
        <option value="">Choisir rôle</option>
        <option value="agent">Agent</option>
        <option value="prop">Propriétaire</option>
    </select>

    <div id="passwordDiv" style="display:none;">
        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="Tapez le mot de passe">
    </div>
<?php endif; ?>

   

    <button name="btn" type="submit">Ajouter</button>

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
