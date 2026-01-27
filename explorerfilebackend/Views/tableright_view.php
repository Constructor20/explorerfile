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
      <div class="field-row" style="justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
        <div class="user-info-section">
          <div class="user-info-item">
            <span class="user-info-label">Utilisateur:</span>
            <span class="user-info-value"><?= htmlspecialchars($userName ?? '') ?></span>
          </div>
          <div class="user-info-item">
            <span class="user-info-label">Email:</span>
            <span class="user-info-value"><?= htmlspecialchars($userEmail ?? '') ?></span>
          </div>
          <div class="user-role-section">
            <div class="current-role-badge <?= ($userIsAdmin ?? false) ? 'role-admin' : 'role-user' ?>">
              <?= ($userIsAdmin ?? false) ? '👤 Administrateur' : '🧑 Utilisateur Standard' ?>
            </div>
            <form method="POST" action="update_admin.php" class="admin-toggle-form">
              <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>" />
              <label class="admin-toggle-switch">
                <input type="checkbox" name="is_admin" value="1" <?= ($userIsAdmin ?? false) ? 'checked' : '' ?> onchange="this.form.submit()" />
                <span class="toggle-slider"></span>
                <span class="toggle-label"><?= ($userIsAdmin ?? false) ? 'Désactiver admin' : 'Activer admin' ?></span>
              </label>
            </form>
          </div>
          <input type="hidden" id="user_id" name="user_id" value="<?= htmlspecialchars($userId) ?>" />
        </div>
        <div>
          <button type="button" class="button" onclick="window.location.href='../../admin/'">Retour</button>
          <button type="button" class="button" onclick="toggleDeconnectionButton(baseDir)">Déconnexion</button>
        </div>
      </div>

      <div class="window password-section-compact" style="margin-bottom: 15px;">
        <div class="title-bar">
          <div class="title-bar-text">Changer le mot de passe</div>
        </div>
        <div class="window-body">
          <form method="POST" action="update_password.php">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>" />
            <div class="field-row-stacked">
              <label>Nouveau mot de passe:</label>
              <input type="password" name="new_password" required />
            </div>
            <div class="field-row-stacked">
              <label>Confirmer le mot de passe:</label>
              <input type="password" name="confirm_password" required />
            </div>
            <div class="field-row-stacked">
              <button type="submit" class="button">Changer le mot de passe</button>
            </div>
            <?php if (!empty($_GET['password_error'])): ?>
              <p style="color: red;"><?= htmlspecialchars($_GET['password_error']) ?></p>
            <?php endif; ?>
            <?php if (!empty($_GET['password_success'])): ?>
              <p style="color: green;"><?= htmlspecialchars($_GET['password_success']) ?></p>
            <?php endif; ?>
          </form>
        </div>
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

      <form id="permissionsForm" method="post" action="update.php">
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
