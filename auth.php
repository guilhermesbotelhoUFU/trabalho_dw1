<?php
session_start();

// if ($_SESSION['usuario_id'] == '') {
//     echo 'Acesso negado';
//     exit;
// }

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.html');
    exit;
}
