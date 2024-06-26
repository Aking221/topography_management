<?php
include '../../includes/db.php';
include '../../includes/auth.php';

requireLogin();

if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_devis = $_POST['date_devis'];
    $fournisseur = $_POST['fournisseur'];
    $num_devis = $_POST['num_devis'];
    $montant_euro = $_POST['montant_euro'];
    $montant_cfa = $_POST['montant_cfa'];
    $chantier = $_POST['chantier'];
    $num_bc = $_POST['num_bc'];
    $date_bc = $_POST['date_bc'];
    $avance_montant = $_POST['avance_montant'];
    $date_avance = $_POST['date_avance'];
    $date_paiement_solde = $_POST['date_paiement_solde'];
    $num_semaines = $_POST['num_semaines'];
    $date_livraison_prevue = $_POST['date_livraison_prevue'];
    $delai_restant = $_POST['delai_restant'];
    $date_reception = $_POST['date_reception'];
    $conformite = $_POST['conformite'];
    $date_fin_garantie = $_POST['date_fin_garantie'];
    $observation = $_POST['observation'];
    $fichier = $_FILES['fichier']['name'];

    // Handle file upload
    if (!empty($fichier)) {
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($fichier);
        move_uploaded_file($_FILES["fichier"]["tmp_name"], $target_file);
    }

    $sql = "INSERT INTO commandes (date_devis, fournisseur, num_devis, montant_euro, montant_cfa, chantier, num_bc, date_bc, avance_montant, date_avance, date_paiement_solde, num_semaines, date_livraison_prevue, delai_restant, date_reception, conformite, date_fin_garantie, fichier, observation) VALUES ('$date_devis', '$fournisseur', '$num_devis', '$montant_euro', '$montant_cfa', '$chantier', '$num_bc', '$date_bc', '$avance_montant', '$date_avance', '$date_paiement_solde', '$num_semaines', '$date_livraison_prevue', '$delai_restant', '$date_reception', '$conformite', '$date_fin_garantie', '$fichier', '$observation')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Nouvelle commande ajoutée avec succès.";
        header("Location: list.php");
        exit();
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout de la commande: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle commande</title>
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
                <div class="form-container">
                    <h1>Enregistrement un nouveau matériel</h1>
                    <form action="ajout_commande.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="date_devis">Date devis *</label>
                            <input type="date" id="date_devis" name="date_devis" required="required" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="fournisseur">Fournisseur *</label>
                            <input type="text" id="fournisseur" name="fournisseur" required="required" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="num_devis">N° Devis *</label>
                            <input type="text" id="num_devis" name="num_devis" required="required" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="montant_euro">Montant Euro</label>
                            <input type="number" id="montant_euro" name="montant_euro" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="montant_cfa">Montant CFA *</label>
                            <input type="number" id="montant_cfa" name="montant_cfa" required="required" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="chantier">Chantier *</label>
                            <input type="text" id="chantier" name="chantier" required="required" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="num_bc">N° Bon commande</label>
                            <input type="text" id="num_bc" name="num_bc" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="date_bc">Date BC</label>
                            <input type="date" id="date_bc" name="date_bc" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="avance_montant">Avance montant</label>
                            <input type="number" id="avance_montant" name="avance_montant" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="date_avance">Date Avance</label>
                            <input type="date" id="date_avance" name="date_avance" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="date_paiement_solde">Date paiement solde</label>
                            <input type="date" id="date_paiement_solde" name="date_paiement_solde" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="num_semaines">N° semaines</label>
                            <input type="number" id="num_semaines" name="num_semaines" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="date_livraison_prevue">Date livraison prévue</label>
                            <input type="date" id="date_livraison_prevue" name="date_livraison_prevue" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="delai_restant">Délai restant</label>
                            <input type="number" id="delai_restant" name="delai_restant" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="date_reception">Date reception</label>
                            <input type="date" id="date_reception" name="date_reception" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="conformite">Conformité</label>
                            <select id="conformite" name="conformite" class="form-control">
                                <option value="">--- Conformité ---</option>
                                <option value="Conforme">Conforme</option>
                                <option value="Non Conforme">Non Conforme</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_fin_garantie">Date fin garantie</label>
                            <input type="date" id="date_fin_garantie" name="date_fin_garantie" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="fichier">Fichier</label>
                            <input type="file" id="fichier" name="fichier" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="observation">Observation</label>
                            <textarea id="observation" name="observation" class="form-control"></textarea>
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
