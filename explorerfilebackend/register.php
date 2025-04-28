<?php include 'registerinc.php' ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un compte - Windows 98 Style</title>
  <link rel="stylesheet" href="https://unpkg.com/98.css" />
  <style>
    body {
      background-color: teal;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .window {
      width: 320px;
    }
    .register-form {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .register-form input {
      margin-top: 4px;
    }
    .buttons {
      display: flex;
      justify-content: flex-end;
      margin-top: 8px;
    }
  </style>
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
      <form class="register-form" method="post" action="register.php">
        <label>
          Email:
          <input type="text" name="email" required />
        </label>
        <label>
          Nom d'utilisateur:
          <input type="text" name="username" required />
        </label>
        <label>
          Mot de passe:
          <input type="password" name="password" required />
        </label>
        <label>
          Confirmer mot de passe:
          <input type="password" name="confirm_password" required />
        </label>
        <div class="buttons">
          <button type="submit" class="button">Créer le compte</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
