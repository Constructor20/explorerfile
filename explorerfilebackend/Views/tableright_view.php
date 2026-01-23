<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Explorateur Win98</title>
  <link rel="stylesheet" href="https://unpkg.com/98.css">
  <link rel="stylesheet" href="../../style.css">
  <link rel="stylesheet" href="../../Assets/css/tableright.css">
</head>
<body>
  <div class="window" style="width: 98%; margin: 15px auto;">
    <div class="title-bar">
      <div class="title-bar-text">Gestion des Permissions</div>
      <div class="title-bar-controls">
        <button aria-label="Minimize"></button>
        <button aria-label="Maximize"></button>
        <button aria-label="Close"></button>
      </div>
    </div>

    <div class="window-body">
      <div class="field-row" style="justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <div>
          <label for="user_id">ID Utilisateur:</label>
          <input type="number" id="user_id" name="user_id" value="<?= htmlspecialchars(
              $userId,
          ) ?>" />
        </div>
        <button type="button" class="button" onclick="toggleDeconnectionButton(baseDir)">Déconnexion</button>
      </div>

      <div class="drag-container">
        <div class="window">
          <div class="title-bar">
            <div class="title-bar-text">Fichiers Disponibles</div>
          </div>
          <div class="window-body">
            <div class="file-list" id="availableFiles">
              <ul class="file-tree" id="fileTree"></ul>
            </div>
          </div>
        </div>

        <div class="window">
          <div class="title-bar">
            <div class="title-bar-text">Permissions Sélectionnées</div>
          </div>
          <div class="window-body">
            <div class="drop-zone" id="dropZone">
              <p>Glissez les fichiers ou dossiers ici pour donner l'accès</p>
            </div>
          </div>
        </div>
      </div>

      <form id="permissionsForm" method="post" action="tablerightinc.php">
        <input type="hidden" id="user_id_input" name="user_id" value="<?= htmlspecialchars(
            $userId,
        ) ?>" />
        <input type="hidden" id="permissions_input" name="permissions" />
        <button type="submit" class="button">Enregistrer les modifications</button>
      </form>
    </div>
  </div>

  <script src="../../Assets/js/permission-manager.js"></script>
  <script>
    const treeData = <?= json_encode($treeData) ?>;
    const existingPermissions = <?= json_encode($existingPermissions) ?>;


    const baseDir = "/explorerfile/explorerfilebackend/";

    function toggleDeconnectionButton(baseDir) {
      window.location.href = baseDir + 'logout.php';
    }

    PermissionManager.init(treeData, existingPermissions);
  </script>
</body>
</html>
