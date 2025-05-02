<?php
session_start();
session_destroy();
session_start(); // inicia nova sessão para flash
$_SESSION['flash'] = "Logout realizado com sucesso!";
$_SESSION['redirect_to'] = 'login.php';
header('Location: ../public/login.php');
exit;