<?php
// Récupère le chemin actuel ou le dossier racine si aucun paramètre 'path' n'est fourni
$chemin = isset($_GET['path']) ? $_GET['path'] : __DIR__;

function findicon($pChemin) {
  $pathinfo = pathinfo($pChemin);
  if(is_dir($pChemin)){
    return "<img src='icon/folder2.png' class='icon' style='vertical-align: middle;' />";
  }

  $file = './icon/'.$pathinfo['extension'].'.png';
  if (!file_exists($file)) {
    $file = './icon/file.png';
  }
  return "<img src='$file' class='icon' style='vertical-align: middle;' />";
} 

function table($chemin) {
    $icon_folder = "icon/folder2.png";
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
        $icon = findicon($chemin_complet,);
    //       // $fichier !== "." && $fichier !== ".."
            if (is_dir($chemin_complet)) {
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
                        $fichier
                        </td>
                    </tr>";
            }
        }
    } else {
        echo "<tr><td>⚠️ Ce dossier n'existe pas.</td></tr>";
    }
}