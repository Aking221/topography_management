<?php
require_once('../includes/db.php'); 
require_once('../includes/auth.php');


// Vérification sur la session authentification
if (!isset($_SESSION["authentification"]) || $_SESSION['privilege'] !== 'admin') {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

// Récupérer les utilisateurs
$sqlUsers = "SELECT id, email, nom_complet, telephone, groupe, privilege, code_chantier FROM utilisateurs";
$resultUsers = mysqli_query($conn, $sqlUsers) or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />
    <title>LaboTopo - Liste des utilisateurs</title>
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
        .search-bar {
            margin-bottom: 20px;
        }
        .table-actions {
            display: flex;
            gap: 10px;
        }
        .table-actions .btn {
            padding: 5px 10px;
            font-size: 14px;
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
                                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="materiel_topo/add.php">Ajout matériel</a></li>
                                     <?php } ?>
                                        <li><a href="materiel_topo/list.php">Liste matériel</a></li>
                                        <li><a href="materiel_topo/recherche_materiel.php">Rechercher / Imprimer</a></li>
                                        <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="materiel_topo/mise_au_rebut.php">Mise au rebut</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-refresh"></i> MOUVEMENT <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="transferts/add.php">Enregistrer transfert</a></li>
                                        <?php } ?>
                                        <li><a href="transferts/list.php">Liste des transferts</a></li>
                                        <li><a href="transferts/recherche.php">Rechercher / Imprimer</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-table"></i> INTERVENTIONS <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="interventions/add.php">Nouvelle intervention</a></li>
                                        <?php } ?>
                                        <li><a href="interventions/list.php">Liste des interventions</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-tasks"></i> SUIVI COMMANDES <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                    <?php if (in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) { ?>
                                        <li><a href="chantiers/add.php">Nouvelle commande</a></li>
                                        <?php } ?>
                                        <li><a href="chantiers/list.php">Liste des commandes</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-search"></i> RECHERCHE / EDITION <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <<li><a href="materiel_topo/fiche_suivi.php">Etat 1</a></li>
                                        <li><a href="materiel_topo/fiche_suivi.php">Etat 2</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-cogs"></i> PARAMETRAGE <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="../pages/pays/list.php">Liste abréviations</a></li>
            <li><a href="../pages/pays/view.php">Liste des pays</a></li>
            <li><a href="../pages/chantiers/view.php">Liste des chantiers</a></li>
            <li><a href="../pages/fournisseurs/list.php">Liste des fournisseurs</a></li>
            <?php if ($_SESSION['privilege'] === 'admin') { ?>
                <li><a href="../pages/materiel_topo/rebut_requests.php">Demandes de Mise au Rebut</a></li>
            <?php } ?>
        </ul>
    </li>
    <?php if ($_SESSION['privilege'] === 'admin') { ?>
                                    <li><a><i class="fa fa-users"></i> UTILISATEUR <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="user_register.php">Nouveau</a></li>
                                        <li><a href="listeutilisateurs.php">Liste des utilisateurs</a></li>
                                    </ul>
                                </li>
                                <?php } ?>
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
                    <h1>Liste des utilisateurs</h1>
                    <div class="search-bar">
                        <input type="text" id="searchInput" onkeyup="searchTable()" class="form-control" placeholder="Rechercher...">
                    </div>
                    <table id="userTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Nom Complet</th>
                                <th>Téléphone</th>
                                <th>Privilège</th>
                                <th>Groupe</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; while ($user = mysqli_fetch_assoc($resultUsers)) { ?>
                                <tr>
                                    <td><?php echo $index++; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['nom_complet']; ?></td>
                                    <td><?php echo $user['telephone']; ?></td>
                                    <td><?php echo $user['privilege']; ?></td>
                                    <td><?php echo $user['groupe']; ?></td>
                                    <td class="table-actions">
                                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toLowerCase();
            table = document.getElementById("userTable");
            tr = table.getElementsByTagName("tr");
            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }

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
