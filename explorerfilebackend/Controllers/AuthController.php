<?php

require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Config/database.php';

session_start();

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        global $conn;
        $this->authService = new AuthService($conn);
    }

    public function handleRegister(): void
    {
        $email = $_POST['email'] ?? null;
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirm_password'] ?? null;

        $result = $this->authService->register($email, $username, $password, $confirmPassword);

        if ($result['success']) {
            header('Location: /login.php');
            exit;
        }

        $error = $result['error'] ?? 'unknown';
        header("Location: /register.php?error=$error");
        exit;
    }

    public function handleLogin(): void
    {
        $identifier = $_POST['identifiant'] ?? null;
        $password = $_POST['password'] ?? null;

        $result = $this->authService->login($identifier, $password);

        if (!$result['success']) {
            $error = $result['error'] ?? 'unknown';
            header("Location: /login.php?error=$error");
            exit;
        }

        $user = $result['user'];
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['isadmin'] = $user->isAdmin() ? 1 : 0;

        if ($user->isAdmin()) {
            header('Location: /admin.php');
            exit;
        }

        header('Location: /profile.php');
        exit;
    }

    public static function logout(): void
    {
        $authService = new AuthService(require __DIR__ . '/../Config/database.php');
        $authService->logout();
        header('Location: /login.php');
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller = new AuthController();
    $action = $_GET['action'] ?? '';

    if ($action === 'register') {
        $controller->handleRegister();
    } elseif ($action === 'login') {
        $controller->handleLogin();
    }
}
