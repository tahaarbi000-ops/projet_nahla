<?php
require_once("../connect.php");
session_start();
if(!isset($_SESSION["info"])){
        header("location:../login.php");
    }
    $sqlContrat = "SELECT c.id AS contrat_id, c.date_cont, c.durée_cont, uc.nom AS nom_client, uc.prenom AS prenom_client, up.nom AS nom_prop, up.prenom AS prenom_prop FROM contrat c JOIN users uc ON uc.id = c.client_id JOIN location l ON l.id = c.location_id JOIN users up ON up.id = l.prop_id;";
    $resContrat = mysqli_query($conn,$sqlContrat);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une propriété</title>
    <link rel="stylesheet" href="style.css">
    <style>
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
            background: #007bff;
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
<h2>Liste des contrats</h2>

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
