<?php

include '../connectdb.php';

$sql = "SELECT * FROM userdata";
$stmt = $conn->prepare( $sql );
$stmt->execute();

if(){

};