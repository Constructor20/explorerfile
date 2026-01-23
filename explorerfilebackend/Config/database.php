<?php

try {
    $conn = new PDO(
        "mysql:host=host.docker.internal;port=3306;dbname=explorerfile;charset=utf8",
        "root",
        "example",
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Erreur connexion BDD : " . $e->getMessage());
    die("Erreur de connexion à la base de données");
}

return $conn;
