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
  <title>Admin - Gestion des comptes</title>
  <link rel="stylesheet" href="../style/stylecompte.css">
  <link rel="stylesheet" href="https://unpkg.com/98.css">

</head>
<body>

  <div class="sidebar window">
    <div class="title-bar">
      <div class="title-bar-text">Admin</div>
    </div>
    <div class="sidebar-body">
      <fieldset class="nav-section">
        <legend>Navigation</legend>
        <ul class="nav-list">
          <li class="nav-item current">
            <img src="../icon/admin.svg" alt="" class="nav-icon">
            <span>Administration</span>
          </li>
          <li class="nav-item">
            <a href="../compte/">
              <img src="../icon/user.svg" alt="" class="nav-icon">
              <span>Mon profil</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="../index.php">
              <img src="../icon/folder.svg" alt="" class="nav-icon">
              <span>Fichiers</span>
            </a>
          </li>
        </ul>
      </fieldset>
      <fieldset class="nav-section">
        <legend>Outils admin</legend>
        <ul class="nav-list">
          <li class="nav-item">
            <a href="./users/">
              <img src="../icon/users.svg" alt="" class="nav-icon">
              <span>Utilisateurs</span>
            </a>
          </li>
        </ul>
      </fieldset>
    </div>
  </div>

  <div class="main">
      <div class="header">
        <button class="button" id="updateDeconnection" onclick="toggleDeconnectionButton(baseDir)">Déconnexion</button>
      </div>

    <div class="window" style="width: 100%;">
      <div class="title-bar">
        <div class="title-bar-text">Gestion des comptes</div>
      </div>
      <div class="window-body">
        <p><b>Admin <?php echo htmlspecialchars($_SESSION['username']); ?></b></p>
        <div class="field-row-stacked">
          <label>Comptes utilisateurs :</label>
          <?php include 'users/index.php'; ?>
        </div>
      </div>
    </div>
  </div>
  <script src="../script.js"></script>


</body>
</html>
