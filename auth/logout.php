<?php
session_start();
session_destroy();
header("Location: /card_vault_php/auth/login.php");
exit();
?>