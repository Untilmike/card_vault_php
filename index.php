<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /card_vault_php/pages/dashboard.php");
} else {
    header("Location: /card_vault_php/auth/login.php");
}
exit();
?>