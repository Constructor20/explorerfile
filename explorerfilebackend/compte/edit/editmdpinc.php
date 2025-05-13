<?php

session_start();
include '../../connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetpswd'])) {

    $password = $_POST['old_password'] ?? null;
    $newpassword = $_POST['new_password'] ?? null;
    $confirmpassword = $_POST['confirm_password'] ?? null;

    if (empty($password) or trim($password) == "") {
        header('Location: editmdp.php?error=emptypassword1');
        exit;
    }
    if (empty($newpassword) or trim($newpassword) == "") {
        header('Location: editmdp.php?error=emptypassword2');
        exit;
    }
    if (empty($confirmpassword) or trim($confirmpassword) == "") {
        header('Location: editmdp.php?error=emptypassword3');
        exit;
    }

    if($password == $newpassword) {
        header('editmdp.php?error=notsamepassword');
        exit();
    }
    if($newpassword !== $confirmpassword) {
        header('editmdp.php?error=notsamepassword2');
        exit();
    } else {
    $sql = "SELECT password FROM userdata WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($password, htmlspecialchars($row['password']))) {
        header('Location: editmdp.php?error=notsamepassword');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE userdata SET password = :newpassword WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->bindParam(':newpassword', $hashedPassword);
    $stmt->execute();
    }

    header('Location: ../compte.php');
};