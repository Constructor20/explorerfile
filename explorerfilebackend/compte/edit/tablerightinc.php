<?php
session_start();
include '../../connectdb.php';

$userId = $_POST['user_id'] ?? null;

include '../../affichage.php';

// pour récupérer les droits user pour checkbox
function pathRight ($conn){
    if (!isset($_POST['user_id'])) {
        return [];
    }
    $userId = (int) $_POST['user_id'];

    $sql = "SELECT encodedjson FROM permission WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $json = $stmt->fetchColumn();

    if (!$json) {
        return [];
    }

    $decoded = json_decode($json, true);
    
    // Retourner le tableau 'paths' si présent, sinon le tableau décod­é complet
    if (is_array($decoded) && isset($decoded['paths'])) {
        return $decoded['paths'];
    }
    
    return is_array($decoded) ? $decoded : [];
}
//modification du chemin
//
//affichage chemin

