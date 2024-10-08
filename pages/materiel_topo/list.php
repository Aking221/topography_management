<?php
include_once '../../includes/db.php';
include_once '../../includes/auth.php';

if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); 
    exit();
}

// Fetch all materiel_topo records along with related information
$sql = "SELECT mt.*, ft.materiel AS famille_materiel, f.fournisseur AS nom_fournisseur, c.chantier AS nom_chantier, dr.status AS rebut_status 
        FROM materiel_topo mt
        LEFT JOIN familles_topo ft ON mt.id_famille_topo = ft.id
        LEFT JOIN fournisseurs f ON mt.id_fournisseur = f.id
        LEFT JOIN chantiers c ON mt.id_chantier = c.id
        LEFT JOIN demandes_rebut dr ON mt.id = dr.id_materiel_topo";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Matériel</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../vendors/animate.css/animate.min.css" rel="stylesheet">
    <link href="../../build/css/custom.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
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
        .highlight-rebut {
            background-color: #ffcccc !important;
        }
        .highlight-pending {
            background-color: #ffffcc !important;
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
                                        <li><a href="../materiel_topo/list_materiel.php">fiche de suivi</a></li>
                                     </ul>                
                                </li>                   
                                <li><a><i class="fa fa-cogs"></i> PARAMETRAGE <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                       <li><a href="../pays/list.php">Liste abréviations</a></li>
                                        <li><a href="../pays/view.php">Liste des pays</a></li>
                                        <li><a href="../chantiers/view.php">Liste des chantiers</a></li>
                                        <li><a href="../fournisseurs/list.php">Liste des fournisseurs</a></li>
                                        <li><a href="../interventions/list_intervenants.php">Liste des intervenants</a></li>
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
                <div class="form-container">
                    <h1>Liste des Matériels</h1>
                    <table id="materielTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Matériel</th>
                                <th>Code Matériel</th>
                                <th>Description</th>
                                <th>Marque</th>
                                <th>Numéro de Série</th>
                                <th>Date Acquisition</th>
                                <th>Coût Acquisition</th>
                                <th>Fournisseur</th>
                                <th>Bon Commande</th>
                                <th>Date Service</th>
                                <th>État</th>
                                <th>Chantier</th>
                                <th>Date Affectation</th>
                                <th>BL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($materiel = mysqli_fetch_assoc($result)) { ?>
                                <tr class="<?php echo ($materiel['rebut_status'] == 'approved') ? 'highlight-rebut' : (($materiel['rebut_status'] == 'pending') ? 'highlight-pending' : ''); ?>">
                                    <td><?php echo $materiel['famille_materiel']; ?></td>
                                    <td><?php echo $materiel['code']; ?></td>
                                    <td><?php echo $materiel['description']; ?></td>
                                    <td><?php echo $materiel['marque']; ?></td>
                                    <td><?php echo $materiel['num_serie']; ?></td>
                                    <td><?php echo $materiel['date_acquisition']; ?></td>
                                    <td><?php echo $materiel['cout_acquisition']; ?></td>
                                    <td><?php echo $materiel['nom_fournisseur']; ?></td>
                                    <td><?php echo $materiel['num_bc']; ?></td>
                                    <td><?php echo $materiel['date_mise_service']; ?></td>
                                    <td><?php echo $materiel['etat']; ?></td>
                                    <td><?php echo $materiel['nom_chantier']; ?></td>
                                    <td><?php echo $materiel['date_affectation']; ?></td>
                                    <td>
                                        <?php if(!empty($materiel['fiche_bl'])): ?>
                                            <a href="../../uploads/<?php echo $materiel['fiche_bl']; ?>" target="_blank">Voir</a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table-actions">
                                        <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                            <a href="edit.php?id=<?php echo $materiel['id']; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                        <?php } ?>
                                        <?php if ($_SESSION['privilege'] === 'admin') { ?>
                                            <a href="delete.php?id=<?php echo $materiel['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce matériel ?');"><i class="fa fa-trash"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="footer">
                <p>&copy; 2024 All Rights Reserved. Direction des Systèmes d'Information CSE</p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize the DataTable
            $('#materielTable').DataTable({
                "order": [[0, "asc"]] // You can change the default sorting column and order here
            });

            // Initialize the sidebar menu dropdowns
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
