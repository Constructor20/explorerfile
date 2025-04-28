<?php

$servername = "host.docker.internal:3306";
$username = "root";
$password = "";
$dbname = "file_explorer_bdd";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", username: $username, password: $password);
    // Paramétrer le mode d'erreur de PDO pour les exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie!";
} catch(PDOException $e) {
    echo "Échec de la connexion: " . $e->getMessage();
}

