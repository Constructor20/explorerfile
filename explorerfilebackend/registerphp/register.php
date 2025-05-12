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
      <div class="title-bar-text">Créer un compte</div>
      <div class="title-bar-controls">
        <button aria-label="Minimize"></button>
        <button aria-label="Maximize"></button>
        <button aria-label="Close"></button>
      </div>
    </div>
    <div class="window-body">
      <form class="register-form" method="post" action="registerinc.php">
        <label>
          Email:
          <input type="email" name="email"  required placeholder="Email"/>
          <?php if(!empty($_GET["error"])) {
            if($_GET["error"] == "email" or $_GET["error"] == "invalid_email") {
              echo "L'email est déjà utilisé ou le champ est vide";
            }
          };?>
        </label>
        <label>
          Nom d'utilisateur:
          <input type="username" name="username" placeholder="Nom d'utilisateur"/>
          <?php if(!empty($_GET["error"])) {
            if($_GET["error"] == "username") {
              echo "L'username est déjà utilisé ou le champ est vide";
            }
          };?>
        </label>
        <label>
          Mot de passe:
          <input type="password" name="password" required placeholder="Mot de passe"/>
          <?php if(!empty($_GET["error"])) {
            if($_GET["error"] == "password") {
              echo "Le champ du mot de passe n'est pas valide";
            }
          };?>
        </label>
        <label>
          Confirmer mot de passe:
          <input type="password" name="confirm_password" required placeholder="Confirmer mot de passe"/>
          <?php if(!empty($_GET["error"])) {
            if($_GET["error"] == "confirm_password" or $_GET["error"] == "nonesamepassword") {
              echo "Le champ de confirmation de mot de passe n'est pas valide";
            }
          };?>
        </label>
        <div id="inscription"><a href="login.php">Déjà un compte ? Connectez-vous !</a></div>
        <div class="buttons">
          <button type="submit" class="button">Créer le compte</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
