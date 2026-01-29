<?php

include '../connectdb.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifiant = $_POST['identifiant'] ?? null;
    $password = $_POST['password'] ?? null;

    //check si vide
    if (empty($identifiant)) {
        header('Location: login.php?error=emptyid');
        exit;
    }
    if (empty($password)) {
        header('Location: login.php?error=emptypassword');
        exit;
    }

    //check si user existe
    $sql = "SELECT * FROM userdata WHERE email = :id OR username = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $identifiant);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //dit si erreur
    if(!$row) {
        header('Location: login.php?error=emptyid');
        exit;
    }
    if(!password_verify($password, $row["password"])) {
        header('Location: login.php?error=emptypassword');
        exit;
    }

    //lance session
    session_start();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['isadmin'] = $row['isadmin'];

    // Si id est 1 ---> compteadmin.php (a modifier pour user est admin)
    if($row['isadmin'] == 1) {
        header('Location: ../compte/compteadmin.php');
        exit;
    }
    
    if ($_SESSION)
    header('Location: ../compte/compte.php');
}