<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../registerphp/login.php');
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
  <style>
  .toggle-header {
    cursor: pointer;
  }
  </style>

</head>
<body>

  <div class="sidebar window">
    <div class="title-bar">
      <div class="title-bar-text">Admin</div>
    </div>
    <div class="window-body">
      <h4>Compte</h4>
      <ul>
        <li><b>Home</b></li>
        <li class="browse-item">🔍 Browse</li>
        <li><a href="compte.php">Profil</a></li>
        <li><a href="../index.php">Accéder au gestionnaires de fichiers</a></li>
      </ul>
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
        <?php 
        if(!empty($_GET["success"])) {
          if($_GET["success"] == "success") {
            echo "<b><p>Les données ont bien été modifiées avec succès</b></p>";
          }
        };
        ?>
        <div class="field-row-stacked">
          <label>Comptes utilisateurs :</label>
          <?php include '../compte/edit/gestioncompte.php'; ?>
        </div>
      </div>
    </div>
  </div>
<script src="../script.js"></script>
<script>
  // Pour gérer l’ouverture/fermeture du bloc
  document.querySelectorAll('.toggle-header').forEach(header => {
    header.addEventListener('click', () => {
      const details = header.nextElementSibling;
      const arrow = header.querySelector('.arrow');

      const isVisible = details.style.display === 'block';
      details.style.display = isVisible ? 'none' : 'block';
      arrow.textContent = isVisible ? '▶' : '▼';
    });
  });
</script>

</body>
</html>
