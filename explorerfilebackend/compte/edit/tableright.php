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




  /* ======== TABLEAU GLOBAL ======== */

  table.explorer-table {
      width: 100%;
      border: 1px solid black;      /* bordure extérieur */
      border-collapse: collapse;     /* important pour séparation propre */
      table-layout: fixed;           /* colonnes stables */
  }

  .explorer-table th,
  .explorer-table td {
      padding: 6px 8px;
      vertical-align: middle;
  }

  /* ======== COLONNE Sélection ======== */

  .select-col,
  .explorer-table thead th.select-col {
      width: 52px;
      text-align: center;
  }

  /* Ligne de séparation entre Sélection et Chemin */
  .explorer-table thead th.select-col,
  .explorer-table tbody td.select-col {
      border-right: 1px solid #b5b5b5; /* gris style Win98 */
  }

  /* ======== COLONNE Chemin ======== */

  .path-col {
      width: auto;
      padding-right: 0; /* bouton collé à la bordure droite */
  }

  /* Conteneur interne de la cellule Chemin */
  .path-cell {
      display: flex;
      align-items: center;
      justify-content: space-between; /* nom ↔ bouton */
      gap: 12px; /* espace CONSTANT entre texte et bouton */
      min-width: 0;
  }

  /* Texte + icône */
  .entry {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      min-width: 0;
  }

  .entry .entry-name,
  .entry a.entry-name {
      display: block;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
  }


  /* ======== ÉTAT LIGNE DÉSACTIVÉE (flou) ======== */

  tr.is-disabled .entry {
      filter: blur(2px);
      opacity: .45;
      pointer-events: none;
      transition: filter .15s ease, opacity .15s ease;
  }

  /* NE PAS flouter le bouton */
  tr.is-disabled button,
  tr.is-disabled .added-tag {
      filter: none !important;
      opacity: 1 !important;
      pointer-events: none;  /* empêche clic même si disabled sauté */
  }


  /* ======== BOUTON AJOUTER ======== */

  .btn.secondary.add {
      margin-left: auto; /* pousse le bouton à droite */
      min-width: 100px;
      text-align: center;
  }


  /* ======== BOUTON ÉTAT AJOUTÉ ======== */

  .added-tag {
      min-width: 100px;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      box-sizing: border-box;
      background: #A8E8A8;   /* vert clair */
      color: #005F00;
      border: 1px solid #5bb85b;
      border-radius: 2px;
      gap: 6px;
      padding: 2px 6px;
      font-weight: bold;
      animation: fadeUp .15s ease-out;
  }

  /* Animation apparition */
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
                <th>Sélectionné</th>
              </tr>
            </thead>
            <tbody id="fileTableBody">
                <?php table($chemin, $paths); ?>
              <!--<form class="checkbox" method="post" action="tablerightinc.php">
                <?php //submit($user_id); ?>
              </form>-->
            </tbody>
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
      const cell = btn.parentElement; // .path-cell

      // Floute le nom
      tr.classList.add("is-disabled");

      // Rend le bouton inclicable
      btn.disabled = true;

      // Transforme le bouton en pastille verte
      btn.classList.add("added-tag");

      // Change le texte du bouton
      btn.innerHTML = '<span class="check">✔</span> Ajouté';
    });
  });
  ``


  </script>


</body>
</html>
<?php include 'tablerightinc.php'?>
