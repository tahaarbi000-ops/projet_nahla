<?php
require_once("../connect.php");
session_start();
if(!isset($_SESSION["info"])){
    header("location:../login.php");
    exit;
}

$userInfo = $_SESSION["info"];
$role = $userInfo["role"];

if(!isset($role) == "client"){
    header("location:../index.php");
    exit;
}


if(isset($_GET['delete'])){
    $id_loc = $_GET["delete"];
    $sqlDelete = "UPDATE location SET etat_loc = 'supprimé' WHERE id = $id_loc";
    echo $sqlDelete;
    $resDelete = mysqli_query($conn,$sqlDelete);
    header("location:./locations.php");
    exit;

}

// إذا مش admin، نعرض فقط locations الخاصة بالprop
if($role == "admin"){
    $sqlLocations = "SELECT l.*, u.nom AS prop_nom, u.prenom AS prop_prenom 
                     FROM location l 
                     JOIN users u ON u.id = l.prop_id";
} else {
    $sqlLocations = "SELECT l.*, u.nom AS prop_nom, u.prenom AS prop_prenom 
                     FROM location l 
                     JOIN users u ON u.id = l.prop_id
                     WHERE l.prop_id = {$userInfo['id']}";
}

$resLocations = mysqli_query($conn, $sqlLocations);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Locations</title>
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
        .disabled{
    pointer-events: none;
    opacity: 0.5;
    cursor: not-allowed;
}

    </style>
</head>
<body>
<header class="topbar">
    <div class="top-left">
        <a href="../index.php">
            <h2>LUXELOC</h2>
        </a>
    </div>
    <div class="top-right">
        <div class="user-menu" onclick="toggleDropdown()">
            <?php
            $sql = "SELECT nom,prenom FROM users WHERE email = '" . $userInfo["email"] ."'";
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

<div class="container">
    <aside class="sidebar">
        <ul>
            <li><a href="./index.php">Accueil</a></li>
            <?php if($role == "admin"): ?>
                <li><a href="./users.php">Utilisateurs</a></li>
            <?php else: ?>
                <li><a href="./users.php">Propriétaires</a></li>
            <?php endif; ?>
            <li><a href="./reservations.php">Réservations</a></li>
            <li><a href="./contrats.php">Contrat</a></li>
            <li><a href="./locations.php">Location</a></li>
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
        <div class="head">
            <h2>Liste des Locations</h2>
            <div class="btn-add">
                <a href="./add-location.php">Ajouter Location</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>État</th>
                    <th>Type</th>
                    <th>Adresse</th>
                    <th>Prix</th>
                    <th>Classification</th>
                    <th>Gouvernorat</th>
                    <th>Propriétaire</th>
                    <th>Créé le</th>
                    <th>Disponibilité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($resLocations) > 0): ?>
                    <?php $i = 1; while($row = mysqli_fetch_assoc($resLocations)): ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= $row['etat'] ?></td>
                            <td><?= $row['type'] ?></td>
                            <td><?= htmlspecialchars($row['adresse']) ?></td>
                            <td><?= $row['prix'] ?> TND</td>
                            <td><?= $row['classification'] ?></td>
                            <td><?= $row['gouvernorat'] ?></td>
                            <td><?= $row['prop_prenom'] . ' ' . $row['prop_nom'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td><?= $row['etat_loc'] ?></td>
                            <td style="
                                    display: flex;
                                    flex-direction: column;
                                    ">
                                <a class="btn" style="background: #007bff;" href="edit-location.php?id=<?= $row['id'] ?>">Modifier</a> <br>
<a class="btn <?= $row['etat_loc'] == 'supprimé' ? 'disabled' : '' ?>"
   href="<?= $row['etat_loc'] == 'supprimé' ? '#' : '?delete=' . $row['id'] ?>">
   Supprimer
</a>
                            </td>
                        </tr>
                    <?php $i++; endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">Aucune location trouvée</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </main>
</div>
<script src="./main.js"></script>
</body>
</html>
