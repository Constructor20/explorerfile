<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Modification de Mot De Passe</title>
  <link rel="stylesheet" href="https://unpkg.com/98.css" />
  <link rel="stylesheet" href="../../style/styleregister.css" />
</head>

<body>
  <div class="window" style="margin: auto;">
    <div class="title-bar">
      <div class="title-bar-text">Rénitialiser votre Mot de Passe</div>
      <div class="title-bar-controls">
        <button aria-label="Minimize"></button>
        <button aria-label="Maximize"></button>
        <button aria-label="Close"></button>
      </div>
    </div>
    <div class="window-body">
      <form class="register-form" method="post" action="editmdpinc.php">
        <label>
          Mot de Passe actuel:
          <input type="password" name="old_password" placeholder="Mot de passe" />
        </label>
        <span style="color: red;">
          <?php 
          if (!empty($_GET["error"])) {
            if ($_GET["error"] == "emptypassword1") {
              echo "Le champ est sois vide ou invalide";
            }
            if ($_GET["error"] == "notsamepassword") {
              echo "Veuillez saisir un autre mot de passe";
            }
          }; ?>
        </span>

        <label>
          Nouveau Mot de passe:
          <input type="password" name="new_password" placeholder="Nouveau Mot de passe" />
        </label>
        <span style="color: red;">
          <?php 
          if (!empty($_GET["error"])) {
            if ($_GET["error"] == "emptypassword2") {
              echo "Le champ est sois vide ou invalide";
            }
            if ($_GET["error"] == "notsamepassword") {
              echo "Veuillez saisir un autre mot de passe";
            }
          }; ?>
        </span>

        <label>
          Confirmation du nouveau Mot de passe:
          <input type="password" name="confirm_password" placeholder="Confirmation Mot de passe" />
        </label>
        <span style="color: red;">
          <?php 
          if (!empty($_GET["error"])) {
            if ($_GET["error"] == "emptypassword3") {
              echo "Le champ est sois vide ou invalide";
            }
            if ($_GET["error"] == "notsamepassword2") {
              echo "Veillez à ce que votre nouveau mot de passe sois identique";
            }
          }; ?>
        </span>
        <div class="buttons">
          <button type="submit" class="button" name="resetpswd">Rénitialiser</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>