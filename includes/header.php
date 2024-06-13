<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Assurez-vous de mettre le bon chemin vers votre fichier CSS -->
    <title>Topographie Management</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <a href="../index.php">Topographie Management</a>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="../index.php">Accueil</a></li>
                    <li><a href="../pages/dashboard.php">Dashboard</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="../pages/logout.php">Se déconnecter</a></li>
                    <?php else: ?>
                        <li><a href="../pages/user_login.php">Se connecter</a></li>
                        <li><a href="../pages/user_register.php">S'inscrire</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
