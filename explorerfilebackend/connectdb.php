<?php

$servername = "host.docker.internal";
$username = "root";
$password = "";
$dbname = "file_explorer_bdd";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Active le mode exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Erreur attrapée : " . $e->getMessage();
}

