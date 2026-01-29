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

  table.explorer-table {
    width: 100%;
    border: 1px solid #000;
    border-collapse: collapse;
    table-layout: fixed;              /* stabilité des largeurs */
  }

  .explorer-table th,
  .explorer-table td {
    padding: 2px 4px;                 /* compact */
    vertical-align: middle;
  }

  .select-col,
  .explorer-table thead th.select-col {
    width: 360px;
    text-align: center;
  }

  .explorer-table thead th.select-col,
  .explorer-table tbody td.select-col {
    border-right: 1px solid #b5b5b5;
  }

  /* ===========================
     COLONNE CHEMIN (GRILLE)
     =========================== */

  /* La cellule qui contient le nom/icône + bouton/pastille */
  .path-cell {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 110px;  /* texte fluide + action fixe */
    align-items: center;
    column-gap: 8px;                              /* écart constant */
    min-width: 0;
  }

  /* ===========================
     ENTRÉE (ICÔNE + NOM)
     =========================== */

  .entry {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    min-width: 0;

    /* Padding & radius constants → pas de “shift” visuel */
    padding: 1px 4px;
    border-radius: 2px;

    /* Héritage de couleurs par défaut */
    color: inherit;
  }

  /* Texte avec ellipsis */
  .entry .entry-name,
  .entry a.entry-name {
    display: block;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    min-width: 0;
    max-width: 100%;
  }

  /* Rendre les liens visiblement focus (accessibilité) */
  .entry a:focus-visible {
    outline: 2px solid #1a73e8;
    outline-offset: 2px;
    border-radius: 2px;
  }

  /* ===========================
     ÉTATS DE LIGNE
     =========================== */

  /* État "désactivé" visuel (après clic sur Ajouter) */
  tr.is-disabled .entry {
    filter: blur(2px);
    opacity: .45;
    /* on NE bloque PAS les clics sur les liens dans .entry */
  }

  /* Lien toujours cliquable malgré l’état "désactivé" */
  tr.is-disabled .entry a,
  tr.is-disabled .entry .entry-name a {
    pointer-events: auto;
  }

  /* Le bouton/pastille restent nets mais non cliquables */
  tr.is-disabled button,
  tr.is-disabled .added-tag {
    filter: none !important;
    opacity: 1 !important;
    pointer-events: none;
  }

  /* Dossier ajouté : fond vert, pas de flou, pas de décalage */
  tr.is-folder.is-added .entry {
    background: #CFF2CF;
    color: #005F00;
    filter: none !important;
    opacity: 1 !important;
  }

  /* ===========================
     BOUTON "AJOUTER"
     =========================== */

  .btn.secondary.add {
    width: 110px;         /* largeur FIXE */
    min-width: 110px;
    max-width: 110px;
    height: 28px;         /* hauteur harmonisée avec la pastille */
    line-height: 26px;
    box-sizing: border-box;

    justify-self: end;    /* aligne à droite dans la grid */
    text-align: center;
    cursor: pointer;
  }

  /* Bouton désactivé : non cliquable / légèrement atténué */
  .btn.secondary.add[disabled] {
    opacity: .85;
    cursor: default;
    pointer-events: none;
  }

  /* ===========================
     PASTILLE VERTE "AJOUTÉ"
     =========================== */

  .added-tag {
    width: 110px;         /* même largeur que le bouton → alignement parfait */
    min-width: 110px;
    max-width: 110px;
    height: 28px;
    box-sizing: border-box;

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

    animation: fadeUp .15s ease-out;
  }

  /* ===========================
     ANIMATIONS & RÉDUCTIONS
     =========================== */

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(4px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Respect des préférences utilisateur (réduit les animations) */
  @media (prefers-reduced-motion: reduce) {
    .added-tag {
      animation: none;
    }
    tr.is-disabled .entry {
      transition: none;
    }
  }

  /* ===========================
     SCROLL AREA
     =========================== */

  .explorer-table-scroll {
    max-height: 400px;
    overflow-y: auto;
  }

  /* ===========================
     DIVERS (ROBUSTESSE)
     =========================== */

  /* Garantir que le texte prend le max d’espace disponible */
  .path-col {
    padding-right: 0;
    width: auto;
  }

  /* Évite tout recouvrement accidentel si tu ajoutes des effets de position */
  .path-cell { position: relative; }
  .entry     { position: relative; z-index: 1; }
  .added-tag { position: relative; z-index: 0; }
  ``


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
    document.body.addEventListener("click", handleAddClick);
  });

  function getAddButton(e) {
    return e.target.closest(".add");
  }
  function getRow(btn) {
    return btn.closest("tr");
  }
  function disableRow(tr) {
    tr.classList.add("is-disabled");
  }
  function markButtonAsAdded(btn) {
    btn.disabled = true;
    btn.classList.add("added-tag");
    btn.innerHTML = '<span class="check">✔</span> Ajouté';
  }
  function markFolderAsAdded(tr) {
    if (tr.classList.contains("is-folder")) {
      tr.classList.add("is-added");
    }
  }
  function getDataAbs(tr) {
    return tr.dataset.abs || null;
  }

  function handleAddClick(e) {
    const btn = getAddButton(e);
    if (!btn) return;

    const tr = getRow(btn);
    if (!tr) return;

    disableRow(tr);
    markButtonAsAdded(btn);
    markFolderAsAdded(tr);

    putDataInput(e);
  }

  function putDataInput(e){
    const absPath = getDataAbs(e.target.closest("tr"));
    if (absPath) {

      const input = document.getElementById("selectedForm");
      console.log(input);
    }
  }

</script>

</body>
</html>
<?php include 'tablerightinc.php'?>
