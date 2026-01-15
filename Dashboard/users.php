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
    if($role == "admin"){
        $sqlClient = "SELECT * FROM users";
    }
    else{
        $sqlClient = "SELECT * FROM users WHERE role = 'proprietaire' ";
    }
    $resClients = mysqli_query($conn,$sqlClient);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une propriété</title>
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
                    echo "<li><a href='./me-reservations'>Réservations</a></li>";
                }
                else{
                    echo "<li><a href='#'>Accueil</a></li>";
                    if($info["role"] == "admin"){
                        echo "<li><a href='./users'>utilisteurs</a></li>";
                    }
                    else{
                        echo "
                        <li><a href='./users'>proprietaires</a></li>
                        ";
                    }
                    echo "
                        <li><a href='./reservations'>Réservations</a></li>
                        <li><a href='./listContrat.php'>Contrat</a></li>
                        <li><a href='./localion.php'>Location</a></li>
                    ";
                }
            ?>
            <li><a href="#">Paramètres</a></li>
        </ul>
    </aside>
        <main class="content">
            <div class="head">
                <h2>Liste des <?php if($role == "admin") : echo "utiliteurs" ; else : echo "prop" ; endif; ?></h2>
                <div class="btn-add">
                    <a href="./add-user.php">Ajoute
                        <?php
                        if($role == "admin") : echo "utiliteur" ; else : echo "prop" ; endif; ?>
                    </a>
                </div>
            </div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Cin</th>
            <th>Prénom Nom</th>
            <th>Email</th>
            <th>Numéro Télephone</th>
            <?php if ($role == "admin"): ?>
                <th>Role</th>
            <?php endif; ?>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($resClients) > 0): ?>
            <?php $i = 1 ; while ($row = mysqli_fetch_assoc($resClients)): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $row['cin'] ?></td>
                    <td><?= $row['prenom'] . ' ' . $row['nom'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <?php if ($role == "admin"): ?>
                    <th><?=  $row['role'] ?></th>
                    <?php endif; ?>
                    <td><?= $row['num_tel'] ?></td>
                    <td>
                        <?php if ($userInfo["email"] != $row['email']): ?>
                            <a class="btn" href="?delete=<?= $row['id'] ?>"  ?>
                            Supprimer
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php $i++; endwhile; ?>
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
