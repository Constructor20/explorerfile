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

  /* ======================== */
  /*    TABLEAU GLOBAL        */
  /* ======================== */

    table.explorer-table {
        width: 100%;
        border: 1px solid black;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .explorer-table th,
    .explorer-table td {
        padding: 2px 4px;   /* compact */
        vertical-align: middle;
    }

    /* ======================== */
    /*   COLONNE SELECTION      */
    /* ======================== */

    .select-col,
    .explorer-table thead th.select-col {
        width: 360px;
        text-align: center;
    }

    /* Séparation visuelle entre colonnes */
    .explorer-table thead th.select-col,
    .explorer-table tbody td.select-col {
        border-right: 1px solid #b5b5b5;
    }

    /* ======================== */
    /*     COLONNE CHEMIN       */
    /* ======================== */

    .path-col {
        padding-right: 0;      /* bouton collé au bord droite */
        width: auto;
    }

    /* Grid → texte 1fr / bouton 110px */
    .path-cell {
        display: grid;
        grid-template-columns: 1fr 110px; /* bouton largeur FIXE */
        align-items: center;
        column-gap: 8px;                  /* écart fixe nom ↔ bouton */
        min-width: 0;
    }

    /* Texte & icône */
    .entry {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-width: 0;
    }
    .entry .entry-name,
    .entry a.entry-name {
        display: block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    /* ======================== */
    /*     ETAT "AJOUTÉ"        */
    /* ======================== */

    /* Floute uniquement le nom, PAS le bouton */
    tr.is-disabled .entry {
        filter: blur(2px);
        opacity: .45;
        pointer-events: none;
        transition: filter .15s ease, opacity .15s ease;
    }

    /* Bouton/pastille restent nets */
    tr.is-disabled button,
    tr.is-disabled .added-tag {
        filter: none !important;
        opacity: 1 !important;
        pointer-events: none; /* anti-clic de sûreté */
    }

    /* ======================== */
    /*     BOUTON AJOUTER       */
    /* ======================== */

    .btn.secondary.add {
        width: 110px;         /* largeur FIXE */
        min-width: 110px;
        max-width: 110px;
        justify-self: end;    /* aligne à droite dans la grid */
        text-align: center;
    }

    /* ======================== */
    /*  PASTILLE VERTE AJOUTÉ   */
    /* ======================== */

    .added-tag {
        width: 110px;         /* même largeur que le bouton → alignement parfait */
        min-width: 110px;
        max-width: 110px;

        display: inline-flex;
        justify-content: center;
        align-items: center;

        background: #A8E8A8;
        color: #005F00;
        border: 1px solid #5bb85b;
        border-radius: 2px;
        gap: 6px;
        padding: 2px 6px;
        font-weight: bold;

        box-sizing: border-box;

        animation: fadeUp .15s ease-out;
    }

    /* Animation */
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
                <button type="submit" form="selectionForm"class="button">Enregistrer</button>
                <th>Chemin</th>
                <th class="select-col">Sélection</th>
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
        <form id="selectionForm" method="post" action="tablerightinc.php">
          <input type="hidden" id="selectedFiles" name="selectedFiles" value="[]">
        </form>
      </div>
    </div>
  </div>
  <script src="../../script.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", () => {
    document.body.addEventListener("click", (e) => {
      const btn = e.target.closest(".add");   // bouton "Ajouter"
      if (!btn) return;

      const tr = btn.closest("tr");
      if (!tr) return;

      // 1) Floute uniquement le nom + icône (pas le bouton)
      tr.classList.add("is-disabled");

      // 2) Rend le bouton inclicable
      btn.disabled = true;

      // 3) Transforme le bouton en pastille verte "Ajouté"
      btn.classList.add("added-tag");
      btn.innerHTML = '<span class="check">✔</span> Ajouté';
    });
  });
  </script>

</body>
</html>
<?php include 'tablerightinc.php'?>
