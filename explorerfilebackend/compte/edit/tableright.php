<?php 
session_start();
include '../../connectdb.php';

include '../../affichage.php';
$user_id = $_POST['user_id'] ?? null;
var_dump($user_id);

// var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Explorateur Win98</title>
  <link rel="stylesheet" href="https://unpkg.com/98.css">
  <link rel="stylesheet" href="../../style.css">
  <div class="window" style="width: 90%; margin: 20px auto;">
    <div class="title-bar">
      <div class="title-bar-text">Explorateur de Fichiers</div>
      <div class="title-bar-controls">
        <button aria-label="Minimize"></button>
        <button aria-label="Maximize"></button>
        <button aria-label="Close"></button>
      </div>
    </div>

    <div class="window-body">
      <!-- Wrapper pour le tableau -->
               Chemin actuel :
          <span id="parentpath">
            <?php echo htmlspecialchars(str_replace(__DIR__, '', $chemin)); ?>
          </span>
        </div>

        <div class="window" style="width: 300px; margin: 20px auto;">
          <div class="title-bar">
              <div class="title-bar-text">Bouton d'accès</div>
              <div class="title-bar-controls">
              <button aria-label="Close"></button>
              </div>
          </div>
              <button type="button" class="button" id="gotoProfile" onclick="gotoaccounts(baseDir)">Profil</button>
              <?php if($_SESSION['isadmin'] == 1) {?>
              <button type='button' class='button' id='gotoEditProfile' onclick="gotomanagementaccounts(baseDir)">Gestion des Comptes</button>
              <?}?>
              <button class="button" id="updateDeconnection" onclick="toggleDeconnectionButton(baseDir)">Déconnexion</button>
        </div>
        <!-- Tableau scrollable -->
        <div class="explorer-table-scroll" style="max-height: 400px; overflow-y: auto;">
          <table class="table explorer-table" style="width: 100%;">
            <thead>
              <tr>
                <th>Chemin</th>
              </tr>
            </thead>
            <tbody id="fileTableBody">
              <form class="checkbox" method="post" action="tablerightinc.php">
                <?php table($chemin, $paths); ?>
                <button type="submit" class="button">Enregistrer les modifications</button>
              </form>
              <!-- futur fonction sélection -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script src="../../script.js"></script>
</body>
</html>
