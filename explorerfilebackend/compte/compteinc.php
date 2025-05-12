<?php

session_start();
include '../connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {

    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    //VALIDATION NEW USERNAME
    if ($username !== $_SESSION['username']) {
        if (empty($username) or trim($username) == "") {
            header('Location: compte.php?error=username');
            exit;
        }
        $sql = "UPDATE userdata SET username = :username WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $_SESSION['username'] = $username;
        header('Location: compte.php?success=username');
    }

    //VALIDATION NEW EMAIL
    if ($email !== $_SESSION['email']) {
        if (empty($email) or trim($email) == "") {
            header('Location: compte.php?error=invalid_email');
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: compte.php?error=invalid_email');
            exit;
        }
        $sql = "UPDATE userdata SET email = :email WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $_SESSION['email'] = $email;
        header('Location: compte.php?success=email');
    }

    //VALIDATION NEW PASSWORD
    if (empty($password) or trim($password) == "") {
        header('Location: register.php?error=password');
        exit;
    }

    $stmt = $conn->prepare("SELECT password FROM userdata WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($password, htmlspecialchars($row['password']))) {
        header('Location: compte.php?error=password');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE userdata SET password = :newpassword WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->bindParam(':newpassword', $hashedPassword);
    $stmt->execute();

    
}