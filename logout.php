<?php
session_start();

// unset($_SESSION['usuario_id']);
// unset($_SESSION['usuario_nome']);
$_SESSION = array();
session_destroy();

// header('Location: index.html');
header('Location: login.html');
exit;
