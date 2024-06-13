<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pays = $_POST['pays'];
    $creer_par = $_POST['creer_par'];

    $stmt = $conn->prepare("INSERT INTO pays (pays, creer_par) VALUES (?, ?)");
    $stmt->bind_param("ss", $pays, $creer_par);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Pays ajouté avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Ajouter un nouveau pays</div>
        <form method="POST" action="add.php">
            <div class="form-group">
                <label for="pays">Nom du pays:</label><br>
                <input type="text" id="pays" name="pays" required><br><br>
            </div>
            <div class="form-group">
                <label for="creer_par">Créé par:</label><br>
                <input type="text" id="creer_par" name="creer_par" required><br><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Ajouter">
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

