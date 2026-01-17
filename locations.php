<?php
    require_once("./connect.php");
    session_start();
    if(isset($_SESSION["info"])){
        if(isset($_GET["logout"])){
            session_unset();
            session_destroy();
            header("location:./index.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Premium - Maisons de Luxe</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --secondary: #1e40af;
            --dark: #1e293b;
            --light: #f1f5f9;
            --accent: #0ea5e9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: white;
            overflow-x: hidden;
        }

        .grain {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.03;
            z-index: 1;
            background-image: url('data:image/svg+xml,<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><filter id="noiseFilter"><feTurbulence type="fractalNoise" baseFrequency="3.5" numOctaves="4" stitchTiles="stitch"/></filter><rect width="100%" height="100%" filter="url(%23noiseFilter)"/></svg>');
        }

        header {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            padding: 1.5rem 0;
            backdrop-filter: blur(20px);
            background: rgba(15, 23, 42, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }


        nav {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 3rem;
            gap: 2rem;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 3rem;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 900;
            color: #2563eb;
        }
        /* FILTER BUTTON */
.filter-toggle {
    position: fixed;
    left: 20px;
    top: 104px;
    transform: translateY(-50%);
    background: #2563eb;
    color: white;
    border: none;
    padding: 0.8rem 1.2rem;
    border-radius: 30px;
    cursor: pointer;
    font-weight: 600;
    z-index: 1200;
}

/* OVERLAY */
.filter-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(5px);
    display: none;
    z-index: 1100;
}

/* DRAWER */
.filter-drawer {
    position: fixed;
    top: 0;
    left: -350px;
    width: 320px;
    height: 100%;
    background: #0f172a;
    padding: 2rem;
    z-index: 1200;
    transition: left 0.4s ease;
    overflow-y: auto;
}

/* ACTIVE */
.filter-drawer.active {
    left: 0;
}

.filter-overlay.active {
    display: block;
}

/* HEADER */
.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.filter-header button {
    background: transparent;
    border: none;
    color: white;
    font-size: 1.3rem;
    cursor: pointer;
}

/* FORM */
.filter-drawer label {
    display: block;
    margin-top: 1rem;
    margin-bottom: 0.4rem;
    color: rgba(255,255,255,0.8);
}

.filter-drawer input,
.filter-drawer select {
    width: 100%;
    padding: 0.7rem;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.15);
    background: rgba(255,255,255,0.08);
    color: white;
}

.filter-drawer select option {
    background: #0f172a;
}

.btn-filter {
    width: 100%;
    margin-top: 1.5rem;
    padding: 0.8rem;
    background: #2563eb;
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
}

.btn-reset {
    display: block;
    text-align: center;
    margin-top: 0.7rem;
    color: #94a3b8;
    text-decoration: none;
}


        nav ul {
            display: flex;
            list-style: none;
            gap: 3rem;
        }

        nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            transition: color 0.3s;
        }

        nav a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #2563eb;
            transition: width 0.3s;
        }

        nav a:hover {
            color: white;
        }

        nav a:hover::after {
            width: 100%;
        }
        .user-menu {
  position: relative;
  cursor: pointer;
}

.user-name {
  font-weight: 600;
}

.dropdown {
  display: none;
  position: absolute;
  right: 0;
  top: 120%;
  background: #0f172a;
  border-radius: 10px;
  min-width: 180px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  z-index: 10;
}

/* Dropdown links */
.dropdown a {
  display: block;
  padding: 0.6rem 1rem;
  color: white;
  text-decoration: none;
}

.dropdown a:hover {
  background: rgba(37, 99, 235, 0.2);
}

