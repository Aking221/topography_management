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

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $materiel = addslashes($_POST['materiel']);
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
    $content_dir = '../../uploads/';
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

    // Generate the correct `code_materiel`
    $id_famille_topo = (int) $_POST['id_famille_topo'];
    $code_materiel = '';

    $sousreq = "SELECT code FROM materiel_topo WHERE id_famille_topo = $id_famille_topo ORDER BY id DESC LIMIT 1";
    $result_req = mysqli_query($conn, $sousreq) or die(mysqli_error($conn));
    if ($result_req && mysqli_num_rows($result_req) > 0) {
        $response = mysqli_fetch_array($result_req);
        $num_increment = $response['code'];
        $chain = explode("-", $num_increment);
        $abv = $chain[0];
        $numincrem = (int)$chain[1] + 1;
        $code_materiel = $abv . "-" . str_pad($numincrem, 5, '0', STR_PAD_LEFT);
    } else {
        $famille_req = "SELECT abv FROM familles_topo WHERE id = $id_famille_topo";
        $famille_res = mysqli_query($conn, $famille_req) or die(mysqli_error($conn));
        $famille_data = mysqli_fetch_array($famille_res);
        $abv = $famille_data['abv'];
        $code_materiel = $abv . "-00001";
    }

    $add_mat = "INSERT INTO materiel_topo (id_famille_topo, code, description, marque, num_serie, date_acquisition, cout_acquisition, id_fournisseur, num_bc, fiche_bl, date_mise_service, etat, id_chantier, date_affectation, creer_par, observation) 
                VALUES ('$id_famille_topo', '$code_materiel', '$description', '$marque', '$num_serie', '$date_acquisition', '$cout_acquisition', '$id_fournisseur', '$bon_commande', '$bl', '$date_service', '$etat_materiel', '$id_chantier', '$date_affectation', '$auteur', 'Aucune')";

    $result = mysqli_query($conn, $add_mat) or die(mysqli_error($conn));

    if ($result) {
        header("location:add.php?add=ok");
    } else {
        header("location:add.php?erreur=existe");
    }
}
?>
