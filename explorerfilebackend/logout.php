<?php
session_start();
session_destroy();
header("Location: /explorerfilebackend/registerphp/login.php");
exit;

// <label>Mot de passe</label>
// <input type="password" name="password" id="password" placeholder="*********">
// <?php if(!empty($_GET["error"])) {
//     if($_GET["error"] == "password") {
//     echo "Le mot de passe est invalide ou incorrect";
//     }
// };