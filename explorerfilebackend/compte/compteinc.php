<?php

session_start();
include '../connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {

    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($username !== $_SESSION['username']) {
        if (empty($username) or trim($username) == "") {
            header('Location: compte.php?error=username');
            exit;
        }
    }
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
    if ($password !== $_SESSION['password']) {
        if (empty($email) or trim($password) == "") {
            header('Location: compte.php?error=pasword');
            exit;
        }
        if (!filter_var($password, FILTER_VALIDATE_EMAIL)) {
            header('Location: compte.php?error=pasword');
            exit;
        }
    }
}