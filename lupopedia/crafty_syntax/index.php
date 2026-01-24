<?php
session_start();

if (empty($_SESSION['auth_user_id'])) {
    header('Location: login.php');
    exit;
}

echo "Operator Console\n";
echo "next step here\n";
