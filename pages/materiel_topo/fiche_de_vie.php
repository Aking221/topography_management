<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin', 'utilisateur','invite'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT m.*, f.fournisseur, c.chantier 
        FROM materiel_topo m 
        LEFT JOIN fournisseurs f ON m.id_fournisseur = f.id 
        LEFT JOIN chantiers c ON m.id_chantier = c.id 
        WHERE m.id = '$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$materiel = mysqli_fetch_assoc($result);

$mouvement_sql = "SELECT t.*, p.chantier as provenance, d.chantier as destination, u.nom_complet as receptionner_par 
                  FROM transfert_materiel t
                  LEFT JOIN chantiers p ON t.id_provenance = p.id
                  LEFT JOIN chantiers d ON t.id_destination = d.id
                  LEFT JOIN utilisateurs u ON t.receptionner = u.id 
                  WHERE t.id_materiel_topo = '$id'";
$mouvement_result = mysqli_query($conn, $mouvement_sql) or die(mysqli_error($conn));
$mouvements = [];
while ($row = mysqli_fetch_assoc($mouvement_result)) {
    $mouvements[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Vie du Matériel</title>
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
        .materiel-info, .materiel-movements {
            margin-bottom: 20px;
        }
        .materiel-info h3, .materiel-movements h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .table-info th, .table-info td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table-info {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .btn-print {
            margin-top: 20px;
        }
        .footer {
            background-color: #f7f7f7;
            padding: 20px;
            text-align: center;
            width: 100%;
            position: absolute;
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
                                <ul class="dropdown-menu dropdown-usermenu pull-right
                                <li><a href="../logout.php"><i class="fa fa-sign-out pull-right"></i> Déconnexion</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="right_col" role="main">
            <div class="form-container">
                <h1>Fiche de Vie du Matériel</h1>
                <div class="materiel-info">
                    <h3>Informations générales</h3>
                    <table class="table-info">
                        <tr>
                            <th>Code :</th>
                            <td><?php echo $materiel['code']; ?></td>
                            <th>Date acquisition :</th>
                            <td><?php echo $materiel['date_acquisition']; ?></td>
                        </tr>
                        <tr>
                            <th>Description :</th>
                            <td><?php echo $materiel['description']; ?></td>
                            <th>Fournisseur :</th>
                            <td><?php echo $materiel['fournisseur']; ?></td>
                        </tr>
                        <tr>
                            <th>Numéro de série :</th>
                            <td><?php echo $materiel['num_serie']; ?></td>
                            <th>Numéro BC :</th>
                            <td><?php echo $materiel['num_bc']; ?></td>
                        </tr>
                        <tr>
                            <th>Marque :</th>
                            <td><?php echo $materiel['marque']; ?></td>
                            <th>Numéro BL :</th>
                            <td><?php echo isset($materiel['num_bl']) ? $materiel['num_bl'] : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th>Chantier :</th>
                            <td><?php echo $materiel['chantier']; ?></td>
                            <th>État :</th>
                            <td><?php echo $materiel['etat']; ?></td>
                        </tr>
                        <tr>
                            <th>Date de mise en service :</th>
                            <td><?php echo $materiel['date_mise_service']; ?></td>
                            <th>Coût :</th>
                            <td><?php echo $materiel['cout_acquisition']; ?></td>
                        </tr>
                        <tr>
                            <th>Date d'affectation :</th>
                            <td><?php echo $materiel['date_affectation']; ?></td>
                            <th>Observations :</th>
                            <td><?php echo isset($materiel['observation']) ? $materiel['observation'] : 'N/A'; ?></td>
                        </tr>
                    </table>
                </div>

                <div class="materiel-movements">
                    <h3>Les Mouvements</h3>
                    <?php if (!empty($mouvements)) { ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date de transfert</th>
                                    <th>Provenance</th>
                                    <th>Destination</th>
                                    <th>Num BT</th>
                                    <th>Receptionner</th>
                                    <th>Date de réception</th>
                                    <th>Recep par</th>
                                    <th>Observation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mouvements as $mouvement) { ?>
                                    <tr>
                                        <td><?php echo $mouvement['date_transfert']; ?></td>
                                        <td><?php echo $mouvement['provenance']; ?></td>
                                        <td><?php echo $mouvement['destination']; ?></td>
                                        <td><?php echo $mouvement['num_bt']; ?></td>
                                        <td><?php echo $mouvement['receptionner']; ?></td>
                                        <td><?php echo $mouvement['date_reception']; ?></td>
                                        <td><?php echo $mouvement['receptionner_par']; ?></td>
                                        <td><?php echo $mouvement['observation']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>Aucun mouvement enregistré pour ce matériel.</p>
                    <?php } ?>
                </div>
                <button class="btn btn-primary btn-print" onclick="window.location.href='print.php?id=<?php echo $id; ?>'">Imprimer</button>
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
