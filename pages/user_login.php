<?php
include '../includes/db.php';
include '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Email ou mot de passe invalide.";
        }
    } else {
        $error = "Email ou mot de passe invalide.";
    }
    $stmt->close();
}
?>

<form method="POST" action="user_login.php">
    <label for="email">E-mail:</label>
    <input type="email" name="email" required>
    
    <label for="password">Mot de passe:</label>
    <input type="password" name="password" required>
    
    <button type="submit">Se connecter</button>
</form>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo $error; ?></div>
<?php endif; ?>
