<?php
require_once("../connect.php");
session_start();

if(!isset($_SESSION["info"])){
    header("location:../login.php");
    exit;
}

if(!isset($_GET['id'])){
    die("Propriétaire invalide");
}

$prop_id = intval($_GET['id']);

// معلومات صاحب الملكية
$sqlProp = "SELECT nom, prenom, cin, email FROM users WHERE id = $prop_id";
$resProp = mysqli_query($conn, $sqlProp);
$prop = mysqli_fetch_assoc($resProp);

// كل الـ locations الخاصة بهذا propriétaire
$sqlLocations = "SELECT * FROM location WHERE prop_id = $prop_id ORDER BY created_at DESC";
$resLocations = mysqli_query($conn, $sqlLocations);

// كل الـ contrats المرتبطة بهذه الـ locations
$sqlContrats = "
SELECT c.*, l.adresse, uc.nom AS nom_client, uc.prenom AS prenom_client
FROM contrat c
JOIN location l ON l.id = c.location_id
JOIN users uc ON uc.id = c.client_id
WHERE l.prop_id = $prop_id
ORDER BY c.date_cont DESC
";
$resContrats = mysqli_query($conn, $sqlContrats);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Propriétaire</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        .head{
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .head a {
            text-decoration: none;
            color: white;
        }
        .btn-add {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #f5f5f5;
        }
        .btn {
            padding: 6px 10px;
            background: red;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
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
                    echo "<li><a href='./me-reservations'>Réservations</a></li>";
                }
                else{
                    echo "<li><a href='./index.php'>Accueil</a></li>";
                    if($info["role"] == "admin"){
                        echo "<li><a href='./users'>utilisteurs</a></li>";
                    }
                    else{
                        echo "
                        <li><a href='./users'>propriétaires</a></li>
                        ";
                    }
                    echo "
                        <li><a href='./reservations.php'>Réservations</a></li>
                        <li><a href='./contrats.php.php'>Contrat</a></li>
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
</li>        </ul>
    </aside>
        <main class="content">

<h2>Historique de <?= $prop['prenom'] . " " . $prop['nom'] ?></h2>

<h3 style="padding: 15px;">Locations</h3>
<?php if(mysqli_num_rows($resLocations) > 0): ?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th>État</th>
            <th>Type</th>
            <th>Adresse</th>
            <th>Prix</th>
            <th>Créé le</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; while($loc = mysqli_fetch_assoc($resLocations)): ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= $loc['description'] ?></td>
            <td><?= $loc['etat'] ?></td>
            <td><?= $loc['type'] ?></td>
            <td><?= $loc['adresse'] ?></td>
            <td><?= $loc['prix'] ?> DT</td>
            <td><?= $loc['created_at'] ?></td>
        </tr>
        <?php $i++; endwhile; ?>
    </tbody>
</table>
<?php else: ?>
<p>Aucune location trouvée</p>
<?php endif; ?>

<h3 style="padding: 15px;">Contrats</h3>
<?php if(mysqli_num_rows($resContrats) > 0): ?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Location</th>
            <th>Date contrat</th>
            <th>Durée</th>
            <th>État</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; while($c = mysqli_fetch_assoc($resContrats)): ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= $c['prenom_client'] . " " . $c['nom_client'] ?></td>
            <td><?= $c['adresse'] ?></td>
            <td><?= $c['date_cont'] ?></td>
            <td><?= $c['durée_cont'] ?></td>
            <td><?= $c['etat'] ?></td>
        </tr>
        <?php $i++; endwhile; ?>
    </tbody>
</table>
<?php else: ?>
<p>Aucun contrat trouvé</p>
<?php endif; ?>
</main>

<a href="users.php">← Retour</a>
<script src="./main.js"></script>
</body>
</html>
