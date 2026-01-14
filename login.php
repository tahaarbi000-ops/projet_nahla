<?php
    require_once("./connect.php");
    session_start();
    if(isset($_SESSION["info"])){
        header("location:./index.php");
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
            width: 100%;
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

        #signupForm{
            margin-top:20px;
            display:flex;
            width: 100%;
            justify-content:center;
            flex-direction:column;
            align-items:center;
        }
        .form-group {
            margin-bottom: 1.3rem;
        }
        .groups{
            width: 50%;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group label .required {
            color: #ef4444;
        }

        .form-group input {
            width: 100%;
            padding: 0.9rem 1.2rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-group input:valid {
            border-color: rgba(34, 197, 94, 0.5);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .strength-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: #ef4444;
            transition: all 0.3s;
        }

        .strength-fill.weak {
            width: 33%;
            background: #ef4444;
        }

        .strength-fill.medium {
            width: 66%;
            background: #f59e0b;
        }

        .strength-fill.strong {
            width: 100%;
            background: #22c55e;
        }

        .checkbox-group {
            margin: 1.5rem 0;
        }

        .checkbox-group label {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            cursor: pointer;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #2563eb;
        }

        .checkbox-group a {
            color: #60a5fa;
            text-decoration: none;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            background: #2563eb;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-submit:hover:not(:disabled) {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .divider span {
            padding: 0 1rem;
        }

        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-social {
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: white;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-social:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .footer-text a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            display: none;
        }

        .form-group input.error {
            border-color: #ef4444;
        }

        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: none;
        }

        @media (max-width: 640px) {
            .signup-container {
                padding: 2rem 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .social-buttons {
                grid-template-columns: 1fr;
            }
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
                    <li><a href="#accueil">Accueil</a></li>
                    <li><a href="#proprietes">Propriétés</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="nav-right">
            
                <a href="./login.php" class="btn-signin">Se connecter</a>
                <a href="./signup.php" class="btn-signup">S'inscrire</a>
            </div>
        </nav>
    </header>

    <div class="success-message" id="successMessage">
            ✅ Inscription réussie ! Redirection en cours...
        </div>

        <form method="post" id="signupForm">
            <div class="groups">

            

            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input 
                    type="email" 
                    id="email" 
                    placeholder="votre@email.com" 
                    name="email"
                >
                <div class="error-message" id="emailError">Veuillez entrer un email valide</div>
            </div>


            <div class="form-group">
                <label>Mot de passe <span class="required">*</span></label>
                <input 
                    type="password" 
                    id="password"
                    name="password"
                    placeholder="••••••••" 
                    minlength="8" 
                    oninput="checkPasswordStrength()"
                >
            </div>
            <button name="btn" type="submit" class="btn-submit" id="submitBtn">
                S'inscrire
            </button>
    </div>
    <div class="footer-text">
            Vous avez déjà un compte ? <a href="#" onclick="alert('Redirection vers la page de connexion')">Se connecter</a>
        </div>
        <?php
        if (isset($_POST["btn"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($conn,$sql);
    if(mysqli_num_rows($res)){
        $userData = mysqli_fetch_assoc($res);
        $passwordCheck = password_verify($password,$userData["password"]);
        if($passwordCheck){
            $_SESSION["info"] = ["email" => $userData["email"],"role" => $userData["email"] ];
            echo "ok";
        }
        else{
            echo "not ok";
        }
    }

    }
    

        
        ?>
        </form>


        
    </div>

    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthBar.className = 'strength-fill';
            
            if (strength === 0 || strength === 1) {
                strengthBar.classList.add('weak');
                strengthText.textContent = 'Mot de passe faible';
                strengthText.style.color = '#ef4444';
            } else if (strength === 2 || strength === 3) {
                strengthBar.classList.add('medium');
                strengthText.textContent = 'Mot de passe moyen';
                strengthText.style.color = '#f59e0b';
            } else {
                strengthBar.classList.add('strong');
                strengthText.textContent = 'Mot de passe fort';
                strengthText.style.color = '#22c55e';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const passwordError = document.getElementById('passwordError');
            const confirmInput = document.getElementById('confirmPassword');

            if (confirmPassword && password !== confirmPassword) {
                passwordError.style.display = 'block';
                confirmInput.classList.add('error');
            } else {
                passwordError.style.display = 'none';
                confirmInput.classList.remove('error');
            }
        }

        function validateForm() {
            const cin = document.getElementById('cin').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            let isValid = true;

            // Validation CIN
            if (!/^[0-9]{8}$/.test(cin)) {
                document.getElementById('cinError').style.display = 'block';
                document.getElementById('cin').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('cinError').style.display = 'none';
                document.getElementById('cin').classList.remove('error');
            }

            // Validation Email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('emailError').style.display = 'block';
                document.getElementById('email').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('emailError').style.display = 'none';
                document.getElementById('email').classList.remove('error');
            }

            // Validation Téléphone
            if (!/^[\+]?[0-9]{8,15}$/.test(phone)) {
                document.getElementById('phoneError').style.display = 'block';
                document.getElementById('phone').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('phoneError').style.display = 'none';
                document.getElementById('phone').classList.remove('error');
            }

            // Validation Mot de passe
            if (password !== confirmPassword) {
                document.getElementById('passwordError').style.display = 'block';
                document.getElementById('confirmPassword').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('passwordError').style.display = 'none';
                document.getElementById('confirmPassword').classList.remove('error');
            }

            return isValid;
        }

        function handleSubmit(event) {
            event.preventDefault();

            if (!validateForm()) {
                return;
            }

            // Récupération des données
            const formData = {
                cin: document.getElementById('cin').value,
                lastName: document.getElementById('lastName').value,
                firstName: document.getElementById('firstName').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                password: document.getElementById('password').value
            };

            console.log('Données du formulaire:', formData);

            // Désactiver le bouton
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Inscription en cours...';

            // Simuler l'envoi au serveur
            setTimeout(() => {
                document.getElementById('successMessage').style.display = 'block';
                document.getElementById('signupForm').style.display = 'none';

                // Redirection après 2 secondes
                setTimeout(() => {
                    alert('Inscription réussie ! Bienvenue sur LUXELOC 🎉');
                    // window.location.href = 'index.html'; // Décommenter pour rediriger
                }, 2000);
            }, 1500);
        }
    </script>
       
</body>
</html>