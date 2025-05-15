<?php

session_start();
include '../connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {

    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;

    if ($username) {
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
    if ($email) {
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

    header('Location: compteadmin.php');
}