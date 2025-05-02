<?php

include '../connectdb.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirm_password = $_POST['confirm_password'] ?? null;

    $erreur = false;
    if (empty($username) or " ") {
        echo "Le champ username est vide.<br>";
        $erreur = true;
    }

    if (empty($email) or " ") {
        echo "Le champ email est vide.<br>";
        $erreur = true;
    }

    if (empty($password) or " ") {
        echo "Le champ mot de passe est vide.<br>";
        $erreur = true;
    }

    if (empty($confirm_password)) {
        echo "Le champ de confirmation du mot de passe est vide.<br>";
        $erreur = true;
    }  

    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.<br>";
        $erreur = true;
    }

    if($erreur){
    exit;
    }


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

    $hashpassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO userdata (username, email, password) VALUES(?, ?, ?)";
    $stmt = $conn->prepare($sql, [PDO::ERRMODE_EXCEPTION]);
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $hashpassword);
    $exec = $stmt->execute();

    header('Location: ../compte.php');
}