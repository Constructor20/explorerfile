<?php

require_once __DIR__ . '/../../Config/paths.php';
require_once __DIR__ . '/../../Config/database.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Repositories/UserRepository.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['isadmin'] !== 1) {
    header('Location: ../../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./');
    exit();
}

$userId = $_POST['user_id'] ?? null;

if (!$userId || !is_numeric($userId)) {
    header('Location: ./');
    exit();
}

// Prevent self-modification
if ((int)$userId === (int)$_SESSION['user_id']) {
    header('Location: ./');
    exit();
}

try {
    $userRepository = new UserRepository($conn);
    $user = $userRepository->findById((int)$userId);

    if (!$user) {
        header('Location: ./');
        exit();
    }

    // Toggle admin status
    $newIsAdmin = $user->isAdmin() ? 0 : 1;

    $updatedUser = new User(
        $user->getUsername(),
        $user->getEmail(),
        $user->getPassword(),
        $newIsAdmin,
        $user->getId()
    );

    $userRepository->update($updatedUser);

    header('Location: ../');
    exit();
} catch (Exception $e) {
    error_log('Erreur dans toggle_admin.php: ' . $e->getMessage());
    header('Location: ../');
    exit();
}
