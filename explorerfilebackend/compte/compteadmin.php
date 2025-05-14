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
      </ul>
    </div>
  </div>

  <div class="main">
      <div class="header">
        <button class="button" id="updateDeconnection" onclick="toggleDeconnectionButton()">Déconnexion</button>
      </div>

    <div class="window" style="width: 100%;">
      <div class="title-bar">
        <div class="title-bar-text">Gestion des comptes</div>
      </div>
      <div class="window-body">
        <p><b>Admin <?php echo htmlspecialchars($_SESSION['username']); ?></b></p>
        <div class="field-row-stacked">
          <label>Comptes utilisateurs :</label>
          <?php include '../compte/edit/tableadmin.php'; ?>
        </div>
      </div>
    </div>
  </div>
  <script>
    function toggleDeconnectionButton() {
    window.location.href = '../logout.php';
    }
    document.querySelectorAll('.toggle-header').forEach(header => {
      header.addEventListener('click', () => {
        const arrow = header.querySelector('.arrow');
        const details = header.nextElementSibling;
        const isVisible = details.style.display === 'block';
        if(details.style.display === 'block') {
          details.style.display = 'none';
          arrow.textContent = '▶';
        } else {
          details.style.display = 'block';
          arrow.textContent = '▼';
        }
      });
    });

  </script>

</body>
</html>
