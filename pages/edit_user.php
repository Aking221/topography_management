<?php
require_once('../includes/db.php'); 
require_once('../includes/auth.php');

// Vérification sur la session authentification et privilège admin
if (!isset($_SESSION["authentification"]) || $_SESSION['privilege'] !== 'admin') {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

$userId = $_GET['id'];

// Récupérer les informations de l'utilisateur
$sqlUser = "SELECT * FROM utilisateurs WHERE id='$userId'";
$resultUser = mysqli_query($conn, $sqlUser) or die(mysqli_error($conn));
$user = mysqli_fetch_assoc($resultUser);

// Mise à jour des informations de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = addslashes($_POST['email']);
    $nomComplet = addslashes($_POST['nomComplet']);
    $telephone = addslashes($_POST['telephone']);
    $chantier = addslashes($_POST['chantier']);
    $privilege = addslashes($_POST['privilege']);

    $updateUserQuery = "UPDATE utilisateurs SET email='$email', nom_complet='$nomComplet', telephone='$telephone', code_chantier='$chantier', privilege='$privilege' WHERE id='$userId'";
    $resultUpdate = mysqli_query($conn, $updateUserQuery) or die(mysqli_error($conn));
    
    if ($resultUpdate) {
        header("Location: listeutilisateurs.php?update=ok");
    } else {
        header("Location: edit_user.php?id=$userId&erreur=update");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />
    <title>LaboTopo - Modifier utilisateur</title>
    <!-- CSS links -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <link href="../vendors/starrr/dist/starrr.css" rel="stylesheet">
    <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <style>
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

<body class="nav-md footer_fixed">
    <div class="container body">
        <div class="main_container">
            <!-- Sidebar -->
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="dashboard.php" class="site_title"><img src="../logo CSE.png" width="190" height="50"/><span style="color: white; font-weight: bold;">GESTION LABORATOIRE</span></a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- Menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="../user.png" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Bonjour,</span>
                            <h2><?php echo $_SESSION['nomComplet'];?></h2>
                        </div>
                    </div>
                    <!-- /Menu profile quick info -->
                    <br />
                    <!-- Sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> ACCUEIL</a></li>
                                <li><a><i class="fa fa-list"></i> MATERIEL <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="materiel_topo/add.php">Ajout matériel</a></li>
                                        <li><a href="materiel_topo/list.php">Liste matériel</a></li>
                                        <li><a href="materiel_topo/recherche_materiel.php">Rechercher / Imprimer</a></li>
                                        <li><a href="materiel_topo/mise_au_rebut.php">Mise au rebut</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-refresh"></i> MOUVEMENT <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="transferts/add.php">Enregistrer transfert</a></li>
                                        <li><a href="transferts/list.php">Liste des transferts</a></li>
                                        <li><a href="transferts/recherche.php">Rechercher / Imprimer</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-table"></i> INTERVENTIONS <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="interventions/add.php">Nouvelle intervention</a></li>
                                        <li><a href="interventions/list.php">Liste des interventions</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-tasks"></i> SUIVI COMMANDES <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="chantiers/add.php">Nouvelle commande</a></li>
                                        <li><a href="chantiers/list.php">Liste des commandes</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-search"></i> RECHERCHE / EDITION <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="chantiers/list.php">Etat 1</a></li>
                                        <li><a href="chantiers/list.php">Etat 2</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-cogs"></i> PARAMETRAGE <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="pays/list.php">Liste abréviations</a></li>
                                        <li><a href="pays/list.php">Liste des pays</a></li>
                                        <li><a href="chantiers/list.php">Liste des chantiers</a></li>
                                        <li><a href="fournisseurs/list.php">Liste des fournisseurs</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-users"></i> UTILISATEUR <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="user_register.php">Nouveau</a></li>
                                        <li><a href="liste_utilisateurs.php">Liste des utilisateurs</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /Sidebar menu -->
                    <!-- Menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Déconnexion" href="../pages/logout.php">
                            <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /Menu footer buttons -->
                </div>
            </div>
            <!-- /Sidebar -->

            <!-- Top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <ul class="navbar-right">
                            <li class="nav-item dropdown open">
                                <a href="logout.php"><i class="fa fa-sign-out" style="font-size:26px"></i></a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="../pages/logout.php"><i class="fa fa-sign-out pull-right"></i> Déconnexion</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /Top navigation -->

            <!-- Page content -->
            <div class="right_col" role="main">
                <div class="dashboard-content">
                    <h1>Modifier Utilisateur</h1>
                    <form action="edit_user.php?id=<?php echo $userId; ?>" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="email">Email <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="email" id="email" name="email" required="required" class="form-control" value="<?php echo $user['email']; ?>">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="nomComplet">Nom Complet <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="nomComplet" name="nomComplet" required="required" class="form-control" value="<?php echo $user['nom_complet']; ?>">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="telephone" class="col-form-label col-md-3 col-sm-3 label-align">Téléphone</label>
                            <div class="col-md-6 col-sm-6">
                                <input id="telephone" class="form-control" type="text" name="telephone" value="<?php echo $user['telephone']; ?>">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="chantier">Chantier</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" list="listechantier" id="chantier" name="chantier" class="form-control" value="<?php echo $user['code_chantier']; ?>">
                                <?php 
                                $req_mat = "SELECT id, code, chantier FROM chantiers";
                                $reponse = mysqli_query($conn, $req_mat) or die(mysqli_error($conn));
                                ?>
                                <datalist id="listechantier">
                                    <?php while ($response2 = mysqli_fetch_array($reponse)) { ?>
                                        <option value="<?php echo $response2['code']; ?>"><?php echo $response2['code'].'  ---'.$response2['chantier']; ?></option>
                                    <?php } ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="privilege" class="col-form-label col-md-3 col-sm-3 label-align">Profil <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <select class="form-control" name="privilege" required="required">
                                    <option>--- Choisir un profil ---</option>
                                    <option value="admin" <?php if ($user['privilege'] == 'admin') echo 'selected'; ?>>Administrateur</option>
                                    <option value="utilisateur" <?php if ($user['privilege'] == 'utilisateur') echo 'selected'; ?>>Utilisateur</option>
                                    <option value="invite" <?php if ($user['privilege'] == 'invite') echo 'selected'; ?>>Invité</option>
                                </select>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <button class="btn btn-primary" type="button" onclick="window.history.back()">Annuler</button>
                                <button class="btn btn-primary" type="reset">Réinitialiser</button>
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /Page content -->

            <!-- Footer content -->
            <div class="footer">
                <p>&copy; 2024 All Rights Reserved. Direction des Systèmes d'Information CSE</p>
            </div>
            <!-- /Footer content -->
        </div>
    </div>

    <!-- JS links -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="../vendors/validator/multifield.js"></script>
    <script src="../vendors/validator/validator.js"></script>
    <script>
        // initialize a validator instance from the "FormValidator" constructor.
        // A "<form>" element is optionally passed as an argument, but is not a must
        var validator = new FormValidator({
            "events": ['blur', 'input', 'change']
        }, document.forms[0]);
        // on form "submit" event
        document.forms[0].onsubmit = function(e) {
            var submit = true,
                validatorResult = validator.checkAll(this);
            console.log(validatorResult);
            return !!validatorResult.valid;
        };
        // on form "reset" event
        document.forms[0].onreset = function(e) {
            validator.reset();
        };
        // stuff related ONLY for this demo page:
        $('.toggleValidationTooltips').change(function() {
            validator.settings.alerts = !this.checked;
            if (this.checked)
                $('form .alert').remove();
        }).prop('checked', false);
    </script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <script src="../vendors/nprogress/nprogress.js"></script>
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../vendors/google-code-prettify/src/prettify.js"></script>
    <script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <script src="../vendors/autosize/dist/autosize.min.js"></script>
    <script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <script src="../vendors/starrr/dist/starrr.js"></script>
    <script src="../build/js/custom.min.js"></script>
</body>
</html>
