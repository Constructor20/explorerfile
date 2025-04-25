<?php include 'table.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Explorateur de Fichiers</title>
  <link rel="stylesheet" href="https://unpkg.com/98.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="window draggable">

    <div class="window" style="width: 90%; height: 90%; margin: 20px auto;">
      <div class="title-bar">
        <div class="title-bar-text">Explorateur de Fichiers (Back-end)</div>
        <div class="title-bar-controls">
          <button aria-label="Minimize"></button>
          <button aria-label="Maximize"></button>
          <button aria-label="Close"></button>
        </div>
      </div>
      
    <div class="window-body" style="height: calc(100% - 40px); display: flex; flex-direction: column;">
      <!-- Barre supérieure -->
      <div class="field-row" style="justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <!-- Chemin actuel -->
        <span id="parentpath" style="font-size: 1.2em;">
          Chemin actuel : <?php
          if($chemin == __DIR__) {
            echo '/';
          }
            echo str_replace(__DIR__,  '',$chemin);
          ?>
        </span>
      </div>
      <!-- Tableau -->
      <div style="flex-grow: 1; overflow-y: auto;">
        <table class="table" style="width: 100%;">
          <thead>
            <tr>
              <th>Chemin</th>
            </tr>
          </thead>
          <tbody id="fileTableBody">
            <?php table($chemin) ?>
              <div class="window" style="width: 300px; margin: 20px auto;">
                <div class="title-bar">
                  <div class="title-bar-text">Chargement du cerveau...</div>
                  <div class="title-bar-controls">
                    <button aria-label="Close"></button>
                  </div>
                </div>
                <div class="window-body" style="text-align: center;">
                  <img src="https://media1.tenor.com/m/BLOZw5VmYA8AAAAd/brain.gif" alt="brain overload" style="width: 100%; height: auto;">
                </div>
              </div>
          </tbody>
        </table>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>