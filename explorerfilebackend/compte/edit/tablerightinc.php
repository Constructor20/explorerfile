<?php
session_start();
include '../../connectdb.php';

$userId = $_POST['user_id'] ?? null;
echo "User ID reçu pour modification des droits : " . $userId;

function table($chemin, $filter) {
    
}

var_dump($_POST);