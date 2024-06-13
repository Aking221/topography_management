<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materiel_topo = $_POST['id_materiel_topo'];
    $date_reforme = $_POST['date_reforme'];
    $raison = $_POST['raison'];
    $id_destination = $_POST['id_destination'];
    $observation = $_POST['observation'];
    $creer_par = $_POST['creer_par'];

    $stmt = $conn->prepare("INSERT INTO reforme_materiel (id_materiel_topo, date_reforme, raison, id_destination, observation, creer_par) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $id_materiel_topo, $date_reforme, $raison, $id_destination, $observation, $creer_par);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Réforme ajoutée avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Ajouter une nouvelle réforme</div>
        <form method="POST" action="add.php">
            <div class="form-group">
                <label for="id_materiel_topo">Matériel:</label><br>
                <select id="id_materiel_topo" name="id_materiel_topo" required>
                    <?php
                    $result = $conn->query("SELECT id, code FROM materiel_topo");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['code'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="date_reforme">Date de réforme:</label><br>
                <input type="date" id="date_reforme" name="date_reforme" required><br><br>
            </div>
            <div class="form-group">
                <label for="raison">Raison:</label><br>
                <textarea id="raison" name="raison" required></textarea><br><br>
            </div>
            <div class="form-group">
                <label for="id_destination">Destination:</label><br>
                <input type="text" id="id_destination" name="id_destination" required><br><br>
            </div>
            <div class="form-group">
                <label for="observation">Observation:</label><br>
                <textarea id="observation" name="observation"></textarea><br><br>
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
