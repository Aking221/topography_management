<?php
include '../../includes/auth.php';
requireLogin();
include '../../includes/header.php';
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    $stmt = $conn->prepare("INSERT INTO interventions (type_intervention, id_materiel_topo, date_intervention, intervenant, sous_traitant, nature_intervention, reference, tolerance, duree_validite, date_fin_validite, cout, fiche, observation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssssiissss", $type_intervention, $id_materiel_topo, $date_intervention, $intervenant, $sous_traitant, $nature_intervention, $reference, $tolerance, $duree_validite, $date_fin_validite, $cout, $fiche, $observation);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Intervention ajoutée avec succès.</div>";
    } else {
        echo "<div class='alert error'>Erreur: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="home-content">
    <div class="box">
        <div class="title">Ajouter une nouvelle intervention</div>
        <form method="POST" action="add.php">
            <div class="form-group">
                <label for="type_intervention">Type d'intervention:</label><br>
                <input type="text" id="type_intervention" name="type_intervention" required><br><br>
            </div>
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
                <label for="date_intervention">Date d'intervention:</label><br>
                <input type="date" id="date_intervention" name="date_intervention" required><br><br>
            </div>
            <div class="form-group">
                <label for="intervenant">Intervenant:</label><br>
                <input type="text" id="intervenant" name="intervenant" required><br><br>
            </div>
            <div class="form-group">
                <label for="sous_traitant">Sous-traitant:</label><br>
                <input type="text" id="sous_traitant" name="sous_traitant"><br><br>
            </div>
            <div class="form-group">
                <label for="nature_intervention">Nature de l'intervention:</label><br>
                <input type="text" id="nature_intervention" name="nature_intervention" required><br><br>
            </div>
            <div class="form-group">
                <label for="reference">Référence:</label><br>
                <input type="text" id="reference" name="reference"><br><br>
            </div>
            <div class="form-group">
                <label for="tolerance">Tolérance:</label><br>
                <input type="number" id="tolerance" name="tolerance"><br><br>
            </div>
            <div class="form-group">
                <label for="duree_validite">Durée de validité (en mois):</label><br>
                <input type="number" id="duree_validite" name="duree_validite"><br><br>
            </div>
            <div class="form-group">
                <label for="date_fin_validite">Date de fin de validité:</label><br>
                <input type="date" id="date_fin_validite" name="date_fin_validite"><br><br>
            </div>
            <div class="form-group">
                <label for="cout">Coût:</label><br>
                <input type="number" id="cout" name="cout"><br><br>
            </div>
            <div class="form-group">
                <label for="fiche">Fiche:</label><br>
                <input type="text" id="fiche" name="fiche"><br><br>
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
