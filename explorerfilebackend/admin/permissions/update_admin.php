<?php

require_once "../../Config/paths.php";
require_once "../../Config/database.php";
require_once "../../Repositories/UserRepository.php";

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["isadmin"] !== 1) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ./");
    exit();
}

$userId = $_POST["user_id"] ?? null;
$isAdmin = isset($_POST["is_admin"]) ? 1 : 0;

if (!$userId || !is_numeric($userId)) {
    header("Location: ../../admin/");
    exit();
}

try {
    $userRepository = new UserRepository($conn);
    $user = $userRepository->findById((int)$userId);

    if (!$user) {
        header("Location: ../../admin/");
        exit();
    }

    // Prevent self-demotion
    if ((int)$userId === (int)$_SESSION["user_id"] && $isAdmin === 0) {
        header("Location: ./?user_id=" . $userId);
        exit();
    }

    // Create updated user with new admin status
    $updatedUser = new User(
        $user->getUsername(),
        $user->getEmail(),
        $user->getPassword(),
        $isAdmin,
        $user->getId()
    );

    $userRepository->update($updatedUser);

    header("Location: ./?user_id=" . $userId);
    exit();
} catch (Exception $e) {
    error_log("Erreur dans update_admin.php: " . $e->getMessage());
    header("Location: ./?user_id=" . $userId);
    exit();
}
