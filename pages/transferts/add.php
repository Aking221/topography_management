<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materiel_topo = $_POST['id_materiel_topo'];
    $date_transfert = $_POST['date_transfert'];
    $id_provenance = $_POST['id_provenance'];
    $id_destination = $_POST['id_destination'];
    $num_bt = $_POST['num_bt'];
    $bon_transfert = $_POST['bon_transfert'];
    $receptionner = $_POST['receptionner'];
    $date_reception = $_POST['date_reception'];
    $cout = $_POST['cout'];
    $creer_par = $_POST['creer_par'];
    $observation = $_POST['observation'];

    $stmt = $conn->prepare("INSERT INTO transfert_materiel (id_materiel_topo, date_transfert, id_provenance, id_destination, num_bt, bon_transfert, receptionner, date_reception, cout, creer_par, observation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issisisdsss", $id_materiel_topo, $date_transfert, $id_provenance, $id_destination, $num_bt, $bon_transfert, $receptionner, $date_reception, $cout, $creer_par, $observation);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Transfert ajouté avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Ajouter un nouveau transfert</div>
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
                <label for="date_transfert">Date de transfert:</label><br>
                <input type="date" id="date_transfert" name="date_transfert" required><br><br>
            </div>
            <div class="form-group">
                <label for="id_provenance">Provenance:</label><br>
                <select id="id_provenance" name="id_provenance" required>
                    <?php
                    $result = $conn->query("SELECT id, chantier FROM chantiers");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['chantier'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="id_destination">Destination:</label><br>
                <select id="id_destination" name="id_destination" required>
                    <?php
                    $result = $conn->query("SELECT id, chantier FROM chantiers");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['chantier'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="num_bt">Numéro BT:</label><br>
                <input type="text" id="num_bt" name="num_bt"><br><br>
            </div>
            <div class="form-group">
                <label for="bon_transfert">Bon de transfert:</label><br>
                <input type="text" id="bon_transfert" name="bon_transfert"><br><br>
            </div>
            <div class="form-group">
                <label for="receptionner">Réceptionné par:</label><br>
                <select id="receptionner" name="receptionner" required>
                    <?php
                    $result = $conn->query("SELECT id, nom_complet FROM utilisateurs");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nom_complet'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="date_reception">Date de réception:</label><br>
                <input type="date" id="date_reception" name="date_reception"><br><br>
            </div>
            <div class="form-group">
                <label for="cout">Coût:</label><br>
                <input type="number" id="cout" name="cout"><br><br>
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

