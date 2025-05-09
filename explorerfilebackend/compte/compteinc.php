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
        $sql = "UPDATE username FROM userdata WHERE = id = :id";
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
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
    }

    //VALIDATION NEW PASSWORD
    if (empty($password) or trim($password) == "") {
        header('Location: compte.php?error=pasword');
        exit;
    }
    $stmt = $conn->prepare("SELECT password FROM userdata WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password, $row['password'])) {
        header('Location: compte.php?error=password');
        exit;
    }

    // $stmt = $conn->prepare('');
}