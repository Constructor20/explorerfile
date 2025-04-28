<?php include 'connectdb.php' ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // $checkEmailStmt = $conn->prepare("SELECT email FROM userdata WHERE email = ?");
    // echo $checkEmailStmt->execute(array("chris"=> $username,"aleixochristophe@gmail.com"=> $email,"password"=> $password));
}
