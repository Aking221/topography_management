<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM reforme_materiel WHERE id = $id");
    $reforme = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $id_materiel_topo = $_POST['id_materiel_topo'];
    $date_reforme = $_POST['date_reforme'];
    $raison = $_POST['raison'];
    $id_destination = $_POST['id_destination'];
    $observation = $_POST['observation'];
    $creer_par = $_POST['creer_par'];

    $stmt = $conn->prepare("UPDATE reforme_materiel SET id_materiel_topo = ?, date_reforme = ?, raison = ?, id_destination = ?, observation = ?, creer_par = ? WHERE id = ?");
    $stmt->bind_param("isssssi", $id_materiel_topo, $date_reforme, $raison, $id_destination, $observation, $creer_par, $id);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Réforme mise à jour avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Modifier la réforme</div>
        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <input type="hidden" name="id" value="<?php echo $reforme['id']; ?>">
            <div class="form-group">
                <label for="id_materiel_topo">Matériel:</label><br>
                <select id="id_materiel_topo" name="id_materiel_topo" required>
                    <?php
                    $result = $conn->query("SELECT id, code FROM materiel_topo");
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] == $reforme['id_materiel_topo']) ? "selected" : "";
                        echo "<option value='" . $row['id'] . "' $selected>" . $row['code'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="date_reforme">Date de réforme:</label><br>
                <input type="date" id="date_reforme" name="date_reforme" value="<?php echo $reforme['date_reforme']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="raison">Raison:</label><br>
                <textarea id="raison" name="raison" required><?php echo $reforme['raison']; ?></textarea><br><br>
            </div>
            <div class="form-group">
                <label for="id_destination">Destination:</label><br>
                <input type="text" id="id_destination" name="id_destination" value="<?php echo $reforme['id_destination']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="observation">Observation:</label><br>
                <textarea id="observation" name="observation"><?php echo $reforme['observation']; ?></textarea><br><br>
            </div>
            <div class="form-group">
                <label for="creer_par">Créé par:</label><br>
                <input type="text" id="creer_par" name="creer_par" value="<?php echo $reforme['creer_par']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Mettre à jour">
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
