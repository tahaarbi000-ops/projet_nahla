<?php
require_once("../connect.php");
session_start();

if(!isset($_SESSION["info"])){
    header("location:../login.php");
    exit;
}

if(!isset($_GET['id'])){
    die("Location invalide");
}

$id = intval($_GET['id']);

// جلب بيانات الـ location
$sql = "SELECT * FROM location WHERE id = $id";
$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) == 0){
    die("Location non trouvée");
}

$location = mysqli_fetch_assoc($res);

// تحديث البيانات
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $description = $_POST['description'];
    $etat = $_POST['etat'];
    $type = $_POST['type'];
    $adresse = $_POST['adresse'];
    $prix = $_POST['prix'];
    $classification = $_POST['classification'];

    $update = "
        UPDATE locations SET
            description = '$description',
            etat = '$etat',
            type = '$type',
            adresse = '$adresse',
            prix = '$prix',
            classification = '$classification'
        WHERE id = $id
    ";

    if(mysqli_query($conn, $update)){
        header("location:locations.php?success=1");
        exit;
    } else {
        $error = "Erreur lors de la modification";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier location</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
    <div class="top-left">
        <h2>LUXELOC</h2>
    </div>

    <div class="top-right">
        <div class="user-menu">
            <?= $_SESSION["info"]["email"] ?>
        </div>
    </div>
</header>

<div class="container">

<!-- SIDEBAR -->
<aside class="sidebar">
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="locations.php">Locations</a></li>

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
<h2>Modifier la location</h2>

<?php if(isset($error)): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="POST" class="property-form">

    <label>Description</label>
    <textarea name="description" required><?= $location['description'] ?></textarea>

    <label>État</label>
    <select name="etat" required>
        <option value="libre" <?= $location['etat']=="libre"?"selected":"" ?>>Libre</option>
        <option value="occupé" <?= $location['etat']=="occupé"?"selected":"" ?>>Occupé</option>
    </select>

    <label>Type</label>
    <select name="type" required>
        <option value="villa" <?= $location['type']=="villa"?"selected":"" ?>>Villa</option>
        <option value="appartement" <?= $location['type']=="appartement"?"selected":"" ?>>Appartement</option>
    </select>

    <label>Adresse</label>
    <input type="text" name="adresse" value="<?= $location['adresse'] ?>" required>

    <label>Prix (DT)</label>
    <input type="number" step="0.01" name="prix" value="<?= $location['prix'] ?>" required>

    <label>Classification</label>
    <input type="text" name="classification" value="<?= $location['classification'] ?>">

    <button type="submit">Enregistrer</button>
    <a href="locations.php" class="btn">Retour</a>

</form>
</main>

</div>
<script src="./main.js"></script>
</body>
</html>
