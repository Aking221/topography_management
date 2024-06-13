<?php
include '../includes/db.php';
include '../includes/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $nom_complet = $_POST['nom_complet'];
    $telephone = $_POST['telephone'];
    $groupe = $_POST['groupe'];
    $privilege = $_POST['privilege'];
    $code_authent = $_POST['code_authent'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $code_chantier = $_POST['code_chantier'];
    
    $sql = "INSERT INTO utilisateurs (email, nom_complet, telephone, groupe, privilege, code_authent, password, code_chantier) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $email, $nom_complet, $telephone, $groupe, $privilege, $code_authent, $password, $code_chantier);
    
    if ($stmt->execute()) {
        echo "Utilisateur ajouté avec succès. Bienvenue $nom_complet.";
    } else {
        echo "Erreur: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<form method="POST" action="user_register.php">
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    
    <label for="nom_complet">Nom Complet:</label>
    <input type="text" name="nom_complet" required>
    
    <label for="telephone">Téléphone:</label>
    <input type="text" name="telephone" required>
    
    <label for="groupe">Groupe:</label>
    <input type="text" name="groupe" required>
    
    <label for="privilege">Privilège:</label>
    <input type="text" name="privilege" required>
    
    <label for="code_authent">Code d'authentification:</label>
    <input type="text" name="code_authent" required>
    
    <label for="password">Mot de passe:</label>
    <input type="password" name="password" required>
    
    <label for="code_chantier">Code Chantier:</label>
    <input type="text" name="code_chantier" required>
    
    <button type="submit">Enregistrer</button>
</form>
