<?php
session_start();
session_destroy();
header("Location: /explorerfilebackend/registerphp/login.php");
exit;
