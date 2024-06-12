<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $nom_complet = $_POST['nom_complet'];
    $telephone = $_POST['telephone'];
    $groupe = $_POST['groupe'];
    $privilege = $_POST['privilege'];
    $code_chantier = $_POST['code_chantier'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO utilisateurs (email, nom_complet, telephone, groupe, privilege, code_chantier, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt= $conn->prepare($sql);
    $stmt->execute([$email, $nom_complet, $telephone, $groupe, $privilege, $code_chantier, $password]);
    
    echo "connection reussie .Bienvenue $nom_complet.";
}
?>
<form method="POST" action="">
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
    
    <label for="code_chantier">Code Chantier:</label>
    <input type="text" name="code_chantier" required>
    
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    
    <button type="submit">Register</button>
</form>

