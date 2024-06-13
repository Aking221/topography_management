<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM materiel_topo WHERE id = $id");
    $materiel = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $code = $_POST['code'];
    $id_famille_topo = $_POST['id_famille_topo'];
    $description = $_POST['description'];
    $marque = $_POST['marque'];
    $num_serie = $_POST['num_serie'];
    $date_acquisition = $_POST['date_acquisition'];
    $cout_acquisition = $_POST['cout_acquisition'];
    $id_fournisseur = $_POST['id_fournisseur'];
    $num_bc = $_POST['num_bc'];
    $fiche_bl = $_POST['fiche_bl'];
    $date_mise_service = $_POST['date_mise_service'];
    $etat = $_POST['etat'];
    $id_chantier = $_POST['id_chantier'];
    $date_affectation = $_POST['date_affectation'];
    $creer_par = $_POST['creer_par'];
    $observation = $_POST['observation'];

    $stmt = $conn->prepare("UPDATE materiel_topo SET code = ?, id_famille_topo = ?, description = ?, marque = ?, num_serie = ?, date_acquisition = ?, cout_acquisition = ?, id_fournisseur = ?, num_bc = ?, fiche_bl = ?, date_mise_service = ?, etat = ?, id_chantier = ?, date_affectation = ?, creer_par = ?, observation = ? WHERE id = ?");
    $stmt->bind_param("sisssisdssssisssi", $code, $id_famille_topo, $description, $marque, $num_serie, $date_acquisition, $cout_acquisition, $id_fournisseur, $num_bc, $fiche_bl, $date_mise_service, $etat, $id_chantier, $date_affectation, $creer_par, $observation, $id);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Matériel mis à jour avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Modifier le matériel topographique</div>
        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <input type="hidden" name="id" value="<?php echo $materiel['id']; ?>">
            <div class="form-group">
                <label for="code">Code:</label><br>
                <input type="text" id="code" name="code" value="<?php echo $materiel['code']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="id_famille_topo">Famille:</label><br>
                <select id="id_famille_topo" name="id_famille_topo" required>
                    <?php
                    $result = $conn->query("SELECT id, materiel FROM familles_topo");
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] == $materiel['id_famille_topo']) ? "selected" : "";
                        echo "<option value='" . $row['id'] . "' $selected>" . $row['materiel'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="description">Description:</label><br>
                <textarea id="description" name="description" required><?php echo $materiel['description']; ?></textarea><br><br>
            </div>
            <div class="form-group">
                <label for="marque">Marque:</label><br>
                <input type="text" id="marque" name="marque" value="<?php echo $materiel['marque']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="num_serie">Numéro de série:</label><br>
                <input type="text" id="num_serie" name="num_serie" value="<?php echo $materiel['num_serie']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="date_acquisition">Date d'acquisition:</label><br>
                <input type="date" id="date_acquisition" name="date_acquisition" value="<?php echo $materiel['date_acquisition']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="cout_acquisition">Coût d'acquisition:</label><br>
                <input type="text" id="cout_acquisition" name="cout_acquisition" value="<?php echo $materiel['cout_acquisition']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="id_fournisseur">Fournisseur:</label><br>
                <select id="id_fournisseur" name="id_fournisseur" required>
                    <?php
                    $result = $conn->query("SELECT id, fournisseur FROM fournisseurs");
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] == $materiel['id_fournisseur']) ? "selected" : "";
                        echo "<option value='" . $row['id'] . "' $selected>" . $row['fournisseur'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="num_bc">Numéro BC:</label><br>
                <input type="text" id="num_bc" name="num_bc" value="<?php echo $materiel['num_bc']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="fiche_bl">Fiche BL:</label><br>
                <input type="text" id="fiche_bl" name="fiche_bl" value="<?php echo $materiel['fiche_bl']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="date_mise_service">Date de mise en service:</label><br>
                <input type="date" id="date_mise_service" name="date_mise_service" value="<?php echo $materiel['date_mise_service']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="etat">État:</label><br>
                <input type="text" id="etat" name="etat" value="<?php echo $materiel['etat']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="id_chantier">Chantier:</label><br>
                <select id="id_chantier" name="id_chantier" required>
                    <?php
                    $result = $conn->query("SELECT id, chantier FROM chantiers");
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] == $materiel['id_chantier']) ? "selected" : "";
                        echo "<option value='" . $row['id'] . "' $selected>" . $row['chantier'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="date_affectation">Date d'affectation:</label><br>
                <input type="date" id="date_affectation" name="date_affectation" value="<?php echo $materiel['date_affectation']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="creer_par">Créé par:</label><br>
                <input type="text" id="creer_par" name="creer_par" value="<?php echo $materiel['creer_par']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="observation">Observation:</label><br>
                <textarea id="observation" name="observation"><?php echo $materiel['observation']; ?></textarea><br><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Mettre à jour">
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

