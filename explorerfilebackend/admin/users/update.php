<?php

session_start();
$conn = require __DIR__ . '/../../Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {

    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $user_id = $_POST['user_id'] ?? null;

    $sql = "SELECT id FROM userdata WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);


    if (!$result){
        header('Location: ../?error=notfound');
        exit;
    }
    if(!$user_id){
        header('Location: ../?error=notfound');
        exit;
    }
    if(!$username){
        header('Location: ../?error=notfound');
        exit;
    }
    if (empty($username) or trim($username) == "") {
        header('Location: ../?error=username');
        exit;
    }
    if(!$email){
        header('Location: ../?error=notfound');
        exit;
    }
    if (empty($email) or trim($email) == "") {
        header('Location: ../?error=invalid_email');
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../?error=invalid_email');
        exit;
    }

    $sql = "UPDATE userdata SET username = :username, email = :email WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    header('Location: ../?success=success');
    exit;
}
