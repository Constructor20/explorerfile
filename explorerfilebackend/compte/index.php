<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Utilisateur - Modifier les informations</title>
  <link rel="stylesheet" href="../style/stylecompte.css">
  <link rel="stylesheet" href="https://unpkg.com/98.css">
</head>
<body>

  <div class="sidebar window">
    <div class="title-bar">
      <div class="title-bar-text">Utilisateur</div>
    </div>
    <div class="sidebar-body">
      <fieldset class="nav-section">
        <legend>Navigation</legend>
        <ul class="nav-list">
          <li class="nav-item current">
            <img src="../icon/file.png" alt="" class="nav-icon">
            <span>Mon profil</span>
          </li>
          <li class="nav-item">
            <a href="../index.php">
              <img src="../icon/folder2.png" alt="" class="nav-icon">
              <span>Fichiers</span>
            </a>
          </li>
          <?php if($_SESSION['isadmin'] == 1) {
            echo '<li class="nav-item">
            <a href="../admin/">
              <img src="../icon/html.png" alt="" class="nav-icon">
              <span>Administration</span>
            </a>
          </li>';
          }?>
        </ul>
      </fieldset>
    </div>
  </div>

    <div class="main">
        <div class="header">
        <button class="button" id="updateDeconnection" onclick="toggleDeconnectionButton()">Déconnexion</button>
        </div>
        <div class="window" style="width: 100%;">
            <div class="title-bar">
                <div class="title-bar-text">Mon Profil</div>
            </div>
            <div class="window-body">
                <p><b>Utilisateur : <?php echo htmlspecialchars($_SESSION['username']);?></b></p>
                <form action="update.php" method="POST">
                    <div class="field-row-stacked">
                        <label>Nom</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" id="username">
                        <?php if(!empty($_GET["error"])) {
                            if($_GET["error"] == "username") {
                            echo "L'username est invalide ou incorrect";
                            }
                        };
                        if(!empty($_GET["success"])) {
                            if($_GET["success"] == "username") {
                            echo "L'username a été modifié avec succès";
                            }
                        };?>
                    </div>
                    <div class="field-row-stacked">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" id="email">
                        <?php if(!empty($_GET["error"])) {
                            if($_GET["error"] == "email" or $_GET["error"] == "invalid_email") {
                            echo "L'email est invalide ou incorrect";
                            }
                        };
                        if(!empty($_GET["success"])) {
                            if($_GET["success"] == "email") {
                            echo "L'email a été modifié avec succès";
                            }
                        };?>
                    </div>
                    <div class="field-row-stacked">
                        <button type="button" class="button" id="redirectioneditpswd" onclick="redirectionPswd(baseDir)">Modifier votre mot de passe</button>
                    </div>
                    <div class="field-row-stacked">
                        <input type="checkbox" id="showUpdate" onchange="toggleUpdateButton(baseDir)">
                        <label for="showUpdate">Je veux modifier mes informations</label>
                    </div>

                    <div class="field-row-stacked" id="updateButtonContainer" style="display: none;">
                        <button type="submit" class="button" name="update_account">Mettre à jour</button>
                    </div>
                </form>
                <div class="field-row-stacked">
                    <h4>Gestion des fichiers</h4>
                    <button type="button" class="button" id="gotomanagementfiles" onclick="gotomanagementfiles(baseDir)">Accéder au gestionnaire de fichiers</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../script.js"></script>
</body>
</html>
