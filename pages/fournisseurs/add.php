<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fournisseur = $_POST['fournisseur'];
    $code = $_POST['code'];
    $contact = $_POST['contact'];
    $active = isset($_POST['active']) ? 1 : 0;
    $creer_par = $_POST['creer_par'];
    $observation = $_POST['observation'];

    $stmt = $conn->prepare("INSERT INTO fournisseurs (fournisseur, code, contact, active, creer_par, observation) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $fournisseur, $code, $contact, $active, $creer_par, $observation);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Fournisseur ajouté avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Ajouter un nouveau fournisseur</div>
        <form method="POST" action="add.php">
            <div class="form-group">
                <label for="fournisseur">Nom du fournisseur:</label><br>
                <input type="text" id="fournisseur" name="fournisseur" required><br><br>
            </div>
            <div class="form-group">
                <label for="code">Code:</label><br>
                <input type="text" id="code" name="code" required><br><br>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label><br>
                <input type="text" id="contact" name="contact" required><br><br>
            </div>
            <div class="form-group">
                <label for="active">Actif:</label><br>
                <input type="checkbox" id="active" name="active" value="1"><br><br>
            </div>
            <div class="form-group">
                <label for="creer_par">Créé par:</label><br>
                <input type="text" id="creer_par" name="creer_par" required><br><br>
            </div>
            <div class="form-group">
                <label for="observation">Observation:</label><br>
                <textarea id="observation" name="observation"></textarea><br><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Ajouter">
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

