<?php
if (!session_start()){
    session_destroy();
} else {
}
header("Location: registerphp/login.php");
exit;
