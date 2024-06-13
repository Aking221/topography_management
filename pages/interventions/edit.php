<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM interventions WHERE id = $id");
    $intervention = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $type_intervention = $_POST['type_intervention'];
    $id_materiel_topo = $_POST['id_materiel_topo'];
    $date_intervention = $_POST['date_intervention'];
    $intervenant = $_POST['intervenant'];
    $sous_traitant = $_POST['sous_traitant'];
    $nature_intervention = $_POST['nature_intervention'];
    $reference = $_POST['reference'];
    $tolerance = $_POST['tolerance'];
    $duree_validite = $_POST['duree_validite'];
    $date_fin_validite = $_POST['date_fin_validite'];
    $cout = $_POST['cout'];
    $fiche = $_POST['fiche'];
    $observation = $_POST['observation'];

    $stmt = $conn->prepare("UPDATE interventions SET type_intervention = ?, id_materiel_topo = ?, date_intervention = ?, intervenant = ?, sous_traitant = ?, nature_intervention = ?, reference = ?, tolerance = ?, duree_validite = ?, date_fin_validite = ?, cout = ?, fiche = ?, observation = ? WHERE id = ?");
    $stmt->bind_param("sisssssiissssi", $type_intervention, $id_materiel_topo, $date_intervention, $intervenant, $sous_traitant, $nature_intervention, $reference, $tolerance, $duree_validite, $date_fin_validite, $cout, $fiche, $observation, $id);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Intervention mise à jour avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Modifier l'intervention</div>
        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <input type="hidden" name="id" value="<?php echo $intervention['id']; ?>">
            <div class="form-group">
                <label for="type_intervention">Type d'intervention:</label><br>
                <input type="text" id="type_intervention" name="type_intervention" value="<?php echo $intervention['type_intervention']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="id_materiel_topo">Matériel:</label><br>
                <select id="id_materiel_topo" name="id_materiel_topo" required>
                    <?php
                    $result = $conn->query("SELECT id, code FROM materiel_topo");
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] == $intervention['id_materiel_topo']) ? "selected" : "";
                        echo "<option value='" . $row['id'] . "' $selected>" . $row['code'] . "</option>";
                    }
                    ?>
                </select><br><br>
            </div>
            <div class="form-group">
                <label for="date_intervention">Date d'intervention:</label><br>
                <input type="date" id="date_intervention" name="date_intervention" value="<?php echo $intervention['date_intervention']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="intervenant">Intervenant:</label><br>
                <input type="text" id="intervenant" name="intervenant" value="<?php echo $intervention['intervenant']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="sous_traitant">Sous-traitant:</label><br>
                <input type="text" id="sous_traitant" name="sous_traitant" value="<?php echo $intervention['sous_traitant']; ?>"><br><br>
            </div>
            <div class="form-group">
                <label for="nature_intervention">Nature de l'intervention:</label><br>
                <input type="text" id="nature_intervention" name="nature_intervention" value="<?php echo $intervention['nature_intervention']; ?>" required><br><br>
            </div>
            <div class="form-group">
                <label for="reference">Référence:</label><br>
                <input type="text" id="reference" name="reference" value="<?php echo $intervention['reference']; ?>"><br><br>
            </div>
            <div class="form-group">
                <label for="tolerance">Tolérance:</label><br>
                <input type="number" id="tolerance" name="tolerance" value="<?php echo $intervention['tolerance']; ?>"><br><br>
            </div>
            <div class="form-group">
                <label for="duree_validite">Durée de validité (en mois):</label><br>
                <input type="number" id="duree_validite" name="duree_validite" value="<?php echo $intervention['duree_validite']; ?>"><br><br>
            </div>
            <div class="form-group">
                <label for="date_fin_validite">Date de fin de validité:</label><br>
                <input type="date" id="date_fin_validite" name="date_fin_validite" value="<?php echo $intervention['date_fin_validite']; ?>"><br><br>
            </div>
            <div class="form-group">
                <label for="cout">Coût:</label><br>
                <input type="number" id="cout" name="cout" value="<?php echo $intervention['cout']; ?>"><br><br>
            </div>
            <div class="form-group">
                <label for="fiche">Fiche:</label><br>
                <input type="text" id="fiche" name="fiche" value="<?php echo $intervention['fiche']; ?>"><br><br>
            </div>
            <div class="form-group">
                <label for="observation">Observation:</label><br>
                <textarea id="observation" name="observation"><?php echo $intervention['observation']; ?></textarea><br><br>
            </div>
            <div class="form-group">
                <input type="submit" value="Mettre à jour">
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
