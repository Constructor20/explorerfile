<?php
session_start();
include '../../connectdb.php';

$userId = $_POST['user_id'] ?? null;
echo "User ID reçu pour modification des droits : " . $userId;

$chemin = isset($_GET['path']) ? $_GET['path'] : __DIR__ . "/new";

$sql = "SELECT id, username, email, isadmin FROM userdata WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
function table($chemin, $user) {
    $icon_folder = "icon/folder2.png";
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
        $fichiers = scandir($chemin);

        foreach ($fichiers as $fichier) {
        $chemin_complet = $chemin . DIRECTORY_SEPARATOR . $fichier;
        $chemin_url = urlencode($chemin_complet);
        $icon = findicon($chemin_complet,);
          // $fichier !== "." && $fichier !== ".."
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
        }
    }