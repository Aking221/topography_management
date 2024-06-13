<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM fournisseurs WHERE id = $id");
    $fournisseur = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $fournisseur = $_POST['fournisseur'];
    $code = $_POST['code'];
    $contact = $_POST['contact'];
    $active = isset($_POST['active']) ? 1 : 0;
    $creer_par = $_POST['creer_par'];
    $observation = $_POST['observation'];

    $stmt = $conn->prepare("UPDATE fournisseurs SET fournisseur = ?, code = ?, contact = ?, active = ?, creer_par = ?, observation = ? WHERE id = ?");
    $stmt->bind_param("sssissi", $fournisseur, $code, $contact, $active, $creer_par, $observation, $id);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Fournisseur mis à jour avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Modifier le fournisseur</div>
        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <input type="hidden" name="id" value="<?php echo $fournisseur['id']; ?>">
            <div class="form-group">
                <label for="fournisseur">Nom du fournisseur:</label><br>
                <input type="text" id="fournisseur" name="fournisseur" value="<?php echo $fournisseur['fournisseur']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="code">Code:</label><br>
                <input type="text" id="code" name="code" value="<?php echo $fournisseur['code']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label><br>
                <input type="text" id="contact" name="contact" value="<?php echo $fournisseur['contact']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="active">Actif:</label><br>
                <input type="checkbox" id="active" name="active" value="1" <?php echo ($fournisseur['active']) ? "checked" : ""; ?>><br><br>
            </div>
            <div class="form-group">
                <label for="creer_par">Créé par:</label><br>
                <input type="text" id="creer_par" name="creer_par" value="<?php echo $fournisseur['creer_par']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="observation">Observation:</label><br>
                <textarea id="observation" name="observation"><?php echo $fournisseur['observation']; ?></textarea><br><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Mettre à jour">
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

