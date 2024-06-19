<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = addslashes($_POST['email']);
    $pass = $_POST['pass'];

    $verif_query = "SELECT * FROM utilisateurs WHERE email='$email'";
    $verif = $conn->query($verif_query);

    if ($verif && $verif->num_rows > 0) {
        while ($data = mysqli_fetch_array($verif)) {
            if (password_verify($pass, $data['password'])) {
                $_SESSION['authentification'] = TRUE;
                $_SESSION['id'] = $data['id'];
                $_SESSION['privilege'] = $data['privilege'];
                $_SESSION['nomComplet'] = $data['nom_complet'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['telephone'] = $data['telephone'];
                $_SESSION['codechantier'] = $data['code_chantier'];
                $_SESSION['groupe'] = $data['groupe'];
                $_SESSION['LAST_ACTIVITY'] = time(); // record the time of login
                header("Location: pages/dashboard.php");
                exit();
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}

if (isset($_GET['message']) && $_GET['message'] == 'delog') {
    session_destroy(); // Destroy the session on logout
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Labo Topo</title>
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">
    <link href="build/css/custom.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f2f2f2;
            overflow: hidden;
        }
        .container {
            display: flex;
            width: 100%;
            height: 100%;
            max-width: 100%;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .left, .right {
            flex: 1;
        }
        .left {
            background-image: url("gmtcse.webp");
            background-size: cover;
            background-position: center;
        }
        .right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 30%; /* Adjusted width for the logo */
            height: auto;
        }
        form {
            width: 80%; /* Adjusted width for the form */
        }
        .form-group {
            margin-bottom: 15px;
            width: 100%;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
        }
        .alert {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            width: 100%;
        }
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <!-- Left side image -->
        </div>
        <div class="right">
            <div class="logo">
                <img src="logo CSE.jpg" alt="Logo">
            </div>
            <form action="" method="post">
                <?php if (isset($error)): ?>
                    <div class="alert error"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['message']) && ($_GET['message'] == "delog")): ?>
                    <div class="alert success">Déconnexion réussie... À bientôt !</div>
                <?php endif; ?>
                <div class="form-group">
                    <input type="text" name="email" class="form-control" placeholder="Login" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="pass" class="form-control" placeholder="Mot de Passe" required="">
                </div>
                <button type="submit" class="btn btn-primary">Connexion</button>
            </form>
        </div>
    </div>
</body>
</html>
