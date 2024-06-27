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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $code_intervenant = $_POST['code_intervenant'];
    $domaine_intervention = $_POST['domaine_intervention'];
    $date_entree_service = $_POST['date_entree_service'];
    $active = isset($_POST['active']) ? 1 : 0;
    $observation = $_POST['observation'];

    $sql = "INSERT INTO intervenants (nom, code_intervenant, domaine_intervention, date_entree_service, active, observation) 
            VALUES ('$nom', '$code_intervenant', '$domaine_intervention', '$date_entree_service', '$active', '$observation')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Nouvel intervenant ajouté avec succès.";
        header("Location: list_intervenants.php");
        exit();
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout de l'intervenant: " . $conn->error;
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
    <title>Ajouter un intervenant</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../vendors/animate.css/animate.min.css" rel="stylesheet">
    <link href="../../build/css/custom.min.css" rel="stylesheet">
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="right_col" role="main">
                <div class="container">
                    <h1>Ajouter un intervenant</h1>
                    <form action="add_intervenant.php" method="POST">
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" required="required" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="code_intervenant">Code Intervenant *</label>
                            <input type="text" id="code_intervenant" name="code_intervenant" required="required" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="domaine_intervention">Domaine d'intervention *</label>
                            <input type="text" id="domaine_intervention" name="domaine_intervention" required="required" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="date_entree_service">Date d'entrée en service *</label>
                            <input type="date" id="date_entree_service" name="date_entree_service" required="required" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="active">Active</label>
                            <input type="checkbox" id="active" name="active" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="observation">Observation</label>
                            <textarea id="observation" name="observation" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
