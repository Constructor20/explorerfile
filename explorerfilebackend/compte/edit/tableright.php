<?php

require_once "../../Config/paths.php";
require_once "../../Config/database.php";
require_once "../../Services/FileSystemService.php";
require_once "../../Services/PermissionService.php";
require_once "../../Repositories/PermissionRepository.php";

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../registerphp/login.php");
    exit();
}

$userId = $_GET["user_id"] ?? ($_POST["user_id"] ?? null);

if (!$userId) {
    die("ID utilisateur requis");
}

try {
    $fileSystemService = new FileSystemService(BASE_DIR);
    $permissionService = new PermissionService($conn);

    $treeData = $fileSystemService->buildTreeWithLeadingSlash();
    $permission = $permissionService->getExistingPermissions($userId);
    $existingPermissions = $permission->getPaths();

    require "../../Views/tableright_view.php";
} catch (Exception $e) {
    error_log("Erreur dans tableright.php: " . $e->getMessage());
    die("Une erreur est survenue lors du chargement des données.");
}
