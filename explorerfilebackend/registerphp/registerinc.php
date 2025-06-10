<?php

include '../connectdb.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirm_password = $_POST['confirm_password'] ?? null;

    $sql = "SELECT email, username FROM userdata";
    $stmt = $conn->query($sql);
    foreach($stmt as $row) {
        if ($email == $row['email']) {
            header('Location: register.php?error=email');
        }
        if ($username == $row['username']) {
            header('Location: register.php?error=username');
        }
    }

    //USERNAME VALIDATION
    if (empty($username) or trim($username) == "") {
        header('Location: register.php?error=username');
        exit;
    }
    //EMAIL VALIDATION
    if (empty($email) or trim($email) == "") {
        header('Location: register.php?error=email');
        exit;
    }
    //EMAIL FILTER VALIDATION
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: register.php?error=invalid_email');
        exit;
    }
    //PASSWORD VALIDATION
    if (empty($password) or trim($password) == "") {
        header('Location: register.php?error=password');
        exit;
    }
    //PASSWORD FILTER VALIDATION
    if (!preg_match('/^[A-Za-z0-9]+$/', $password)) {
        header('Location: register.php?error=invalid_password');
        exit;
    }
    //CONFIRM PASSWORD VALIDATION
    if (empty($confirm_password) or trim($confirm_password) == "") {
        header('Location: register.php?error=confirm_password');
        exit;
    }  
    //SAME PASSWORD AND CONFIRM VALIDATION
    if ($password !== $confirm_password) {
        header('Location: register.php?error=nonesamepassword');
        exit;
    }
 
    $username = trim($username);
    $email = trim($email);
    $hashPassword = password_hash(trim($password), PASSWORD_DEFAULT);
    $sql = "INSERT INTO userdata (username, email, password) VALUES(?, ?, ?)";
    $stmt = $conn->prepare($sql, [PDO::ERRMODE_EXCEPTION]);
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $hashPassword);
    $exec = $stmt->execute();

    header('Location: ../compte/compte.php');
}