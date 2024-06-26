<?php
include '../../includes/db.php';
include '../../includes/auth.php';



$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM fournisseurs WHERE fournisseur LIKE '%$search%' OR code LIKE '%$search%' OR contact LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des fournisseurs</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../vendors/animate.css/animate.min.css" rel="stylesheet">
    <link href="../../build/css/custom.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .table-container {
            padding: 20px;
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
            font-weight: bold.
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
            text-decoration: none.
        }
        .sidebar ul li a:hover {
            text-decoration: underline.
        }
        .footer {
            background-color: #f7f7f7;
            padding: 20px;
            text-align: center.
            position: fixed;
            width: 100%;
            bottom: 0.
        }
    </style>
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="../dashboard.php" class="site_title">
                            <img src="../../logo CSE.png" width="190" height="50"/>
                            <span style="color: white; font-weight: bold;">GESTION LABORATOIRE</span>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="../../user.png" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Bonjour,</span>
                            <h2><?php echo $_SESSION['nomComplet']; ?></h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                            <li><a href="../dashboard.php"><i class="fa fa-home"></i> ACCUEIL</a></li>
                                <li><a><i class="fa fa-list"></i> MATERIEL <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="../materiel_topo/add.php">Ajout matériel</a></li>
                                     <?php } ?>
                                        <li><a href="../materiel_topo/list.php">Liste matériel</a></li>
                                        <li><a href="../materiel_topo/recherche_materiel.php">Rechercher / Imprimer</a></li>
                                        <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="../materiel_topo/mise_au_rebut.php">Mise au rebut</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-refresh"></i> MOUVEMENT <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="../transferts/add.php">Enregistrer transfert</a></li>
                                        <?php } ?>
                                        <li><a href="../transferts/list.php">Liste des transferts</a></li>
                                        <li><a href="../transferts/recherche.php">Rechercher / Imprimer</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-table"></i> INTERVENTIONS <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="../interventions/add.php">Nouvelle intervention</a></li>
                                        <?php } ?>
                                        <li><a href="../interventions/list.php">Liste des interventions</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-tasks"></i> SUIVI COMMANDES <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="../chantiers/add.php">Nouvelle commande</a></li>
                                        <?php } ?>
                                        <li><a href="../chantiers/list.php">Liste des commandes</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-search"></i> RECHERCHE / EDITION <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                         <li><a href="../materiel_topo/fiche_suivi.php">Etat 1</a></li>
                                        <li><a href="../materiel_topo/fiche_suivi.php">Etat 2</a></li></ul>
                                </li>
                                <li><a><i class="fa fa-cogs"></i> PARAMETRAGE <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="../pays/list.php">Liste abréviations</a></li>
            <li><a href="../pays/view.php">Liste des pays</a></li>
            <li><a href="../chantiers/view.php">Liste des chantiers</a></li>
            <li><a href="../fournisseurs/list.php">Liste des fournisseurs</a></li>
            <?php if ($_SESSION['privilege'] === 'admin') { ?>
                <li><a href="../materiel_topo/rebut_requests.php">Demandes de Mise au Rebut</a></li>
            <?php } ?>
        </ul>
    </li>
    <?php if ($_SESSION['privilege'] === 'admin') { ?>
                                    <li><a><i class="fa fa-users"></i> UTILISATEUR <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="../user_register.php">Nouveau</a></li>
                                        <li><a href="../listeutilisateurs.php">Liste des utilisateurs</a></li>
                                    </ul>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                    <!-- menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Deconnexion" href="../logout.php">
                            <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <ul class="navbar-right">
                            <li class="nav-item dropdown open">
                                <a href="../logout.php"><i class="fa fa-sign-out" style="font-size:26px"></i></a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="../logout.php"><i class="fa fa-sign-out pull-right"></i> Déconnexion</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="right_col" role="main">
                <div class="table-container">
                    <h1>Liste des fournisseurs</h1>
                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                    <a href="add.php" class="btn btn-success add-button"><i class="fa fa-plus"></i> Ajouter un fournisseur</a>
                    <?php } ?>
                    <form action="list_fournisseurs.php" method="GET" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="search" class="sr-only">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="Rechercher" value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Rechercher</button>
                    </form>
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Fournisseur</th>
                                <th>Code</th>
                                <th>Contact</th>
                                <th>Active</th>
                                <th>Observation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['fournisseur']; ?></td>
                                    <td><?php echo $row['code']; ?></td>
                                    <td><?php echo $row['contact']; ?></td>
                                    <td><?php echo $row['active'] ? 'Oui' : 'Non'; ?></td>
                                    <td><?php echo $row['observation']; ?></td>
                                    <td>
                                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                        <?php } ?>
                                        <?php if ($_SESSION['privilege'] === 'admin') { ?>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="footer">
                <p>&copy; 2024 All Rights Reserved. Direction des Systèmes d'Information CSE</p>
            </div>
        </div>
    </div>

    <script src="../../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendors/fastclick/lib/fastclick.js"></script>
    <script src="../../vendors/nprogress/nprogress.js"></script>
    <script src="../../vendors/Chart.js/dist/Chart.min.js"></script>
    <script>
        // Initialize the sidebar menu dropdowns
        $(document).ready(function() {
            $('.side-menu li a').on('click', function(e) {
                const $this = $(this);
                const $parent = $this.parent();
                const $submenu = $this.next('.child_menu');

                if ($submenu.length > 0) {
                    e.preventDefault();

                    if ($parent.hasClass('active')) {
                        $parent.removeClass('active');
                        $submenu.slideUp();
                    } else {
                        $parent.addClass('active');
                        $submenu.slideDown();
                    }
                }
            });
        });
    </script>
</body>
</html>
