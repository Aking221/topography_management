<?php
include '../../includes/db.php';
include '../../includes/auth.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

if (!isset($_SESSION["authentification"]) || !in_array($_SESSION['privilege'], ['admin', 'utilisateur'])) {
    $_SESSION['error'] = "Vous n'avez pas accès à cette section.";
    header("Location: ../dashboard.php"); // Redirection vers le tableau de bord
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $materiel = addslashes($_POST['materiel']);
    $code_materiel = addslashes($_POST['code_materiel']);
    $description = addslashes($_POST['description']);
    $marque = addslashes($_POST['marque']);
    $num_serie = addslashes($_POST['num_serie']);
    $date_acquisition = addslashes($_POST['date_acquisition']);
    $cout_acquisition = addslashes($_POST['cout_acquisition']);
    $frais_suppl = addslashes($_POST['frais_suppl']);
    $fournisseur = addslashes($_POST['fournisseur']);
    $bon_commande = addslashes($_POST['bon_commande']);
    $date_service = $_POST['date_service'];
    $etat_materiel = addslashes($_POST['etat_materiel']);
    $chantier = addslashes($_POST['chantier']);
    $date_affectation = addslashes($_POST['date_affectation']);
    $content_dir = '../../uploads/'; // dossier où sera déplacé le fichier
    $tmp_file = $_FILES['bl']['tmp_name'];
    $bl = '';

    if ($tmp_file != "") {
        if (!is_uploaded_file($tmp_file)) {
            exit("Le fichier est introuvable");
        }
        $type_file = $_FILES['bl']['type'];
        if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'png') && !strstr($type_file, 'bmp') && !strstr($type_file, 'pdf')) {
            exit("Le fichier doit être une image ou un pdf");
        }
        $name_file = $_FILES['bl']['name'];
        $extension = pathinfo($name_file, PATHINFO_EXTENSION);
        $filename = pathinfo($name_file, PATHINFO_FILENAME);
        $nomDestination = $filename . "_" . date("YmdHis") . '.' . $extension;
        if (!move_uploaded_file($tmp_file, $content_dir . $nomDestination)) {
            exit("Impossible de copier le fichier dans $content_dir");
        }
        $bl = $nomDestination;
    }

    $auteur = $_SESSION['nomComplet'];

    // Fetch the abbreviation for the selected material
    $sql_abv = "SELECT abv FROM familles_topo WHERE materiel = '$materiel'";
    $result_abv = mysqli_query($conn, $sql_abv) or die(mysqli_error($conn));
    $abv = '';
    if ($result_abv && mysqli_num_rows($result_abv) > 0) {
        $row_abv = mysqli_fetch_assoc($result_abv);
        $abv = $row_abv['abv'];
    }

    // Recherche du numero autoIncrement
    $id_famille_topo = (int) $_POST['id_famille_topo'];
    $num_increment = '';
    $sousreq = "SELECT code FROM materiel_topo WHERE id_famille_topo = $id_famille_topo ORDER BY id DESC LIMIT 1";
    $result_req = mysqli_query($conn, $sousreq) or die(mysqli_error($conn));
    if ($result_req && mysqli_num_rows($result_req) > 0) {
        while ($response = mysqli_fetch_array($result_req)) {
            $num_increment = $response['code'];
        }
        $chain = explode("-", $num_increment);
        $numincrem = $chain[1] + 1;
        $length = 5;
        $char = 0;
        $type = 'd';
        $format = "%{$char}{$length}{$type}";
        $codeformat = sprintf($format, $numincrem);
        $code_materiel = $abv . "-" . $codeformat;
    } else {
        $codeformat = "00001";
        $code_materiel = $abv . "-" . $codeformat;
    }

    $add_mat = "INSERT INTO materiel_topo (id_famille_topo, code, description, marque, num_serie, date_acquisition, cout_acquisition, id_fournisseur, num_bc, fiche_bl, date_mise_service, etat, id_chantier, date_affectation, creer_par, observation) 
                VALUES (
                    (SELECT id FROM familles_topo WHERE materiel = '$materiel'), 
                    '$code_materiel', 
                    '$description', 
                    '$marque', 
                    '$num_serie', 
                    '$date_acquisition', 
                    '$cout_acquisition', 
                    (SELECT id FROM fournisseurs WHERE fournisseur = '$fournisseur'), 
                    '$bon_commande', 
                    '$bl', 
                    '$date_service', 
                    '$etat_materiel', 
                    (SELECT id FROM chantiers WHERE chantier = '$chantier'), 
                    '$date_affectation', 
                    '$auteur', 
                    'Aucune'
                )";

    $result = mysqli_query($conn, $add_mat) or die(mysqli_error($conn));

    if ($result) {
        header("location:add.php?add=ok");
    } else {
        header("location:add.php?erreur=existe");
    }
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
            margin-left: 230px; /* Ensure form is not hidden behind the sidebar */
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
        .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1050; /* Sit on top, above sidebar */
    left: 230px; /* Start after the sidebar */
    top: 0;
    width: calc(100% - 230px); /* Full width minus sidebar */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
    box-shadow: 0px 0px 10px 0px #000;
    animation: fadeIn 0.5s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

