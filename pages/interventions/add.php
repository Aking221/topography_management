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
    $type_intervention = $_POST['type_intervention'];
    $id_materiel_topo = $_POST['id_materiel_topo'];
    $date_intervention = $_POST['date_intervention'];
    $intervenant = $_POST['intervenant'];
    $sous_traitant = $_POST['sous_traitant'];
    $nature_intervention = $_POST['nature_intervention'];
    $reference = $_POST['reference'];
    $tolerance = $_POST['tolerance'];
    $duree_validite = $_POST['duree_validite'];
    $date_fin_validite = $_POST['date_fin_validite'];
    $cout = $_POST['cout'];
    $fiche = $_FILES['fiche']['name'];
    $observation = $_POST['observation'];

    // Move uploaded file to a target directory
    if (!empty($fiche)) {
        move_uploaded_file($_FILES['fiche']['tmp_name'], "../../uploads/" . $fiche);
    }

    $sql = "INSERT INTO interventions (type_intervention, id_materiel_topo, date_intervention, intervenant, sous_traitant, nature_intervention, reference, tolerance, duree_validite, date_fin_validite, cout, fiche, observation)
            VALUES ('$type_intervention', '$id_materiel_topo', '$date_intervention', '$intervenant', '$sous_traitant', '$nature_intervention', '$reference', '$tolerance', '$duree_validite', '$date_fin_validite', '$cout', '$fiche', '$observation')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Nouvelle intervention ajoutée avec succès!";
        header("Location: list.php");
        exit();
    } else {
        $_SESSION['error'] = "Erreur: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Intervention</title>
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
                        <a href="../dashboard.php" class="site_title"><img src="../../logo CSE.png" width="190" height="50"/><span style="color: white; font-weight: bold;">GESTION LABORATOIRE</span></a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="../../user.png" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Bonjour,</span>
                            <h2><?php echo $_SESSION['nomComplet'];?></h2>
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
                    <h1>Nouvelle Intervention</h1>
                    <?php if(isset($_SESSION['message'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <form action="add.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="type_intervention">Type d'Intervention *</label>
                            <input type="text" class="form-control" id="type_intervention" name="type_intervention" required>
                        </div>
                        <div class="form-group">
                            <label for="id_materiel_topo">Matériel *</label>
                            <select class="form-control" id="id_materiel_topo" name="id_materiel_topo" required>
                                <option value="">Sélectionner le matériel</option>
                                <?php
                                $sqlMateriels = "SELECT id, code FROM materiel_topo";
                                $resultMateriels = $conn->query($sqlMateriels);
                                while($row = $resultMateriels->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['code']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_intervention">Date d'Intervention *</label>
                            <input type="date" class="form-control" id="date_intervention" name="date_intervention" required>
                        </div>
                        <div class="form-group">
                            <label for="intervenant">Intervenant</label>
                            <input type="text" class="form-control" id="intervenant" name="intervenant">
                        </div>
                        <div class="form-group">
                            <label for="sous_traitant">Sous-traitant</label>
                            <input type="text" class="form-control" id="sous_traitant" name="sous_traitant">
                        </div>
                        <div class="form-group">
                            <label for="nature_intervention">Nature de l'Intervention</label>
                            <input type="text" class="form-control" id="nature_intervention" name="nature_intervention">
                        </div>
                        <div class="form-group">
                            <label for="reference">Référence</label>
                            <input type="text" class="form-control" id="reference" name="reference">
                        </div>
                        <div class="form-group">
                            <label for="tolerance">Tolérance</label>
                            <input type="number" class="form-control" id="tolerance" name="tolerance">
                        </div>
                        <div class="form-group">
                            <label for="duree_validite">Durée de Validité (en jours)</label>
                            <input type="number" class="form-control" id="duree_validite" name="duree_validite">
                        </div>
                        <div class="form-group">
                            <label for="date_fin_validite">Date de Fin de Validité</label>
                            <input type="date" class="form-control" id="date_fin_validite" name="date_fin_validite">
                        </div>
                        <div class="form-group">
                            <label for="cout">Coût</label>
                            <input type="number" class="form-control" id="cout" name="cout">
                        </div>
                        <div class="form-group">
                            <label for="fiche">Fiche</label>
                            <input type="file" class="form-control" id="fiche" name="fiche">
                        </div>
                        <div class="form-group">
                            <label for="observation">Observation</label>
                            <textarea class="form-control" id="observation" name="observation"></textarea>
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
