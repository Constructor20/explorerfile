<?php
include 'connectdb.php';
// Récupère le chemin actuel ou le dossier racine si aucun paramètre 'path' n'est fourni
$chemin = isset($_GET['path']) ? $_GET['path'] : __DIR__ . "/new";
$server_host = $_SERVER['HTTP_HOST'];

function findIcon($pChemin) {
  $pathinfo = pathinfo($pChemin);
  $url = "/explorerfile/explorerfilebackend";
  $iconDir = __DIR__ . '/icon';
  
  if(is_dir($pChemin)){
    return "<img src='$url/icon/folder2.png' class='icon' style='vertical-align: middle;' />";
  }

  $file = $iconDir.'/'.$pathinfo['extension'].'.png';
  if (is_file($file)) {
    $file = $url.'/icon/'.$pathinfo['extension'].'.png';
  } else {
    $file = $url . '/icon/file.png';
  }
  return "<img src='$file' class='icon' style='vertical-align: middle;' />";
} 
// verif accès fichier selon user id
// $_SESSION['user_id'] = $dada; // A supprimer après test

function path($conn) {
    if (!isset($_SESSION['user_id'])) {
        return [];
    }

    $userId = (int) $_SESSION['user_id'];

    $sql = "SELECT encodedjson FROM permission WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $json = $stmt->fetchColumn();

    if (!$json) {
        return [];
    }

    $decoded = json_decode($json, true);

    //triage dossier si auto = affiche dossier
    //prendre chaque élément json et comparer si il y a chemin parent

    //obliger un chemin direct absolue genre /blabla/test.txt les autres test.txt vont être automatiquement bloquer
    
    // Retourner le tableau 'paths' si présent, sinon le tableau décod­é complet


    // résultat final:
    //Donc avant de comparer les fichier 
    // je fais une comparaison de dossier mais il faudrait que je compare un tableau['dossier'] voir si il a accès logiquement dcp
    // logiquement les paths sans extension seront automatiquement des dossiers et si il y a un '/' à ce moment là des chemins avec sous dossier
    
    if (is_array($decoded) && isset($decoded['paths'])) {
        return $decoded['paths'];
    }
    
    return is_array($decoded) ? $decoded : [];
    
}
$paths = path($conn);

function pathForAdmin($conn){
    $sql = "SELECT isadmin FROM userdata WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $allPaths = [];
    while ($json = $stmt->fetchColumn()) {
        if ($json) {
            $decoded = json_decode($json, true);
            if (is_array($decoded) && isset($decoded['paths'])) {
                $allPaths = array_merge($allPaths, $decoded['paths']);
            } elseif (is_array($decoded)) {
                $allPaths = array_merge($allPaths, $decoded);
            }
        }
    }
    
    return array_unique($allPaths);
}

function completePath($chemin, $fichier) {
  return $chemin . DIRECTORY_SEPARATOR . $fichier;
}

function findFile($chemin) {
  return scandir($chemin);
}

function checkbox(){
    return "<input type='checkbox' class='checkbox' onchange='checkboxChanged()'>
    <label></label>";
}

function table($chemin, $paths) {
    $icon_folder = "/explorerfile/explorerfilebackend/icon/folder2.png";
    // Lien vers le dossier parent

    if ($chemin !== __DIR__ . "/new") {
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
      $fichiers = findFile($chemin);
      foreach ($fichiers as $fichier) {
        $i = 1;
        $filterPath = $chemin . '/' . $paths[-1+$i];
        var_dump($filterPath);
        if ($filterPath == $fichier) {echo "test";}
          $chemin_complet = completePath($chemin, $fichier);
          if(in_array($fichier, $paths) || $_SESSION['isadmin'] == 1) {
            $chemin_url = urlencode($chemin_complet);
            $icon = findIcon($chemin_complet);
            $checkbox = checkbox();
            // $fichier !== "." && $fichier !== ".."
              if (is_dir($chemin_complet)) {
                if ($fichier !== "." && $fichier !== "..") {
                    echo "<tr>
                            <td>
                                $checkbox
                                $icon
                            <a href='?path=$chemin_url'>/$fichier</a>
                            </td>
                        </tr>";
                }
              } else {
              echo "<tr>
                      <td>
                      $checkbox
                      $icon
                      $fichier
                      </td>
                  </tr>";
              }
          }
      }
    } else {
        echo "<tr><td>⚠️ Ce dossier n'existe pas.</td></tr>";
    }
        
}