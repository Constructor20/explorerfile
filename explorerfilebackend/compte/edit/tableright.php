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
  <style>




  /* État "ligne désactivée" → flou (on garde) */
  tr.is-disabled > td:not(.actions) {
    filter: blur(2px);
    opacity: .45;
    pointer-events: none;
    transition: filter .15s ease, opacity .15s ease;
  }

  .actions .btn.secondary,
  .actions {
      width: 90px;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      box-sizing: border-box;
      text-align: center;
  }

  .added-tag {
      width: 90px;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      box-sizing: border-box;
      text-align: center;
      background: #A8E8A8;  /* pastille verte clair */
      color: #005F00;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(4px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  </style>
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
              <button class="button" id="backToCompteAdmin" onclick="toggleCompteAdmin(baseDir)">Retour</button>
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
              <!--<form class="checkbox" method="post" action="tablerightinc.php">
                <?php //submit($user_id); ?>
              </form>-->
            </tbody>
            <thead>
              <tr>
                <th>Chemin</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script src="../../script.js"></script>


  <script>

  document.addEventListener("DOMContentLoaded", () => {
    document.body.addEventListener("click", function(e){
      const btn = e.target.closest(".add");
      if(!btn) return;

      const tr = btn.closest("tr");
      if(!tr) return;

      tr.classList.add("is-disabled"); // flou

      const cell = tr.querySelector(".actions");
      cell.innerHTML = `

        <td class="added-tag">
          <button disabled class="added-tag"><span class="check">✔</span>Ajouter</button>
        </td>
      `;
    });
  });

  // <span class="added-tag">
  //   <span class="check">✔</span> Ajouté
  // </span>
  </script>


</body>
</html>
<?php include 'tablerightinc.php'?>
