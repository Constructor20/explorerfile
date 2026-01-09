<?php 

include 'tablerightinc.php';
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
              <button type="button" class="button" id="gotoProfile" onclick="goto('/explorerfile/explorerfilebackend/compte/compte.php')">Profil</button>
              <?php if($_SESSION['isadmin'] == 1) {
              $ref = '/explorerfile/explorerfilebackend/compte/compteadmin.php';
              echo "<button type='button' class='button' id='gotoEditProfile' onclick='goto(" . json_encode('/explorerfile/explorerfilebackend/compte/compteadmin.php') . ")'>Gestion des Comptes</button>";
              }?>
              <button class="button" id="updateDeconnection" onclick="toggleDeconnectionButton()">Déconnexion</button>
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
              <?php table($chemin, $paths); ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script src="../../script.js"></script>
</body>
</html>
