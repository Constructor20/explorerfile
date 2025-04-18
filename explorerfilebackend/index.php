<?php
$chemin = __DIR__;
$icon = ("/icon/folder.png");

if (is_dir($chemin)) {
    $fichiers = scandir($chemin);

    foreach ($fichiers as $fichier) {
        if ($fichier !== "." && $fichier !== "..") {
            echo "<img src='$icon'>"." /".$fichier . "<br>";
        }
    }
} else {
    echo "⚠️ Le dossier '$chemin' n'existe pas.";
}

?>