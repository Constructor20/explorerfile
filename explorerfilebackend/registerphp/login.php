<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un compte</title>
  <link rel="stylesheet" href="https://unpkg.com/98.css" />
  <link rel="stylesheet" href="../style/styleregister.css" />
</head>
<body>
  <div class="window" style="margin: auto;">
    <div class="title-bar">
      <div class="title-bar-text">Connexion</div>
      <div class="title-bar-controls">
        <button aria-label="Minimize"></button>
        <button aria-label="Maximize"></button>
        <button aria-label="Close"></button>
      </div>
    </div>
    <div class="window-body">
      <form class="register-form" method="post" action="logininc.php">
        <label>
          identifiant:
          <input type="text" name="identifiant"  placeholder="Email ou Username"/>
          <?php if(!empty($_GET["error"])) {
            if($_GET["error"] == "identifiant" or $_GET["error"] == "emptyid") {
              echo "L'identifiant n'est pas bon";
            }
          };?>
        </label>
        <label>
          Mot de passe:
          <input type="password" name="password"  placeholder="Mot de passe"/>
          <?php if(!empty($_GET["error"])) {
            if($_GET["error"] == "password" or $_GET["error"] == "emptypassword") {
              echo "Le mot de passe n'est pas bon";
            }
          };?>
        </label>
        <div class="buttons">
          <button type="submit" class="button">Connexion</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
