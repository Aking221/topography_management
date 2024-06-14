<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = addslashes($_POST['email']);
    $pass = $_POST['pass']; // Plain password for verification

    $verif_query = "SELECT * FROM utilisateurs WHERE email='$email'";
    $verif = $conn->query($verif_query);

    if ($verif && $verif->num_rows > 0) {
        while ($data = mysqli_fetch_array($verif)) {
            if (password_verify($pass, $data['password'])) { // Using password_verify for hashing
                $_SESSION['authentification'] = TRUE;

                $_SESSION['id'] = $data['id'];
                $_SESSION['privilege'] = $data['privilege'];
                $_SESSION['nomComplet'] = $data['nom_complet'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['telephone'] = $data['telephone'];
                $_SESSION['codechantier'] = $data['code_chantier'];
                $group = $data['groupe'];

                if ($group == "super") {
                    $_SESSION['groupe'] = $group;
                    header("Location: pages/dashboard.php");
                } elseif ($group == "labo") {
                    $_SESSION['groupe'] = $group;
                    header("Location: pages/ldashboard.php");
                } else {
                    $_SESSION['groupe'] = $group;
                    header("Location: pages/dashboard.php");
                }
                exit();
            } else {
                $error = "Email ou mot de passe invalide.";
            }
        }
    } else {
        $error = "Email ou mot de passe invalide.";
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'logout') {
    unset($_SESSION['authentification']);
    header("Location: index.php?message=delog");
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gestion Labo Topo </title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
   
    <style>
    body {
        background-image: url("radisson.jpg");
        background-repeat: no-repeat;
        background-position: center ; /* Center the image horizontally and vertically */
        background-size: 100%; /* Cover the whole screen */
        height: 100vh;
        display: center;
        align-items: center;
        justify-content: center;
        margin: 0;
    }
</style>

  <body >
    <div class="test">
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form action="" method="post">
              <h1>Connexion</h1>
              <div>
                <input type="text" name="email" id="pass" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" name="pass" id="pass" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <!--<a class="btn btn-default submit" href="labo/index.html">Se connecter</a> -->
                <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
              <?php if(isset($_GET['message']) && ($_GET['message'] == "delog")) { // Affiche l'erreur ?>
                <strong style="color:green ;">D&eacute;connexion r&eacute;ussie... A bient&ocirc;t !</strong>
                <?php } ?>
                          <?php if(isset($_GET['erreur']) && ($_GET['erreur'] == "intru")) { // Affiche l'erreur ?>
                <strong style="color:red ;">Echec d'authentification !!! &gt; ou vous n'avez pas les droits pour afficher cette page</strong>
                <?php } ?><br />

                <div class="clearfix"></div>
                <br />

                <div>
                <!--   <h1>CSE</h1> -->
                  <p>©2024 All Rights Reserved. Direction des Systemes d'Information CSE</p>
                  <p><a href="https://www.groupcse.com">www.groupcse.com</a></p>
                </div>
              </div>
            </form>
          </section>
        </div>

      
      </div>
    </div>
  </body>
</html>
