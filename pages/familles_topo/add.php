<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $materiel = $_POST['materiel'];
    $abv = $_POST['abv'];
    $active = isset($_POST['active']) ? 1 : 0;
    $observation = $_POST['observation'];

    $stmt = $conn->prepare("INSERT INTO familles_topo (materiel, abv, active, observation) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $materiel, $abv, $active, $observation);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Famille ajoutée avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Ajouter une nouvelle famille de matériel topographique</div>
        <form method="POST" action="add.php">
            <div class="form-group">
                <label for="materiel">Nom de la famille:</label><br>
                <input type="text" id="materiel" name="materiel" required><br><br>
            </div>
            <div class="form-group">
                <label for="abv">Abréviation:</label><br>
                <input type="text" id="abv" name="abv" required><br><br>
            </div>
            <div class="form-group">
                <label for="active">Actif:</label><br>
                <input type="checkbox" id="active" name="active" value="1"><br><br>
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

