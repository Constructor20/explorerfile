<?php

require_once "../../Config/paths.php";
require_once "../../Config/database.php";
require_once "../../Services/FileSystemService.php";
require_once "../../Services/PermissionService.php";
require_once "../../Repositories/PermissionRepository.php";
require_once "../../Repositories/UserRepository.php";

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit();
}

$userId = $_GET['user_id'] ?? null;

if (!$userId || !is_numeric($userId)) {
    die("ID utilisateur requis");
}

try {
    $userRepository = new UserRepository($conn);
    $user = $userRepository->findById((int)$userId);
    if (!$user) {
        die("Utilisateur non trouvé");
    }
    $userName = $user->getUsername();
    $userEmail = $user->getEmail();
    $userIsAdmin = $user->isAdmin();

    $fileSystemService = new FileSystemService(BASE_DIR);
    $permissionService = new PermissionService($conn);

    $treeData = $fileSystemService->buildTreeWithLeadingSlash();
    $permission = $permissionService->getExistingPermissions($userId);
    $existingPermissions = $permission->getPaths();

    require "../../Views/tableright_view.php";
} catch (Exception $e) {
    error_log("Erreur dans permissions/index.php: " . $e->getMessage());
    die("Une erreur est survenue lors du chargement des données.");
}
