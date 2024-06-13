<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM pays WHERE id = $id");
    $pays = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $pays = $_POST['pays'];
    $creer_par = $_POST['creer_par'];

    $stmt = $conn->prepare("UPDATE pays SET pays = ?, creer_par = ? WHERE id = ?");
    $stmt->bind_param("ssi", $pays, $creer_par, $id);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Pays mis à jour avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Modifier le pays</div>
        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <input type="hidden" name="id" value="<?php echo $pays['id']; ?>">
            <div class="form-group">
                <label for="pays">Nom du pays:</label><br>
                <input type="text" id="pays" name="pays" value="<?php echo $pays['pays']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="creer_par">Créé par:</label><br>
                <input type="text" id="creer_par" name="creer_par" value="<?php echo $pays['creer_par']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Mettre à jour">
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

