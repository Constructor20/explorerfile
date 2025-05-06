<?php

include '../connectdb.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifiant = $_POST['identifiant'] ?? null;
    $password = $_POST['password'] ?? null;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $sql = "SELECT * FROM userdata WHERE email = ?";
} else {
    $sql = "SELECT * FROM userdata WHERE username = ?";
}

$stmt = $conn->query($sql);
$stmt->bindParam(1, $identifiant);

$sql = "SELECT * FROM userdata WHERE email = :identifiant OR username = :identifiant";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':identifiant', $identifiant);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
password_verify($password, $row['password']);

// foreach($stmt as $row) {
//     if ($email == $row['email']) {
//         header('Location: login.php?error=email');
//     }
//     if ($username == $row['username']) {
//         header('Location: login.php?error=username');
//     }
// }