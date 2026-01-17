<?php
require_once("../connect.php");
session_start();
if(!isset($_SESSION["info"])){
        header("location:../login.php");
    }
$sqlContrat = "SELECT c.id AS contrat_id, c.date_cont, c.durée_cont,
uc.nom AS nom_client, uc.prenom AS prenom_client,
up.nom AS nom_prop, up.prenom AS prenom_prop
FROM contrat c
JOIN users uc ON uc.id = c.client_id
JOIN location l ON l.id = c.location_id
JOIN users up ON up.id = l.prop_id";
$where = "";

if (isset($_GET['type'], $_GET['search']) && $_GET['search'] !== "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    if ($_GET['type'] == "cin_client") {
        $where = " WHERE uc.cin LIKE '%$search%'";
    } elseif ($_GET['type'] == "cin_prop") {
        $where = " WHERE up.cin LIKE '%$search%'";
    } elseif ($_GET['type'] == "contrat") {
        $where = " WHERE c.id = '$search'";
    }
}

$sqlContrat = "
SELECT 
    c.id AS contrat_id,
    c.date_cont,
    c.durée_cont,
    uc.nom AS nom_client,
    uc.prenom AS prenom_client,
    up.nom AS nom_prop,
    up.prenom AS prenom_prop
FROM contrat c
JOIN users uc ON uc.id = c.client_id
JOIN location l ON l.id = c.location_id
JOIN users up ON up.id = l.prop_id
$where
ORDER BY c.date_cont DESC
";

$resContrat = mysqli_query($conn, $sqlContrat);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Listes des contrats</title>
    <link rel="stylesheet" href="style.css">
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
                    echo "<li><a href='./reservations.php'>Réservations</a></li>";
                }
                else{
                    echo "<li><a href='./index.php'>Accueil</a></li>";
                    if($info["role"] == "admin"){
                        echo "<li><a href='./users.php'>Utilisateurs</a></li>";
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
</li>        </ul>
    </aside>
        <main class="content">
<div class="head">
                <h2>Listes des contrats</h2>
                <div class="btn-add">
                    <a href="./add-contrat.php">Ajoute contrats
                    </a>
                </div>
            </div>
<form method="GET" style="margin-bottom:15px; display:flex; gap:10px;">
    <select name="type" required>
        <option value="">-- Rechercher par --</option>
        <option value="cin_client" <?= ($_GET['type'] ?? '') == 'cin_client' ? 'selected' : '' ?>>CIN Client</option>
        <option value="cin_prop" <?= ($_GET['type'] ?? '') == 'cin_prop' ? 'selected' : '' ?>>CIN Propriétaire</option>
        <option value="contrat" <?= ($_GET['type'] ?? '') == 'contrat' ? 'selected' : '' ?>>Numéro Contrat</option>
    </select>

    <input 
        type="text" 
        name="search" 
        placeholder="Tapez ici..." 
        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
        required
    >

    <button type="submit" class="btn-add">Rechercher</button>
</form>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Propriétaire</th>
            <th>Date contrat</th>
            <th>Durée</th>
            <th>Télécharger</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($resContrat) > 0): ?>
            <?php $i = 1 ; while ($row = mysqli_fetch_assoc($resContrat)): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $row['prenom_client'] . ' ' . $row['nom_client'] ?></td>
                    <td><?= $row['prenom_prop'] . ' ' . $row['nom_prop'] ?></td>
                    <td><?= date('d/m/Y', strtotime($row['date_cont'])) ?></td>
                    <td><?= $row['durée_cont'] ?></td>
                    <td>
                        <a class="btn" href="download_contrat.php?id=<?= $row['contrat_id'] ?>">
                            Télécharger
                        </a>
                    </td>
                </tr>
            <?php endwhile; $i++; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Aucun contrat trouvé</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</main>
<script src="./main.js"></script>
</body>
</html>
