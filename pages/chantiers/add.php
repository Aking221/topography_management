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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_devis = $_POST['date_devis'];
    $fournisseur = addslashes($_POST['fournisseur']);
    $num_devis = addslashes($_POST['num_devis']);
    $materiel = addslashes($_POST['materiel']);
    $montant_euro = isset($_POST['montant_euro']) ? addslashes($_POST['montant_euro']) : NULL;
    $montant_cfa = addslashes($_POST['montant_cfa']);
    $chantier = addslashes($_POST['chantier']);
    $num_bc = isset($_POST['num_bc']) ? addslashes($_POST['num_bc']) : NULL;
    $date_bc = isset($_POST['date_bc']) ? $_POST['date_bc'] : NULL;
    $avance_montant = isset($_POST['avance_montant']) ? addslashes($_POST['avance_montant']) : NULL;
    $date_avance = isset($_POST['date_avance']) ? $_POST['date_avance'] : NULL;
    $date_paiement_solde = isset($_POST['date_paiement_solde']) ? $_POST['date_paiement_solde'] : NULL;
    $num_semaines = isset($_POST['num_semaines']) ? $_POST['num_semaines'] : NULL;
    $date_livraison_prevue = isset($_POST['date_livraison_prevue']) ? $_POST['date_livraison_prevue'] : NULL;
    $date_reception = isset($_POST['date_reception']) ? $_POST['date_reception'] : NULL;
    $conformite = isset($_POST['conformite']) ? addslashes($_POST['conformite']) : NULL;
    $date_fin_garantie = isset($_POST['date_fin_garantie']) ? $_POST['date_fin_garantie'] : NULL;
    $fichier = '';

    $content_dir = '../../uploads/';
    $tmp_file = $_FILES['fichier']['tmp_name'];

    if ($tmp_file != "") {
        if (!is_uploaded_file($tmp_file)) {
            exit("Le fichier est introuvable");
        }
        $type_file = $_FILES['fichier']['type'];
        if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'png') && !strstr($type_file, 'bmp') && !strstr($type_file, 'pdf')) {
            exit("Le fichier doit être une image ou un pdf");
        }
        $name_file = $_FILES['fichier']['name'];
        $extension = pathinfo($name_file, PATHINFO_EXTENSION);
        $filename = pathinfo($name_file, PATHINFO_FILENAME);
        $nomDestination = $filename . "_" . date("YmdHis") . '.' . $extension;
        if (!move_uploaded_file($tmp_file, $content_dir . $nomDestination)) {
            exit("Impossible de copier le fichier dans $content_dir");
        }
        $fichier = $nomDestination;
    }

    // Start a transaction
    mysqli_begin_transaction($conn);

    // Insert new command
    $add_commande = "INSERT INTO commandes (date_devis, fournisseur, num_devis, materiel, montant_euro, montant_cfa, chantier, num_bc, date_bc, avance_montant, date_avance, date_paiement_solde, num_semaines, date_livraison_prevue, date_reception, conformite, date_fin_garantie, fichier) 
                     VALUES (
                         '$date_devis', 
                         '$fournisseur', 
                         '$num_devis', 
                         '$materiel', 
                         '$montant_euro', 
                         '$montant_cfa', 
                         (SELECT chantier FROM chantiers WHERE chantier = '$chantier'), 
                         '$num_bc', 
                         '$date_bc', 
                         '$avance_montant', 
                         '$date_avance', 
                         '$date_paiement_solde', 
                         '$num_semaines', 
                         '$date_livraison_prevue', 
                         '$date_reception', 
                         '$conformite', 
                         '$date_fin_garantie', 
                         '$fichier'
                     )";

    $result = mysqli_query($conn, $add_commande);

    if ($result) {
        // Update the devis counter
        $update_devis_counter = "UPDATE devis_counter SET last_num_devis = '$num_devis' WHERE id = (SELECT MAX(id) FROM devis_counter)";
        $result_counter = mysqli_query($conn, $update_devis_counter);

        if ($result_counter) {
            // Commit transaction
            mysqli_commit($conn);
            header("Location: add.php?add=ok");
        } else {
            // Rollback transaction
            mysqli_rollback($conn);
            header("Location: add.php?erreur=existe");
        }
    } else {
        // Rollback transaction
        mysqli_rollback($conn);
        header("Location: add.php?erreur=existe");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement d'une nouvelle commande</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../vendors/animate.css/animate.min.css" rel="stylesheet">
    <link href="../../build/css/custom.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
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
                    <h1>Enregistrement d'une nouvelle commande</h1>
                    <form action="add.php" method="POST" id="commande-form" class="form-horizontal form-label-left" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_devis">Date du devis *</label>
                                    <input type="date" id="date_devis" name="date_devis" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="fournisseur">Fournisseur *</label>
                                    <input type="text" list="listefournisseur" id="fournisseur" name="fournisseur" required class="form-control">
                                    <datalist id="listefournisseur">
                                        <?php 
                                        $req_four = "SELECT id, fournisseur FROM fournisseurs";
                                        $reponse = mysqli_query($conn, $req_four) or die(mysqli_error($conn));
                                        while($response2 = mysqli_fetch_array($reponse)) {         
                                        ?>
                                        <option value="<?php echo $response2['fournisseur']; ?>"><?php echo $response2['fournisseur']; ?></option>
                                        <?php } ?>
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <label for="num_devis">Numéro du devis *</label>
                                    <input type="text" id="num_devis" name="num_devis" required class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="materiel">Matériel *</label>
                                    <input type="text" list="listemateriel" id="materiel" name="materiel" required="required" class="form-control">
                                    <datalist id="listemateriel">
                                        <?php 
                                        $req_mat = "SELECT id, materiel FROM familles_topo";
                                        $reponse = mysqli_query($conn, $req_mat) or die(mysqli_error($conn));
                                        while($response2 = mysqli_fetch_array($reponse)) {         
                                        ?>
                                        <option value="<?php echo $response2['materiel']; ?>"><?php echo $response2['materiel']; ?></option>
                                        <?php } ?>
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <label for="montant_euro">Montant en Euro</label>
                                    <input type="text" id="montant_euro" name="montant_euro" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="montant_cfa">Montant en CFA *</label>
                                    <input type="text" id="montant_cfa" name="montant_cfa" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="chantier">Chantier *</label>
                                    <input type="text" list="listechantier" id="chantier" name="chantier" required class="form-control">
                                    <datalist id="listechantier">
                                        <?php 
                                        $req_chant = "SELECT id, chantier FROM chantiers";
                                        $reponse = mysqli_query($conn, $req_chant) or die(mysqli_error($conn));
                                        while($response2 = mysqli_fetch_array($reponse)) {         
                                        ?>
                                        <option value="<?php echo $response2['chantier']; ?>"><?php echo $response2['chantier']; ?></option>
                                        <?php } ?>
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="num_bc">Numéro BC</label>
                                    <input type="text" id="num_bc" name="num_bc" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="date_bc">Date BC</label>
                                    <input type="date" id="date_bc" name="date_bc" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="avance_montant">Avance Montant</label>
                                    <input type="text" id="avance_montant" name="avance_montant" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="date_avance">Date Avance</label>
                                    <input type="date" id="date_avance" name="date_avance" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="date_paiement_solde">Date Paiement Solde</label>
                                    <input type="date" id="date_paiement_solde" name="date_paiement_solde" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="num_semaines">Délai en semaines</label>
                                    <input type="number" id="num_semaines" name="num_semaines" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="date_livraison_prevue">Date Livraison Prévue</label>
                                    <input type="date" id="date_livraison_prevue" name="date_livraison_prevue" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="date_reception">Date Réception</label>
                                    <input type="date" id="date_reception" name="date_reception" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="conformite">Conformité</label>
                                    <input type="text" id="conformite" name="conformite" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="date_fin_garantie">Date Fin Garantie</label>
                                    <input type="date" id="date_fin_garantie" name="date_fin_garantie" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="fichier">Fichier</label>
                                    <input type="file" id="fichier" name="fichier" class="form-control">
                                </div>
                            </div>
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

    <script>
        $(document).ready(function() {
            // Fetch the latest num_devis and set the new one
            $.ajax({
                url: 'get_latest_num_devis.php',
                type: 'GET',
                success: function(response) {
                    $('#num_devis').val(response);
                }
            });

            // Convert Euro to CFA based on a fixed rate or API
            $('#montant_euro').on('input', function() {
                var euroValue = $(this).val();
                if (euroValue) {
                    var cfaValue = euroValue * 655.957; // Example conversion rate
                    $('#montant_cfa').val(cfaValue.toFixed(2));
                } else {
                    $('#montant_cfa').val('');
                }
            });

            // Calculate and set the date of delivery based on delay in weeks
            $('#num_semaines').on('input', function() {
                var numWeeks = $(this).val();
                if (numWeeks) {
                    var dateDevis = new Date($('#date_devis').val());
                    var deliveryDate = new Date(dateDevis.setDate(dateDevis.getDate() + numWeeks * 7));
                    $('#date_livraison_prevue').val(deliveryDate.toISOString().split('T')[0]);
                } else {
                    $('#date_livraison_prevue').val('');
                }
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

            // Validate coherence of dates
            $('#commande-form').on('submit', function(event) {
                var dateDevis = new Date($('#date_devis').val());
                var dateBC = new Date($('#date_bc').val());
                var dateAvance = new Date($('#date_avance').val());
                var datePaiementSolde = new Date($('#date_paiement_solde').val());
                var dateLivraisonPrevue = new Date($('#date_livraison_prevue').val());
                var dateReception = new Date($('#date_reception').val());
                var dateFinGarantie = new Date($('#date_fin_garantie').val());

                var errors = [];

                if (dateBC < dateDevis) {
                    errors.push("La date BC ne peut pas être antérieure à la date du devis.");
                }
                if (dateAvance < dateDevis) {
                    errors.push("La date d'avance ne peut pas être antérieure à la date du devis.");
                }
                if (datePaiementSolde < dateDevis) {
                    errors.push("La date de paiement du solde ne peut pas être antérieure à la date du devis.");
                }
                if (dateReception < dateLivraisonPrevue) {
                    errors.push("La date de réception ne peut pas être antérieure à la date de livraison prévue.");
                }
                if (dateFinGarantie < dateReception) {
                    errors.push("La date de fin de garantie ne peut pas être antérieure à la date de réception.");
                }

                if (errors.length > 0) {
                    event.preventDefault();
                    alert(errors.join("\n"));
                }
            });
        });
    </script>
</body>
</html>
