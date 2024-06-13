<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $chantier = $_POST['chantier'];
    $id_pays = $_POST['id_pays'];
    $contact = $_POST['contact'];
    $active = isset($_POST['active']) ? 1 : 0;
    $creer_par = $_POST['creer_par'];
    $observation = $_POST['observation'];

    $stmt = $conn->prepare("INSERT INTO chantiers (code, chantier, id_pays, contact, active, creer_par, observation) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissis", $code, $chantier, $id_pays, $contact, $active, $creer_par, $observation);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Chantier ajouté avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Ajouter un nouveau chantier</div>
        <form method="POST" action="add.php">
            <div class="form-group">
                <label for="code">Code:</label><br>
                <input type="text" id="code" name="code" required><br><br>
            </div>
            <div class="form-group">
                <label for="chantier">Nom du chantier:</label><br>
                <input type="text" id="chantier" name="chantier" required><br><br>
            </div>
            <div class="form-group">
                <label for="id_pays">Pays:</label><br>
                <select id="id_pays" name="id_pays" required>
                    <?php
                    $result = $conn->query("SELECT id, pays FROM pays");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['pays'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label><br>
                <input type="text" id="contact" name="contact"><br><br>
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
