<?php

require_once '../../Config/paths.php';
require_once '../../Config/database.php';
require_once '../../Repositories/PermissionRepository.php';
require_once '../../Models/Permission.php';

session_start();

$userId = $_POST['user_id'] ?? null;
$permissionsJson = $_POST['permissions'] ?? null;

if (!$userId || !$permissionsJson) {
    die('Données invalides');
}

try {
    $repository = new PermissionRepository($conn);
    $paths = json_decode($permissionsJson, true);

    if (!is_array($paths)) {
        $paths = [];
    }

    $permission = new Permission((int) $userId, $paths);
    $repository->save($permission);

    header('Location: tableright.php?user_id=' . $userId);
    exit;

} catch (Exception $e) {
    error_log('Erreur dans tablerightinc.php: ' . $e->getMessage());
    die('Erreur lors de la sauvegarde des permissions.');
}
