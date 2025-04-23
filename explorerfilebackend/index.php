<?php
// Récupère le chemin actuel ou le dossier racine si aucun paramètre 'path' n'est fourni
$chemin = isset($_GET['path']) ? $_GET['path'] : __DIR__;
function findicon($chemin) {
  if (!is_dir($chemin)) {
  $pathinfo = pathinfo($chemin);
  var_dump($chemin);
  // var_dump($pathinfo);
  switch ($pathinfo['extension']) {
    case 'html':
      $extension = 'html';
      break;
    case 'css':
      $extension = 'css';
      break;
    case 'php':
      $extension = 'php';
      break;
    case 'txt':
      $extension = 'txt';
      break;
    default:
      $extension = 'file';
    }
  return "<img src='./icon/$extension.png' class='icon' style='vertical-align: middle;' />";
  } else {
    return "<img src='./icon/folder2.png' class='icon' style='vertical-align: middle;' />";
  }
}
$icon_folder = "icon/folder.png";
?>

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
          Chemin actuel : <?php echo htmlspecialchars($chemin); ?>
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
              <?php
              // Lien vers le dossier parent
              if ($chemin !== __DIR__) {
                  $parent = dirname($chemin);  // Chemin du dossier parent
                  $url_parent = urlencode($parent);  // URL encodée pour le lien
                  echo "<tr>
                          <td>
                          <img src='$icon_folder' class='icon' style='vertical-align: middle;' />
                          <a href='?path=$url_parent'>🔙 Revenir au dossier parent</a>
                          </td>
                      </tr>";
              }
              if (is_dir($chemin)) {
                  $fichiers = scandir($chemin);

                  foreach ($fichiers as $fichier) {
                    $chemin_complet = $chemin . DIRECTORY_SEPARATOR . $fichier;
                    $chemin_url = urlencode($chemin_complet);
                    $icon = findicon($chemin_complet);
                    // $fichier !== "." && $fichier !== ".."
                      if (is_dir($fichier)) {
                        if ($fichier !== "." && $fichier !== "..") {
                          echo "<tr>
                                  <td>
                                      $icon
                                  <a href='?path=$chemin_url'>/$fichier</a>
                                  </td>
                                </tr>";
                        }
                      } else {
                        $icon_file_path = 'icon';
                        $icon_file = scandir(directory: $icon_file_path);
                        // $path_parts = pathinfo($fichier);
                        // return le visuel de mon icon
                        
                        // echo $path_parts['extension'], "\n";
                          echo "<tr>
                                  <td>
                                  $icon
                                  //$fichier
                                  </td>
                              </tr>";
                      }
                  }
                } else {
                  echo "<tr><td>⚠️ Ce dossier n'existe pas.</td></tr>";
              }
              ?>
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
  </div>
</body>
</html>