<?php
    require_once("./connect.php");
    session_start();
    if(isset($_GET["id"])){
    $id = intval($_GET["id"]);
    if(isset($_SESSION["info"])){
                $info = $_SESSION["info"];
                $email = $info["email"];
                $sql = "SELECT nom,prenom,role,id FROM users WHERE email = '$email'";
                $response = mysqli_query($conn,$sql);
                $user = mysqli_fetch_assoc($response);
        if(isset($_POST["btn"])){
                    $time = $_POST["time"];
                    $date = $_POST["date"];
                    $sqlRev = "INSERT INTO reservation (date_res,heure_res,client_id,location_id,is_payed,présence_prop) VALUES('$date','$time'," . $user["id"] .",$id,0,1)";
                    $resRev = mysqli_query($conn,$sqlRev);
                    header("location:./index.php");
                    }
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
            height: 250px;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            position: relative;
            overflow: hidden;
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
        /* Section spécifique pour la location detail */
.properties {
    max-width: 900px;
    margin: 8rem auto;
    padding: 0 2rem;
}

.property-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.4s;
    cursor: default;
    margin-bottom: 3rem;
}

.property-image {
    width: 100%;
    height: 300px;
    background: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 5rem;
    color: white;
    position: relative;
    overflow: hidden;
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

.property-info {
    padding: 2rem;
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
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.property-info p {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.8);
    margin-bottom: 1rem;
}

.property-details {
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
    color: rgba(255,255,255,0.7);
}

.property-details span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
}
.reserver-content{
    width: 100%;
    display: flex;
    justify-content: center;
    padding: 5px;
}
.reserver {
    width:200px;
    text-align: center;
    cursor: pointer;
}
        .modal{
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

    </style>
</head>
<body>
    <div class="grain"></div>
    
    <header>
        <nav>
            <div class="nav-left">
                <div class="logo">LUXELOC</div>
                <ul>
                    <li><a href="#accueil">Accueil</a></li>
                    <li><a href="#proprietes">Propriétés</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="nav-right">
            <?php
            if(isset($_SESSION["info"])){
                echo "
                    <div class='user-menu' id='userMenu'>
                    <span class='user-name'>👤 " . $user["nom"] . " " . $user["prenom"] . " </span>
                    <div class='dropdown' id='userDropdown'>
                        <a href='./profile.php'>⚙️ Paramètres</a>
                        <a href='./client/dashboard.php'>📊 Mon espace</a>
                        <a href='?logout' class='logout'>🚪 Déconnexion</a>
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

    <section class="properties" id="proprietes">
<?php

    $sql = "SELECT 
                l.id, l.description, l.type, l.prix, l.adresse, l.classification, l.created_at, 
                u.nom AS owner_nom, u.prenom AS owner_prenom, u.num_tel AS owner_phone 
            FROM location AS l
            JOIN users AS u ON u.id = l.prop_id
            WHERE l.id = $id";
    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result) > 0){
        $location = mysqli_fetch_assoc($result);
        echo "
        <div class='property-card'>
            <div class='property-image'>
                🏠
            </div>
            <div class='property-info'>
                <span class='property-tag'>{$location['type']}</span>
                <h3>{$location['adresse']}</h3>
                <p>{$location['description']}</p>
                <div class='property-details'>
                    <span>💰 Prix: {$location['prix']} TND</span>
                    <span>⭐ Classification: {$location['classification']}</span>
                    <span>📅 Date: {$location['created_at']}</span>
                </div>
                <div class='property-details'>
                    <span>👤 Propriétaire: {$location['owner_nom']} {$location['owner_prenom']}</span>
                    <span>📞 Téléphone: {$location['owner_phone']}</span>
                </div>
            </div>
        ";
        if(isset($_SESSION["info"])){
            echo "<div class='reserver-content'>
            <div class='reserver btn-primary'>
                <span>Réserver</span>
            </div></div>";
        }
        echo "</div>";
    } else {
        echo "<p style='color:white; text-align:center;'>Aucune location trouvée pour cet ID.</p>";
    }
} else {
    echo "<p style='color:white; text-align:center;'>ID de location non spécifié.</p>";
}
?>
</section>

    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h4>LUXELOC</h4>
                <p>Votre partenaire de confiance pour la location de propriétés d'exception.</p>
            </div>
            <div class="footer-section">
                <h4>Navigation</h4>
                <a href="#accueil">Accueil</a>
                <a href="#proprietes">Nos Propriétés</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p>📧 contact@luxeloc.com</p>
                <p>📱 +216 70 XXX XXX</p>
                <p>📍 Tunis, Tunisie</p>
            </div>
            <div class="footer-section">
                <h4>Suivez-nous</h4>
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">LinkedIn</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 LUXELOC. Tous droits réservés.</p>
        </div>
    </footer>

    </div>

</div>

<div id="signinModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('signinModal')">&times;</button>
            <div class="modal-header">
                <h2>Réservation</h2>
                <p>Faire Réservation </p>
            </div>
            <form method="post" onsubmit="handleSignIn(event)">
                <div class="form-group">
                    <label>Jour</label>
                    <input type="date" name="date" id="date"/>
                </div>
                <div class="form-group">
                    <label>Temps</label>
                    <input type="time"min="08:00" max="15:00" name="time"/>
                    <span>temps de Réservation doit etre entre 8 et 15</span>
                </div>
                <button type="submit" name="btn" class="btn-submit">Réserver</button>
                
            </form>
        </div>
    </div>


    <script>

         function openSignInModal() {
            document.getElementById('signinModal').classList.add('active');
        }
    const reserverBtn = document.querySelector(".reserver");
        reserverBtn.addEventListener("click", openSignInModal);

        const userMenu = document.getElementById("userMenu");
const userDropdown = document.getElementById("userDropdown");

userMenu.addEventListener("click", (e) => {
  e.stopPropagation(); // لمنع إغلاقه فوراً عند الضغط داخله
  userDropdown.style.display = userDropdown.style.display === "block" ? "none" : "block";
});

const dateInput = document.getElementById("date");

    const today = new Date();
    today.setDate(today.getDate() + 1); // tomorrow

    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');

    dateInput.min = `${yyyy}-${mm}-${dd}`;

// Close dropdown عند الضغط في أي مكان خارج
document.addEventListener("click", () => {
  userDropdown.style.display = "none";
});
    </script>
</body>
</html>