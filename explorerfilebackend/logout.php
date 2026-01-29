<?php
if (!session_start()){
    session_destroy();
}
header("Location: registerphp/login.php");
exit;
