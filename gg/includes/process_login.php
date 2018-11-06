<?php
include_once 'db.php';
include_once 'functions.php';
 
sec_session_start(); 
 
if (isset($_POST['login'], $_POST['senha'])) {
    $login = $_POST['login'];
    $password = $_POST['senha']; 
 
    if (login($login, $password, $mysqli) == true) {
        // Login com sucesso 
        header('Location: ../index.php');
    } else {
        // Falha de login 
        header('Location: ../login.php?error=1');
    }
} else {
        // Falha de login 
        header('Location: ../login.php?error=1');
}
