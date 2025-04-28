<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Login - Windows 98 Style</title>
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
      width: 300px;
    }
    .login-form {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .login-form input {
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
      <div class="title-bar-text">Connexion</div>
      <div class="title-bar-controls">
        <button aria-label="Minimize"></button>
        <button aria-label="Maximize"></button>
        <button aria-label="Close"></button>
      </div>
    </div>
    <div class="window-body">
      <form class="login-form" method="post" action="login.php">
        <label>
          Nom d'utilisateur:
          <input type="text" name="username" required />
        </label>
        <label>
          Mot de passe:
          <input type="password" name="password" required />
        </label>
        <div class="buttons">
          <button type="submit" class="button">Se connecter</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
