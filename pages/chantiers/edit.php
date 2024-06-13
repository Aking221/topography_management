<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM chantiers WHERE id = $id");
    $chantier = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $code = $_POST['code'];
    $chantier = $_POST['chantier'];
    $id_pays = $_POST['id_pays'];
    $contact = $_POST['contact'];
    $active = isset($_POST['active']) ? 1 : 0;
    $creer_par = $_POST['creer_par'];
    $observation = $_POST['observation'];

    $stmt = $conn->prepare("UPDATE chantiers SET code = ?, chantier = ?, id_pays = ?, contact = ?, active = ?, creer_par = ?, observation = ? WHERE id = ?");
    $stmt->bind_param("ssissisi", $code, $chantier, $id_pays, $contact, $active, $creer_par, $observation, $id);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Chantier mis à jour avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Modifier le chantier</div>
        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <input type="hidden" name="id" value="<?php echo $chantier['id']; ?>">
            <div class="form-group">
                <label for="code">Code:</label><br>
                <input type="text" id="code" name="code" value="<?php echo $chantier['code']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="chantier">Nom du chantier:</label><br>
                <input type="text" id="chantier" name="chantier" value="<?php echo $chantier['chantier']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="id_pays">Pays:</label><br>
                <select id="id_pays" name="id_pays" required>
                    <?php
                    $result = $conn->query("SELECT id, pays FROM pays");
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] == $chantier['id_pays']) ? "selected" : "";
                        echo "<option value='" . $row['id'] . "' $selected>" . $row['pays'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label><br>
                <input type="text" id="contact" name="contact" value="<?php echo $chantier['contact']; ?>"><br><br>
            </div>
            <div class="form-group">
                <label for="active">Actif:</label><br>
                <input type="checkbox" id="active" name="active" value="1" <?php echo ($chantier['active']) ? "checked" : ""; ?>><br><br>
            </div>
            <div class="form-group">
                <label for="creer_par">Créé par:</label><br>
                <input type="text" id="creer_par" name="creer_par" value="<?php echo $chantier['creer_par']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="observation">Observation:</label><br>
                <textarea id="observation" name="observation"><?php echo $chantier['observation']; ?></textarea><br><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Mettre à jour">
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
