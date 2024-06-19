<?php
include '../includes/auth.php';
requireLogin();
include '../includes/db.php';

// Récupérer le nombre de chantiers
$sqlChantiers = "SELECT COUNT(*) as total FROM chantiers";
$resultChantiers = $conn->query($sqlChantiers);
$totalChantiers = $resultChantiers->fetch_assoc()['total'];

// Récupérer le nombre de matériels topographiques
$sqlMateriels = "SELECT COUNT(*) as total FROM materiel_topo";
$resultMateriels = $conn->query($sqlMateriels);
$totalMateriels = $resultMateriels->fetch_assoc()['total'];

// Récupérer le nombre d'utilisateurs
$sqlUsers = "SELECT COUNT(*) as total FROM utilisateurs";
$resultUsers = $conn->query($sqlUsers);
$totalUsers = $resultUsers->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-content {
            padding: 20px;
        }
        .chart-container {
            width: 60%;
            margin: 0 auto;
        }
        .nav-md .container.body .main_container {
            background: #F7F7F7;
        }
        .sidebar {
            background-color: #00a65a;
            color: #fff;
            height: 100%;
            position: fixed;
            width: 230px;
        }
        .sidebar .nav-title {
            text-transform: uppercase;
            padding: 15px 20px;
            font-weight: bold;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar ul li {
            padding: 10px 20px;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }
        .sidebar ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="accueil.php" class="site_title"><i class="fa fa-paw"></i> <span>Topographie Management</span></a>
                    </div>
                    <div class="clearfix"></div>
                    <br />

                    <!-- Sidebar menu -->
                    <div class="sidebar">
                        <ul class="nav side-menu">
                            <li class="nav-title">Menu</li>
                            <li><a href="../index.php">Accueil</a></li>
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="../index.php?msg=logout">Se déconnecter</a></li>
                            <li><a href="#">Matériel</a></li>
                            <li><a href="#">Mouvement</a></li>
                            <li><a href="#">Interventions</a></li>
                            <li><a href="#">Suivi Commandes</a></li>
                            <li><a href="#">Recherche / Edition</a></li>
                            <li><a href="#">Paramétrage</a></li>
                            <li><a href="#">Administration</a></li>
                            <li><a href="#">Utilisateur</a></li>
                        </ul>
                    </div>
                    <!-- /Sidebar menu -->
                </div>
            </div>

            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <ul class="navbar-right">
                            <li class="nav-item dropdown open">
                                <a href="../index.php?msg=logout"><i class="fa fa-sign-out" style="font-size:26px"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="right_col" role="main">
                <div class="dashboard-content">
                    <h1>Tableau de Bord</h1>
                    <div class="chart-container">
                        <canvas id="dashboardChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <script src="../vendors/nprogress/nprogress.js"></script>
    <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    <script>
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        const dashboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Chantiers', 'Matériels', 'Utilisateurs'],
                datasets: [{
                    label: 'Nombre Total',
                    data: [<?php echo $totalChantiers; ?>, <?php echo $totalMateriels; ?>, <?php echo $totalUsers; ?>],
                    backgroundColor: ['#007bff', '#28a745', '#dc3545'],
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
