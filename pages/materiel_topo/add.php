<?php
include '../../includes/db.php';
include '../../includes/auth.php';

requireLogin();

if (!isset($_SESSION["authentification"]) || $_SESSION['privilege'] !== 'admin') {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement un nouveau matériel</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../vendors/animate.css/animate.min.css" rel="stylesheet">
    <link href="../../build/css/custom.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .form-container {
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
        .footer {
            background-color: #f7f7f7;
            padding: 20px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
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
                                        <li><a href="add.php">Ajout matériel</a></li>
                                        <li><a href="list.php">Liste matériel</a></li>
                                        <li><a href="recherche_materiel.php">Rechercher / Imprimer</a></li>
                                        <li><a href="mise_au_rebut.php">Mise au rebut</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-refresh"></i> MOUVEMENT <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="../transferts/add.php">Enregistrer transfert</a></li>
                                        <li><a href="../transferts/list.php">Liste des transferts</a></li>
                                        <li><a href="../transferts/recherche.php">Rechercher / Imprimer</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-table"></i> INTERVENTIONS <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="../interventions/add.php">Nouvelle intervention</a></li>
                                        <li><a href="../interventions/list.php">Liste des interventions</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-tasks"></i> SUIVI COMMANDES <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="../chantiers/add.php">Nouvelle commande</a></li>
                                        <li><a href="../chantiers/list.php">Liste des commandes</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-search"></i> RECHERCHE / EDITION <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="../chantiers/list.php">Etat 1</a></li>
                                        <li><a href="../chantiers/list.php">Etat 2</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-cogs"></i> PARAMETRAGE <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="../pays/list.php">Liste abréviations</a></li>
                                        <li><a href="../pays/list.php">Liste des pays</a></li>
                                        <li><a href="../chantiers/list.php">Liste des chantiers</a></li>
                                        <li><a href="../fournisseurs/list.php">Liste des fournisseurs</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-users"></i> UTILISATEUR <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="../user_register.php">Nouveau</a></li>
                                        <li><a href="../listeutilisateurs.php">Liste des utilisateurs</a></li>
                                    </ul>
                                </li>
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
                <div class="form-container">
                    <h1>Enregistrement un nouveau matériel</h1>
                    <form>
                        <div class="form-group">
                            <label for="materiel">Matériel *</label>
                            <input type="text" list="listemateriel" id="materiel" name="materiel" required="required" onChange="getcodebymat(this.value);" class="form-control ">
                        </div>
                        <div class="form-group">
                            <label for="code_materiel">Code matériel</label>
                            <input type="text" id="codemat" name="codemat" readonly="readonly"  class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="description">Description du matériel</label>
                            <input type="text" class="form-control" id="description">
                        </div>
                        <div class="form-group">
                            <label for="marque">Marque</label>
                            <input type="text" class="form-control" id="marque">
                        </div>
                        <div class="form-group">
                            <label for="num_serie">Numéro de Série</label>
                            <input type="text" class="form-control" id="num_serie">
                        </div>
                        <div class="form-group">
                            <label for="date_acquisition">Date acquisition</label>
                            <input type="date" class="form-control" id="date_acquisition
                        </div>
                        <div class="form-group">
                            <label for="cout_acquisition">Coût d'acquisition</label>
                            <input type="text" class="form-control" id="cout_acquisition">
                        </div>
                        <div class="form-group">
                            <label for="frais_suppl">Frais supplémentaires</label>
                            <input type="text" class="form-control" id="frais_suppl">
                        </div>
                        <div class="form-group">
                            <label for="fournisseur">Fournisseur *</label>
                            <input type="text" class="form-control" id="fournisseur" required>
                        </div>
                        <div class="form-group">
                            <label for="bon_commande">Bon commande</label>
                            <input type="text" class="form-control" id="bon_commande">
                        </div>
                        <div class="form-group">
                            <label for="bl">BL</label>
                            <input type="file" class="form-control" id="bl">
                        </div>
                        <div class="form-group">
                            <label for="date_service">Date de mise en service</label>
                            <input type="date" class="form-control" id="date_service">
                        </div>
                        <div class="form-group">
                            <label for="etat_materiel">État du matériel *</label>
                            <select class="form-control" id="etat_materiel" required>
                                <option value="">--- État du matériel ---</option>
                                <option value="neuf">Neuf</option>
                                <option value="occasion">Occasion</option>
                                <option value="reforme">Réformé</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="chantier">Chantier *</label>
                            <input type="text" class="form-control" id="chantier" required>
                        </div>
                        <div class="form-group">
                            <label for="date_affectation">Date d'affectation</label>
                            <input type="date" class="form-control" id="date_affectation">
                        </div>
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </form>
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