#errorMessage {
    color: red;
    font-weight: bold;
    text-align: center;
}
    </style>
</head>
<body class="nav-md">
    <!-- Modal for error messages -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="errorMessage"></p>
        </div>
    </div>

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
                    <h1>Enregistrement un nouveau matériel</h1>
                    <form action="" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="materiel">Matériel *</label>
                                    <input type="text" list="listemateriel" id="materiel" name="materiel" required="required" class="form-control">
                                    <datalist id="listemateriel">
                                        <?php 
                                        $req_mat = "SELECT id, materiel, abv FROM familles_topo";
                                        $reponse = mysqli_query($conn, $req_mat) or die(mysqli_error($conn));
                                        while($response2 = mysqli_fetch_array($reponse)) {         
                                        ?>
                                        <option value="<?php echo $response2['materiel']; ?>" data-id="<?php echo $response2['id']; ?>"><?php echo $response2['materiel']; ?></option>
                                        <?php } ?>
                                    </datalist>
                                    <input type="hidden" id="id_famille_topo" name="id_famille_topo">
                                </div>
                                <div class="form-group">
                                    <label for="code_materiel">Code matériel</label>
                                    <input type="text" id="code_materiel" name="code_materiel" readonly="readonly" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description du matériel</label>
                                    <input type="text" class="form-control" id="description" name="description">
                                </div>
                                <div class="form-group">
                                    <label for="marque">Marque</label>
                                    <input type="text" class="form-control" id="marque" name="marque">
                                </div>
                                <div class="form-group">
                                    <label for="num_serie">Numéro de Série</label>
                                    <input type="text" class="form-control" id="num_serie" name="num_serie">
                                </div>
                                <div class="form-group">
                                    <label for="date_acquisition">Date acquisition</label>
                                    <input type="date" class="form-control" id="date_acquisition" name="date_acquisition">
                                </div>
                                <div class="form-group">
                                    <label for="cout_acquisition">Coût d'acquisition</label>
                                    <input type="text" class="form-control" id="cout_acquisition" name="cout_acquisition">
                                </div>
                                <div class="form-group">
                                    <label for="frais_suppl">Frais supplémentaires</label>
                                    <input type="text" class="form-control" id="frais_suppl" name="frais_suppl">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fournisseur">Fournisseur *</label>
                                    <input type="text" list="listefournisseur" id="fournisseur" name="fournisseur" required class="form-control">
                                    <datalist id="listefournisseur">
                                        <?php 
                                        $req_four = "SELECT id, fournisseur FROM fournisseurs";
                                        $reponse = mysqli_query($conn, $req_four) or die(mysqli_error($conn));
                                        while($response2 = mysqli_fetch_array($reponse)) {         
                                        ?>
                                        <option value="<?php echo $response2['fournisseur']; ?>" data-id="<?php echo $response2['id']; ?>"><?php echo $response2['fournisseur']; ?></option>
                                        <?php } ?>
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <label for="bon_commande">Bon commande</label>
                                    <input type="text" class="form-control" id="bon_commande" name="bon_commande">
                                </div>
                                <div class="form-group">
                                    <label for="bl">BL</label>
                                    <input type="file" class="form-control" id="bl" name="bl">
                                </div>
                                <div class="form-group">
                                    <label for="date_service">Date de mise en service</label>
                                    <input type="date" class="form-control" id="date_service" name="date_service">
                                </div>
                                <div class="form-group">
                                    <label for="etat_materiel">État du matériel *</label>
                                    <select class="form-control" id="etat_materiel" name="etat_materiel" required>
                                        <option value="">--- État du matériel ---</option>
                                        <option value="Bon">Bon</option>
                                        <option value="Panne">En panne</option>
                                        <option value="Reforme">Réformé</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="chantier">Chantier *</label>
                                    <input type="text" list="listechantier" id="chantier" name="chantier" required class="form-control">
                                    <datalist id="listechantier">
                                        <?php 
                                        $req_chant = "SELECT id, code, chantier FROM chantiers";
                                        $reponse = mysqli_query($conn, $req_chant) or die(mysqli_error($conn));
                                        while($response2 = mysqli_fetch_array($reponse)) {         
                                        ?>
                                        <option value="<?php echo $response2['chantier']; ?>" data-id="<?php echo $response2['id']; ?>"><?php echo $response2['chantier']; ?></option>
                                        <?php } ?>
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <label for="date_affectation">Date d'affectation</label>
                                    <input type="date" class="form-control" id="date_affectation" name="date_affectation">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" id="submitBtn">Enregistrer</button>
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
$(document).ready(function() {
    $('#materiel').on('input', function() {
        var selectedMaterial = $(this).val();
        var id = $('#listemateriel option[value="' + selectedMaterial + '"]').data('id');
        if (id) {
            $('#id_famille_topo').val(id);
            // Fetch the last code and increment it
            $.ajax({
                type: "POST",
                url: "get_last_code.php",
                data: { id_famille: id },
                dataType: "json",
                success: function(response) {
                    var abv = response.abv;
                    var last_code = response.last_code;
                    var new_code = generateNewCode(abv, last_code);
                    $('#code_materiel').val(new_code);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        } else {
            $('#code_materiel').val('');
        }
    });

    function generateNewCode(abv, last_code) {
        if (last_code === null) {
            return abv + "-00001";
        }
        var parts = last_code.split("-");
        var number = parseInt(parts[1]) + 1;
        return abv + "-" + ("00000" + number).slice(-5);
    }

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

    // Date and file validation
    $('#submitBtn').on('click', function(event) {
        var dateAcquisition = new Date($('#date_acquisition').val());
        var dateService = new Date($('#date_service').val());
        var dateAffectation = new Date($('#date_affectation').val());

        var errors = [];

        if (dateAcquisition > dateService) {
            errors.push("La date d'acquisition ne peut pas être postérieure à la date de mise en service.");
        }

        if (dateAcquisition > dateAffectation) {
            errors.push("La date d'acquisition ne peut pas être postérieure à la date d'affectation.");
        }

        if (dateService > dateAffectation) {
            errors.push("La date de mise en service ne peut pas être postérieure à la date d'affectation.");
        }

        // File validation
        var fileInput = $('#bl')[0];
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0];
            var validFileTypes = ['image/jpeg', 'image/png', 'image/bmp', 'application/pdf'];
            if (!validFileTypes.includes(file.type)) {
                errors.push("Le fichier doit être une image (jpeg, png, bmp) ou un pdf.");
            }
        }

        if (errors.length > 0) {
            event.preventDefault();
            showErrorModal(errors.join("\n"));
        }
    });

    function showErrorModal(message) {
        var modal = document.getElementById("errorModal");
        var span = document.getElementsByClassName("close")[0];
        document.getElementById("errorMessage").innerText = message;
        modal.style.display = "block";

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
});
</script>
