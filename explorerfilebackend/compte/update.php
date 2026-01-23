<?php

session_start();
$conn = require __DIR__ . '/../Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {

    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;

    if ($username !== $_SESSION['username']) {
        if (empty($username) or trim($username) == "") {
            header('Location: ?error=username');
            exit;
        }
        $sql = "UPDATE userdata SET username = :username WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $_SESSION['username'] = $username;
        header('Location: ?success=username');
        exit;
    }

    if ($email !== $_SESSION['email']) {
        if (empty($email) or trim($email) == "") {
            header('Location: ?error=invalid_email');
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: ?error=invalid_email');
            exit;
        }
        $sql = "UPDATE userdata SET email = :email WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $_SESSION['email'] = $email;
        header('Location: ?success=email');
        exit;
    }

    header('Location: ');
    exit;
}
