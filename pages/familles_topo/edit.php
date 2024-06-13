<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM familles_topo WHERE id = $id");
    $famille = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $materiel = $_POST['materiel'];
    $abv = $_POST['abv'];
    $active = isset($_POST['active']) ? 1 : 0;
    $observation = $_POST['observation'];

    $stmt = $conn->prepare("UPDATE familles_topo SET materiel = ?, abv = ?, active = ?, observation = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $materiel, $abv, $active, $observation, $id);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Famille mise à jour avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Modifier la famille de matériel topographique</div>
        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <input type="hidden" name="id" value="<?php echo $famille['id']; ?>">
            <div class="form-group">
                <label for="materiel">Nom de la famille:</label><br>
                <input type="text" id="materiel" name="materiel" value="<?php echo $famille['materiel']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="abv">Abréviation:</label><br>
                <input type="text" id="abv" name="abv" value="<?php echo $famille['abv']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="active">Actif:</label><br>
                <input type="checkbox" id="active" name="active" value="1" <?php echo ($famille['active']) ? "checked" : ""; ?>><br><br>
            </div>
            <div class="form-group">
                <label for="observation">Observation:</label><br>
                <textarea id="observation" name="observation"><?php echo $famille['observation']; ?></textarea><br><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Mettre à jour">
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

