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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materiel_topo = $_POST['id_materiel_topo'];
    $date_transfert = $_POST['date_transfert'];
    $id_provenance = $_POST['id_provenance'];
    $id_destination = $_POST['id_destination'];
    $num_bt = $_POST['num_bt'];
    $receptionner = isset($_POST['receptionner']) ? 1 : 0;
    $date_reception = $_POST['date_reception'];
    $cout = $_POST['cout'];
    $creer_par = $_SESSION['nomComplet'];

    $bon_transfert = '';
    if (isset($_FILES['bon_transfert']) && $_FILES['bon_transfert']['error'] == 0) {
        $tmp_file = $_FILES['bon_transfert']['tmp_name'];
        $type_file = $_FILES['bon_transfert']['type'];
        $name_file = $_FILES['bon_transfert']['name'];
        $extension = pathinfo($name_file, PATHINFO_EXTENSION);
        $filename = pathinfo($name_file, PATHINFO_FILENAME);
        $nomDestination = $filename . "_" . date("YmdHis") . '.' . $extension;

        $validFileTypes = ['image/jpeg', 'image/png', 'image/bmp', 'application/pdf'];
        if (in_array($type_file, $validFileTypes)) {
            $content_dir = '../../uploads/';
            if (move_uploaded_file($tmp_file, $content_dir . $nomDestination)) {
                $bon_transfert = $nomDestination;
            } else {
                $error = "Impossible de copier le fichier dans $content_dir";
            }
        } else {
            $error = "Le fichier doit être une image (jpeg, png, bmp) ou un pdf.";
        }
    }

    if (empty($error)) {
        $dateTransfert = new DateTime($date_transfert);
        $dateReception = new DateTime($date_reception);

        if ($dateReception < $dateTransfert) {
            $error = "La date de réception ne peut pas être antérieure à la date de transfert.";
        }
    }

    if (empty($error)) {
        $sql = "INSERT INTO transfert_materiel (id_materiel_topo, date_transfert, id_provenance, id_destination, num_bt, bon_transfert, receptionner, date_reception, cout, creer_par) 
                VALUES ('$id_materiel_topo', '$date_transfert', '$id_provenance', '$id_destination', '$num_bt', '$bon_transfert', '$receptionner', '$date_reception', '$cout', '$creer_par')";

        try {
            $conn->query($sql);
            $success = "Transfert enregistré avec succès.";
            if ($receptionner) {
                $sqlUpdate = "UPDATE materiel_topo SET id_chantier = '$id_destination' WHERE id = '$id_materiel_topo'";
                $conn->query($sqlUpdate);
            }
        } catch (mysqli_sql_exception $e) {
            $error = "Erreur lors de l'enregistrement du transfert: " . $e->getMessage();
        }
    }
}

$sqlMateriels = "SELECT id, code, description, id_chantier FROM materiel_topo";
$resultMateriels = $conn->query($sqlMateriels);

$sqlChantiers = "SELECT id, chantier FROM chantiers";
$resultChantiers = $conn->query($sqlChantiers);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrer un Transfert</title>
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
        .modal {
            display: none; 
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
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
                                        <li><a href="../materiel_topo/list_materiel.php">fiche de suivi</a></li>
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
                    <h1>Enregistrer un Transfert</h1>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <form action="add.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="id_materiel_topo">Matériel *</label>
                            <select class="form-control" id="id_materiel_topo" name="id_materiel_topo" required>
                                <option value="">Sélectionnez le matériel</option>
                                <?php while ($materiel = $resultMateriels->fetch_assoc()): ?>
                                    <option value="<?php echo $materiel['id']; ?>" data-chantier="<?php echo $materiel['id_chantier']; ?>"><?php echo $materiel['code'] . ' - ' . $materiel['description']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_transfert">Date Transfert *</label>
                            <input type="date" class="form-control" id="date_transfert" name="date_transfert" required>
                        </div>
                        <div class="form-group">
                            <label for="id_provenance">Provenance *</label>
                            <input type="text" class="form-control" id="id_provenance_display" readonly>
                            <input type="hidden" id="id_provenance" name="id_provenance" required>
                        </div>
                        <div class="form-group">
                            <label for="id_destination">Destination *</label>
                            <select class="form-control" id="id_destination" name="id_destination" required>
                                <option value="">Sélectionnez la destination</option>
                                <?php
                                $resultChantiers->data_seek(0);
                                while ($chantier = $resultChantiers->fetch_assoc()): ?>
                                    <option value="<?php echo $chantier['id']; ?>"><?php echo $chantier['chantier']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="num_bt">Numéro BT *</label>
                            <input type="text" class="form-control" id="num_bt" name="num_bt" required>
                        </div>
                        <div class="form-group">
                            <label for="bon_transfert">Bon Transfert</label>
                            <input type="file" class="form-control" id="bon_transfert" name="bon_transfert">
                        </div>
                        <?php if ($_SESSION['privilege'] === 'admin') { ?>
                        <div class="form-group">
                            <label for="receptionner">Réceptionné</label>
                            <input type="checkbox" id="receptionner" name="receptionner">
                        </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="date_reception">Date Réception</label>
                            <input type="date" class="form-control" id="date_reception" name="date_reception">
                        </div>
                        <div class="form-group">
                            <label for="cout">Coût</label>
                            <input type="number" step="0.01" class="form-control" id="cout" name="cout">
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

    <script>
    $(document).ready(function() {
        $('#id_materiel_topo').on('change', function() {
            var materielId = $(this).val();
            if (materielId) {
                $.ajax({
                    url: 'get_provenance.php',
                    method: 'POST',
                    data: { id: materielId },
                    dataType: 'json',
                    success: function(data) {
                        $('#id_provenance').val(data.id_chantier);
                        $('#id_provenance_display').val(data.provenance);
                    }
                });
            } else {
                $('#id_provenance').val('');
                $('#id_provenance_display').val('');
            }
        });

        $('#submitBtn').on('click', function(event) {
            var dateTransfert = new Date($('#date_transfert').val());
            var dateReception = new Date($('#date_reception').val());
            var errors = [];

            if (dateReception < dateTransfert) {
                errors.push("La date de réception ne peut pas être antérieure à la date de transfert.");
            }

            var fileInput = $('#bon_transfert')[0];
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

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

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
