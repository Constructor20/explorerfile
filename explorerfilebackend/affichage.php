<?php
include "connectdb.php";
// Récupère le chemin actuel ou le dossier racine si aucun paramètre 'path' n'est fourni
$chemin = isset($_GET["path"]) ? $_GET["path"] : __DIR__ . "/new";
$server_host = $_SERVER["HTTP_HOST"];

function findIcon($pChemin)
{
    $pathinfo = pathinfo($pChemin);
    $url = "/explorerfile/explorerfilebackend";
    $iconDir = __DIR__ . "/icon";

    if (is_dir($pChemin)) {
        return "<img src='$url/icon/folder2.png' class='icon' style='vertical-align: middle;' />";
    }

    $file = $iconDir . "/" . $pathinfo["extension"] . ".png";
    if (is_file($file)) {
        $file = $url . "/icon/" . $pathinfo["extension"] . ".png";
    } else {
        $file = $url . "/icon/file.png";
    }
    return "<img src='$file' class='icon' style='vertical-align: middle;' />";
}
function path($conn)
{
    if (!isset($_SESSION["user_id"])) {
        return [];
    }

    $userId = (int) $_SESSION["user_id"];

    $sql = "SELECT encodedjson FROM permission WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", $userId, PDO::PARAM_INT);
    $stmt->execute();

    $json = $stmt->fetchColumn();

    if (!$json) {
        return [];
    }

    $decoded = json_decode($json, true);
    if (!isset($decoded) || !is_array($decoded)) {
        return [];
    }

    $filterPath = [];
    $racine = __DIR__ . "/new";
    foreach ($decoded["paths"] as $path) {
        $path = ltrim($path, "/"); // Supprimer le '/' initial s'il existe
        $filterPath[] = $racine . "/" . $path;
    }
    return $filterPath;
}
$paths = path($conn);

function pathForAdmin($conn)
{
    $sql = "SELECT isadmin FROM userdata WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $allPaths = [];
    while ($json = $stmt->fetchColumn()) {
        if ($json) {
            $decoded = json_decode($json, true);
            if (is_array($decoded) && isset($decoded["paths"])) {
                $allPaths = array_merge($allPaths, $decoded["paths"]);
            } elseif (is_array($decoded)) {
                $allPaths = array_merge($allPaths, $decoded);
            }
        }
    }

    return array_unique($allPaths);
}
function completePath($chemin, $fichier)
{
    return $chemin_complet = $chemin . DIRECTORY_SEPARATOR . $fichier;
}
function findFile($chemin)
{
    return scandir($chemin);
}
function checkbox()
{
    return "<input type='checkbox' class='checkbox' onchange='checkboxChanged()'>
    <label></label>";
}
// Affiche la ligne HTML pour remonter dans le dossier parent
function renderParentFolderRow($chemin)
{
    $icon_folder = "/explorerfile/explorerfilebackend/icon/folder2.png";

    // Si on est PAS à la racine, on affiche le lien "retour"
    if ($chemin !== __DIR__ . "/new") {
        $parent = dirname($chemin);
        $url_parent = urlencode($parent);

        return "<tr>
                <td>
                <img src='$icon_folder' class='icon' style='vertical-align: middle;' />
                <a href='?path=$url_parent'>🔙 Revenir au dossier parent</a>
                </td>
            </tr>";
    }

    return ""; // Si on est à la racine → rien
}
// Vérifie si l'utilisateur a le droit de VOIR ce fichier/dossier
function hasAccessTo($chemin_complet, $paths)
{
    return in_array($chemin_complet, $paths) || $_SESSION["isadmin"] == 1;
}
// Créé une ligne HTML pour un DOSSIER

function renderFolderRow($chemin_complet, $fichier)
{
    $icon = findIcon($chemin_complet);
    $chemin_url = urlencode($chemin_complet);
    $currentPage = basename($_SERVER["PHP_SELF"]);
    $showCheckbox = $currentPage !== "index.php";

    echo '
    <tr class="folder-row" data-folder="' .
        $chemin_complet .
        '">
        <td class="cell">';
    if ($showCheckbox) {
        echo '
            <input type="checkbox" class="folder-checkbox perm-checkbox" data-folder="' .
            $chemin_complet .
            '" id="checkbox-' .
            $chemin_complet .
            '" name="checkbox-' .
            $chemin_complet .
            '">
            <label for="checkbox-' .
            $chemin_complet .
            '">';
    }
    echo '
              <span class="entry">
                ' .
        $icon .
        '<a href="?path=' .
        $chemin_url .
        '">/' .
        $fichier .
        '</a>
              </span>';
    if ($showCheckbox) {
        echo '
            </label>';
    }
    echo '
        </td>
    </tr>';
}

// Créé une ligne HTML pour un FICHIER (en HTML)

function renderFileRow($chemin_complet, $fichier)
{
    $icon = findIcon($chemin_complet);
    $currentPage = basename($_SERVER["PHP_SELF"]);
    $showCheckbox = $currentPage !== "index.php";

    echo '
    <tr class="folder-row" data-folder="' .
        $chemin_complet .
        '">
        <td class="cell">';
    if ($showCheckbox) {
        echo '
            <input type="checkbox" class="folder-checkbox perm-checkbox"
                   data-folder="' .
            $chemin_complet .
            '"
                   id="checkbox-' .
            $chemin_complet .
            '">

            <label for="checkbox-' .
            $chemin_complet .
            '">';
    }
    echo '
              <span class="entry">
                ' .
        $icon .
        '
                <span class="entry-name">' .
        $fichier .
        '</span>
              </span>';
    if ($showCheckbox) {
        echo "</label>";
    }
    echo '
        </td>
    </tr>';
}

// Gère UN fichier/dossier et retourne la ligne HTML adaptée
function renderOneElement($chemin, $fichier, $paths)
{
    $chemin_complet = completePath($chemin, $fichier);

    // Si l'utilisateur ne peut pas y accéder → on ignore
    if (!hasAccessTo($chemin_complet, $paths)) {
        return "";
    }

    // Si c'est un dossier
    if (is_dir($chemin_complet)) {
        // On ignore les dossiers spéciaux
        if ($fichier === "." || $fichier === "..") {
            return "";
        }

        return renderFolderRow($chemin_complet, $fichier);
    }

    // Sinon → fichier normal
    return renderFileRow($chemin_complet, $fichier);
}

// Fonction TABLE reconstruite proprement

function submit($user_id)
{
    echo "
    <input type='hidden' name='user-id-saving' value='$user_id'>
    <button type='button' name='path-user-update' class='button' type='submit'>Enregistrer les modifications</button>";
}

function table($chemin, $paths)
{
    // 1. Affiche le bouton retour si nécessaire
    echo renderParentFolderRow($chemin);

    // 2. Si ce n’est pas un dossier → message d’erreur
    if (!is_dir($chemin)) {
        echo "<tr><td>⚠️ Ce dossier n'existe pas.</td></tr>";
        return;
    }

    // 3. Récupère tous les fichiers du dossier
    $fichiers = findFile($chemin);

    // 4. Boucle sur chaque fichier/dossier
    foreach ($fichiers as $fichier) {
        // Affiche 1 élément (si accessible)
        echo renderOneElement($chemin, $fichier, $paths);
    }
}

function selection() {}
