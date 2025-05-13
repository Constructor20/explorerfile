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
  <title>Utilisateur - Modifier les informations</title>
  <link rel="stylesheet" href="../style/stylecompte.css">
  <link rel="stylesheet" href="https://unpkg.com/98.css">
</head>
<body>

  <div class="sidebar window">
    <div class="title-bar">
      <div class="title-bar-text">Utilisateur</div>
    </div>
    <div class="window-body">
      <h4>Mon Compte</h4>
      <ul>
        <li><b>Home</b></li>
        <li><b>Mon Profil</b></li>
      </ul>
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
                <form action="compteinc.php" method="POST">
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
                        <button type="button" class="button" id="redirectioneditpswd" onclick="redirectionPswd()">Modifier votre mot de passe</button>
                    </div>
                    <div class="field-row-stacked">
                        <input type="checkbox" id="showUpdate" onchange="toggleUpdateButton()">
                        <label for="showUpdate">Je veux modifier mes informations</label>
                    </div>

                    <div class="field-row-stacked" id="updateButtonContainer" style="display: none;">
                        <button type="submit" class="button" name="update_account">Mettre à jour</button>
                    </div>
                </form>
                <div class="field-row-stacked">
                    <h4>Gestion des fichiers</h4>
                    <button class="button">Accéder au gestionnaire de fichiers</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    function toggleUpdateButton() {
    const checkbox = document.getElementById('showUpdate');
    const updateBtn = document.getElementById('updateButtonContainer');
    updateBtn.style.display = checkbox.checked ? 'block' : 'none';
    }
    function toggleDeconnectionButton() {
    window.location.href = '../logout.php';
    }
    function redirectionPswd (){
    document.getElementById('redirectioneditpswd').addEventListener('click', function(event) {
    event.preventDefault();
    window.location.href = 'edit/editmdp.php';
    });
    }
</script>

</body>
</html>