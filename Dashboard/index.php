<?php
require_once("../connect.php");
session_start();
if(!isset($_SESSION["info"])){
        header("location:../login.php");
    }
    // Count users
$sqlUsers = "SELECT COUNT(*) as total FROM users";
$resUsers = mysqli_query($conn, $sqlUsers);
$totalUsers = mysqli_fetch_assoc($resUsers)['total'];

// Count agents
$sqlAgents = "SELECT COUNT(*) as total FROM users WHERE role='agent'";
$resAgents = mysqli_query($conn, $sqlAgents);
$totalAgents = mysqli_fetch_assoc($resAgents)['total'];

// Count proprietaires
$sqlProps = "SELECT COUNT(*) as total FROM users WHERE role='Propriétaire'";
$resProps = mysqli_query($conn, $sqlProps);
$totalProps = mysqli_fetch_assoc($resProps)['total'];

// Count locations
$sqlLocations = "SELECT COUNT(*) as total FROM location";
$resLocations = mysqli_query($conn, $sqlLocations);
$totalLocations = mysqli_fetch_assoc($resLocations)['total'];


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <style>
        .stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.card h3 {
    margin-bottom: 10px;
    font-size: 18px;
}

.card p {
    font-size: 28px;
    font-weight: bold;
}

.chart-box {
    margin-top: 40px;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
}

    </style>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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
                        echo "<li><a href='./users.php'>Utilisateurs</a></li>";
                    }
                    else{
                        echo "
                        <li><a href='./users.php'>propriétaire</a></li>
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

    <!-- MAIN -->
    <main class="content">
    <h2>Voici votre tableau de bord</h2>

    <div class="stats">
        <div class="card">
            <h3>Utilisateurs</h3>
            <p><?= $totalUsers ?></p>
        </div>

        <div class="card">
            <h3>Agents</h3>
            <p><?= $totalAgents ?></p>
        </div>

        <div class="card">
            <h3>Propriétaires</h3>
            <p><?= $totalProps ?></p>
        </div>

        <div class="card">
            <h3>Locations</h3>
            <p><?= $totalLocations ?></p>
        </div>
    </div>

    <div class="chart-box">
        <canvas id="dashboardChart"></canvas>
    </div>
</main>


</div>

<script src="main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('dashboardChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Utilisateurs', 'Agents', 'Propriétaires', 'Locations'],
        datasets: [{
            label: 'Statistiques',
            data: [
                <?= $totalUsers ?>,
                <?= $totalAgents ?>,
                <?= $totalProps ?>,
                <?= $totalLocations ?>
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</body>
</html>
