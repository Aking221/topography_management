<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_famille_topo = addslashes($_POST['id_famille_topo']);
    $code = addslashes($_POST['code']);
    $description = addslashes($_POST['description']);
    $marque = addslashes($_POST['marque']);
    $num_serie = addslashes($_POST['num_serie']);
    $date_acquisition = addslashes($_POST['date_acquisition']);
    $cout_acquisition = addslashes($_POST['cout_acquisition']);
    $id_fournisseur = addslashes($_POST['id_fournisseur']);
    $num_bc = addslashes($_POST['num_bc']);
    $fiche_bl = addslashes($_POST['fiche_bl']);
    $date_mise_service = addslashes($_POST['date_mise_service']);
    $etat = addslashes($_POST['etat']);
    $id_chantier = addslashes($_POST['id_chantier']);
    $date_affectation = addslashes($_POST['date_affectation']);

    $sql = "UPDATE materiel_topo SET 
        id_famille_topo='$id_famille_topo', 
        code='$code', 
        description='$description', 
        marque='$marque', 
        num_serie='$num_serie', 
        date_acquisition='$date_acquisition', 
        cout_acquisition='$cout_acquisition', 
        id_fournisseur='$id_fournisseur', 
        num_bc='$num_bc', 
        fiche_bl='$fiche_bl', 
        date_mise_service='$date_mise_service', 
        etat='$etat', 
        id_chantier='$id_chantier', 
        date_affectation='$date_affectation' 
        WHERE id='$id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if ($result) {
        header("Location: list.php");
        exit();
    }
}

$sql = "SELECT * FROM materiel_topo WHERE id='$id'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$materiel = mysqli_fetch_assoc($result);

$sqlFamille = "SELECT id, materiel FROM familles_topo";
$resultFamille = mysqli_query($conn, $sqlFamille) or die(mysqli_error($conn));

$sqlFournisseur = "SELECT id, fournisseur FROM fournisseurs";
$resultFournisseur = mysqli_query($conn, $sqlFournisseur) or die(mysqli_error($conn));

$sqlChantier = "SELECT id, chantier FROM chantiers";
$resultChantier = mysqli_query($conn, $sqlChantier) or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Matériel</title>
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
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="../../user.png" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Bonjour,</span>
                            <h2><?php echo $_SESSION['nomComplet']; ?></h2>
                        </div>
                    </div>
                    <br />
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
                                        <li><a href="../chantiers/list.php">Etat 1</a></li>
                                        <li><a href="../                                        chantiers/list.php">Etat 2</a></li>
                                    </ul>
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
                <div class="form-container">
                    <h1>Modifier Matériel</h1>
                    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                        <div class="form-group">
                            <label for="id_famille_topo">Famille de matériel *</label>
                            <select class="form-control" id="id_famille_topo" name="id_famille_topo" required disabled>
                                <?php while($famille = mysqli_fetch_assoc($resultFamille)) { ?>
                                    <option value="<?php echo $famille['id']; ?>" <?php if ($materiel['id_famille_topo'] == $famille['id']) echo 'selected'; ?>><?php echo $famille['materiel']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="id_famille_topo" value="<?php echo $materiel['id_famille_topo']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="code">Code matériel</label>
                            <input type="text" id="code" name="code" value="<?php echo $materiel['code']; ?>" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="description">Description du matériel</label>
                            <input type="text" class="form-control" id="description" name="description" value="<?php echo $materiel['description']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="marque">Marque</label>
                            <input type="text" class="form-control" id="marque" name="marque" value="<?php echo $materiel['marque']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="num_serie">Numéro de Série</label>
                            <input type="text" class="form-control" id="num_serie" name="num_serie" value="<?php echo $materiel['num_serie']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="date_acquisition">Date acquisition</label>
                            <input type="date" class="form-control" id="date_acquisition" name="date_acquisition" value="<?php echo $materiel['date_acquisition']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="cout_acquisition">Coût d'acquisition</label>
                            <input type="text" class="form-control" id="cout_acquisition" name="cout_acquisition" value="<?php echo $materiel['cout_acquisition']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="id_fournisseur">Fournisseur *</label>
                            <select class="form-control" id="id_fournisseur" name="id_fournisseur" required>
                                <?php while($fournisseur = mysqli_fetch_assoc($resultFournisseur)) { ?>
                                    <option value="<?php echo $fournisseur['id']; ?>" <?php if ($materiel['id_fournisseur'] == $fournisseur['id']) echo 'selected'; ?>><?php echo $fournisseur['fournisseur']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="num_bc">Bon commande</label>
                            <input type="text" class="form-control" id="num_bc" name="num_bc" value="<?php echo $materiel['num_bc']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="fiche_bl">BL</label>
                            <input type="text" class="form-control" id="fiche_bl" name="fiche_bl" value="<?php echo $materiel['fiche_bl']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="date_mise_service">Date de mise en service</label>
                            <input type="date" class="form-control" id="date_mise_service" name="date_mise_service" value="<?php echo $materiel['date_mise_service']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="etat">État du matériel *</label>
                            <select class="form-control" id="etat" name="etat" required>
                                <option value="BON" <?php if ($materiel['etat'] == 'BON') echo 'selected'; ?>>Bon</option>
                                <option value="PANNE" <?php if ($materiel['etat'] == 'PANNE') echo 'selected'; ?>>En panne</option>
                                <option value="REFORME" <?php if ($materiel['etat'] == 'REFORME') echo 'selected'; ?>>Réformé</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_chantier">Chantier *</label>
                            <select class="form-control" id="id_chantier" name="id_chantier" required>
                                <?php while($chantier = mysqli_fetch_assoc($resultChantier)) { ?>
                                    <option value="<?php echo $chantier['id']; ?>" <?php if ($materiel['id_chantier'] == $chantier['id']) echo 'selected'; ?>><?php echo $chantier['chantier']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_affectation">Date d'affectation</label>
                            <input type="date" class="form-control" id="date_affectation" name="date_affectation" value="<?php echo $materiel['date_affectation']; ?>">
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
