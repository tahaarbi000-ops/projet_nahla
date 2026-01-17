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
    if(isset($_GET["annuler"])){
            $idRes = $_GET["annuler"];
            $sqlModifi = "UPDATE reservation SET etat = 'annuler' ";
            $resAnnuler = mysqli_query($conn,$sqlAnnuler);
            header("location:./me-reservations.php");

        }
    if(isset($_GET["annuler"])){
            $idRes = $_GET["annuler"];
            $sqlModifi = "UPDATE reservation SET etat = 'annuler' ";
            $resModifi = mysqli_query($conn,$sqlModifi);
            header("location:./reservations.php");

        }
    if(isset($_GET["paye"])){
            $idRes = $_GET["paye"];
            $sqlModifi = "UPDATE reservation SET is_payed = 1 WHERE id = $idRes ";
            $resModifi = mysqli_query($conn,$sqlModifi);
            header("location:./reservations.php");
        }
    if(isset($_GET["accept"])){
            $idRes = $_GET["accept"];
            $sqlModifi = "UPDATE reservation SET etat = 'accepté' ";
            $resModifi = mysqli_query($conn,$sqlModifi);
            header("location:./reservations.php");
        }
    if (isset($_GET["presence"])) {
    $idRes = intval($_GET["presence"]); // Always sanitize input
    $sqlPre = "SELECT présence_prop FROM reservation WHERE id = $idRes";
    $resPre = mysqli_query($conn, $sqlPre);
    $result = mysqli_fetch_assoc($resPre);

    if ($result) {
        $newValue = $result["présence_prop"] == 1 ? 0 : 1;
        $sqlModifi = "UPDATE reservation SET présence_prop = $newValue WHERE id = $idRes";
        mysqli_query($conn, $sqlModifi);
    }

    header("Location: ./reservations.php");
    exit();
}

    $userInfo = $_SESSION["info"];
    $role = $userInfo["role"];
    if($role == "client"){
        header("location:../index.php");
    }
    $sqlRes = "SELECT r.id,adresse,r.etat,présence_prop,is_payed,heure_res,date_res FROM reservation r , location l  WHERE l.id = r.location_id";
    $resRes = mysqli_query($conn,$sqlRes);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des réservations</title>
    <link rel="stylesheet" href="style.css">
    <style>
        a{
            text-decoration: none;
        }
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
            border: none;
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
            border: none;
            margin: 3px;
            cursor: pointer;
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
                        echo "<li><a href='./users.php'>Utilisateurs</a></li>";
                    }
                    else{
                        echo "
                        <li><a href='./users.php'>propriétaires</a></li>
                        ";
                    }
                    echo "
                        <li><a href='./reservations'>Réservations</a></li>
                        <li><a href='./Contrats.php'>Contrat</a></li>
                        <li><a href='./locations.php'>Locations</a></li>
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
                <h2>Liste des réservations</h2>
            </div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Heure</th>
            <th>État</th>
            <th>Payé</th>
            <th>Présence Propriétaire</th>
            <th>adresse</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($resRes) > 0): ?>
            <?php $i = 1 ; while ($row = mysqli_fetch_assoc($resRes)): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $row['date_res'] ?></td>
                    <td><?= $row['heure_res']?></td>
                    <td><?= $row['etat'] ?></td>
                    <td><?= $row['is_payed'] ?></td>
                    <td><?= $row['présence_prop'] ?></td>
                    <td><?= $row['adresse'] ?></td>
                    <td>
    <?php 
        $today = date('Y-m-d'); // تاريخ اليوم
        $resDate = $row['date_res']; // تاريخ الحجز
        $disabled = ($today >= $resDate || $row["etat"] == 'annuler' || $row["etat"] == 'refusé' ) ? "disabled" : "";
        
    ?>
    <form method="get">
    <button class="btn" name="refuse" value="<?= $row['id'] ?>" <?= $disabled ?>>
        refusé
    </button>
    <button class="btn" style="background: green;" name="accept" value="<?= $row['id'] ?>" <?= $disabled ?>>
        accepté
    </button>
    <button style="background: #f2f01f;" class="btn" name="paye" value="<?= $row['id'] ?>">
        payé
    </button>
    <button style="background: #007bff;" class="btn" name="presence" value="<?= $row['id'] ?>">
        <?= $row['présence_prop'] == 0 ? 'Présent' : 'Absent' ?>

    </button>
    </form>
</td>

                </tr>
            <?php $i++; endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Aucun contrat trouvé</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</main>
<script src="./main.js"></script>
</body>
</html>
