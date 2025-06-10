<?php

include '../connectdb.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifiant = $_POST['identifiant'] ?? null;
    $password = $_POST['password'] ?? null;

    if (empty($identifiant)) {
        header('Location: login.php?error=emptyid');
        exit;
    }
    if (empty($password)) {
        header('Location: login.php?error=emptypassword');
        exit;
    }

    $sql = "SELECT * FROM userdata WHERE email = :id OR username = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $identifiant);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$row) {
        header('Location: login.php?error=emptyid');
        exit;
    }
    if(!password_verify($password, $row["password"])) {
        header('Location: login.php?error=emptypassword');
        exit;
    }

    session_start();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['username'] = $row['username'];

    if($row['id'] == 1) {
        header('Location: ../compte/compteadmin.php');
        exit;
    }
    
    header('Location: ../compte/compte.php');
}