.logout {
  color: #ef4444;
}

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 0 3rem;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(37, 99, 235, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(14, 165, 233, 0.3) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 1200px;
        }

        .hero-content h1 {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            color: white;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-content p {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .search-container {
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            gap: 1rem;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .search-box input,
        .search-box select {
            flex: 1;
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
        }

        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .search-box input:focus,
        .search-box select:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--primary);
        }

        .search-box select {
            cursor: pointer;
        }

        .search-box select option {
            background: var(--dark);
            color: white;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(37, 99, 235, 0.4);
        }

        .properties {
            max-width: 1400px;
            margin: 8rem auto;
            padding: 0 3rem;
            position: relative;
            z-index: 2;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            margin-bottom: 1rem;
            color: white;
        }

        .section-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.2rem;
        }

        .property-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .property-grid a{
            text-decoration: none;
        }

        .property-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
        }

        .property-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(37, 99, 235, 0.1);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .property-card:hover {
            transform: translateY(-15px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .property-card:hover::before {
            opacity: 1;
        }

        .property-image {
            width: 100%;
            height: 350px;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            position: relative;
            overflow: hidden;
        }

        .property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;   /* fill container without distortion */
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
}


        .property-image::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .property-info {
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .property-tag {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: rgba(37, 99, 235, 0.2);
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #60a5fa;
            margin-bottom: 1rem;
            border: 1px solid rgba(37, 99, 235, 0.3);
        }

        .property-info h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .property-details {
            display: flex;
            gap: 1.5rem;
            margin: 1rem 0;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
        }

        .property-details span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .price {
            font-size: 2rem;
            font-weight: 900;
            color: #2563eb;
            margin-top: 1rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 6rem auto;
            padding: 0 3rem;
        }

        .stat-card {
            text-align: center;
            padding: 3rem 2rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.1rem;
        }

        footer {
            background: rgba(255, 255, 255, 0.03);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem 3rem 2rem;
            margin-top: 8rem;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: white;
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #60a5fa;
        }

        .btn-search {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-search:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-signin {
            background: transparent;
            border: none;
            color: white;
            padding: 0.6rem 1.2rem;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-signin:hover {
            color: #60a5fa;
        }

        .btn-signup {
            background: #2563eb;
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-signup:hover {
            background: #1e40af;
            transform: translateY(-1px);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 3rem;
            max-width: 450px;
            width: 90%;
            position: relative;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: white;
        }

        .modal-header {
            margin-bottom: 2rem;
        }

        .modal-header h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: white;
        }

        .modal-header p {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.9rem 1.2rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: #2563eb;
        }

        .btn-submit {
            width: 100%;
            background: #2563eb;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: #1e40af;
            transform: translateY(-2px);
        }

        .modal-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .modal-footer a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .modal-footer a:hover {
            color: #2563eb;
        }

        .search-modal-content {
            max-width: 600px;
        }

        .search-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 968px) {
            .search-box {
                flex-direction: column;
            }

            nav ul {
                gap: 1.5rem;
            }

            .property-grid {
                grid-template-columns: 1fr;
            }

            .nav-left {
                gap: 1.5rem;
            }

            nav ul {
                display: none;
            }

            .nav-right {
                gap: 0.5rem;
            }

            .btn-search span,
            .btn-signin {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="grain"></div>
    
    <header>
        <nav>
            <div class="nav-left">
                <div class="logo">LUXELOC</div>
                <ul>
                    <li><a href="./index.php">Accueil</a></li>
                    <li><a href="./locations.php">Locations</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="nav-right">
            <?php
            if(isset($_SESSION["info"])){
                $info = $_SESSION["info"];
                $email = $info["email"];
                $sql = "SELECT nom,prenom,role FROM users WHERE email = '$email'";
                $response = mysqli_query($conn,$sql);
                $user = mysqli_fetch_assoc($response);
                echo "
                    <div class='user-menu' id='userMenu'>
                    <span class='user-name'>" . $user["nom"] . " " . $user["prenom"] . " </span>
                    <div class='dropdown' id='userDropdown'>
                        <a href='./profile.php'>Paramètres</a>
                        <a href='./client/dashboard.php'>Mon espace</a>
                        <a href='?logout' class='logout'>Déconnexion</a>
                    </div>
                </div>
                ";
            }
            else{
                echo "
                <a href='./login.php'>Se connecter</a>
                <a href='./signup.php' class='btn-signup'>S'inscrire</a>
                ";
            }
            ?>
            
            </div>
        </nav>
    </header>
    <button id="openFilter" class="filter-toggle">
    🔍 Filtres
</button>


    <!-- FILTER OVERLAY -->
<div class="filter-overlay" id="filterOverlay"></div>

<!-- FILTER SIDEBAR -->
<aside class="filter-drawer" id="filterDrawer">
    <div class="filter-header">
        <h3>Filtres</h3>
        <button id="closeFilter">✖</button>
    </div>

    <form method="GET" id="filterForm">
        <label>Gouvernorat</label>
        <select name="gouvernorat">
            <option value="">Tous</option>
            <option value="Tunis">Tunis</option>
            <option value="Sousse">Sousse</option>
            <option value="Monastir">Monastir</option>
            <option value="Sfax">Sfax</option>
        </select>

        <label>Type</label>
        <select name="type">
            <option value="">Tous</option>
            <option value="villa">Villa</option>
            <option value="appartement">Appartement</option>
            <option value="studio">Studio</option>
        </select>

        <label>Prix min</label>
        <input type="number" name="min">

        <label>Prix max</label>
        <input type="number" name="max">

        <button type="submit" class="btn-filter">Appliquer</button>
        <a href="?" class="btn-reset">Réinitialiser</a>
    </form>
</aside>


    <section style="margin-top: 150px;" class="properties" id="proprietes">
        <div class="property-grid" id="propertyGrid">
            <?php
            
           $sql = "
SELECT 
    l.id,
    l.type,
    l.adresse,
    l.gouvernorat,
    l.prix,
    l.classification,
    MIN(i.img_url) AS img_url,
    l.description
FROM location l
JOIN images i ON l.id = i.location_id
WHERE 1=1 AND etat = 'libre'
AND etat_loc = 'actif'
";

/* ---- Filters ---- */

if (!empty($_GET["gouvernorat"])) {
    $gouv = mysqli_real_escape_string($conn, $_GET["gouvernorat"]);
    $sql .= " AND l.gouvernorat = '$gouv'";
}

if (!empty($_GET["type"])) {
    $type = mysqli_real_escape_string($conn, $_GET["type"]);
    $sql .= " AND l.type = '$type'";
}

if (!empty($_GET["min"])) {
    $min = (int) $_GET["min"];
    $sql .= " AND l.prix >= $min";
}

if (!empty($_GET["max"])) {
    $max = (int) $_GET["max"];
    $sql .= " AND l.prix <= $max";
}

/* ---- Group ---- */
$sql .= " GROUP BY l.id";

            $res = mysqli_query($conn, $sql);

if (mysqli_num_rows($res) > 0) {

    while ($ligne = mysqli_fetch_assoc($res)) {
        echo "
        <a href='location.php?id={$ligne['id']}' class='property-card'>
            <div class='property-image'>
                <img src='{$ligne['img_url']}' alt='image'>
            </div>
            <div class='property-info'>
                <span class='property-tag'>{$ligne['type']}</span>
                <h3>{$ligne['adresse']}</h3>
                <div class='property-details'>
                    <span>📍 {$ligne['gouvernorat']}</span>
                </div>
                <p class='price'>{$ligne['prix']} TND / mois</p>
            </div>
        </a>
        ";
    }

} else {

    echo "
    <div style='
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
        color: rgba(255,255,255,0.7);
        font-size: 1.4rem;
    '>
        <strong>Aucun résultat trouvé</strong><br>
        <span style='font-size:1rem; opacity:.7;'>
            Essayez de modifier vos critères de recherche
        </span>
    </div>
    ";
}



            ?>
    
</a>
    </section>
    <script>

        const userMenu = document.getElementById("userMenu");
const userDropdown = document.getElementById("userDropdown");

userMenu.addEventListener("click", (e) => {
  e.stopPropagation(); // لمنع إغلاقه فوراً عند الضغط داخله
  userDropdown.style.display = userDropdown.style.display === "block" ? "none" : "block";
});

// Close dropdown عند الضغط في أي مكان خارج
document.addEventListener("click", () => {
  userDropdown.style.display = "none";
});

const openBtn = document.getElementById("openFilter");
const closeBtn = document.getElementById("closeFilter");
const drawer = document.getElementById("filterDrawer");
const overlay = document.getElementById("filterOverlay");

openBtn.onclick = () => {
    drawer.classList.add("active");
    overlay.classList.add("active");
};

closeBtn.onclick = closeFilter;
overlay.onclick = closeFilter;

function closeFilter() {
    drawer.classList.remove("active");
    overlay.classList.remove("active");
}
document.getElementById("filterForm").addEventListener("submit", function () {
    const fields = this.querySelectorAll("input, select");

    fields.forEach(field => {
        if (field.value.trim() === "") {
            field.removeAttribute("name");
        }
    });
});




    </script>
</body>
</html>