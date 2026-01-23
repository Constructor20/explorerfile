<?php

require_once '../../Config/database.php';

session_start();

$user_id = $_POST['user_id'] ?? null;
$new_password = $_POST['new_password'] ?? null;
$confirm_password = $_POST['confirm_password'] ?? null;

if (!$user_id || !$new_password || !$confirm_password) {
    header('Location: index.php?user_id=' . $user_id . '&password_error=Tous les champs sont requis');
    exit;
}

if (empty($new_password) || trim($new_password) == "") {
    header('Location: index.php?user_id=' . $user_id . '&password_error=Le mot de passe ne peut pas être vide');
    exit;
}

if ($new_password !== $confirm_password) {
    header('Location: index.php?user_id=' . $user_id . '&password_error=Les mots de passe ne correspondent pas');
    exit;
}

$hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

try {
    $sql = "UPDATE userdata SET password = :password WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: index.php?user_id=' . $user_id . '&password_success=Mot de passe modifié avec succès');
    exit;
} catch (PDOException $e) {
    error_log('Erreur dans update_password.php: ' . $e->getMessage());
    header('Location: index.php?user_id=' . $user_id . '&password_error=Erreur lors de la modification du mot de passe');
    exit;
}
