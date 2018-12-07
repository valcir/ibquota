<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 08/11/2018 - Valcir C.
 *
 * Excluir Usuario
 */  
include_once '../includes/db.php';
include_once '../includes/functions.php';
 
sec_session_start();

if (login_check($mysqli) == false) {
  header("Location: ../login.php");
  exit();
}

if ($_SESSION['permissao'] < 1){
  header("Location: ../login.php");
  exit();
}

// Não teve variavel enviada pelo Formulario
if ( !isset($_GET['cod_usuario']) ) {
       header("Location: index.php");
       exit();
}


$cod_usuario = trim($_GET['cod_usuario']);
$stmt = $mysqli->prepare("SELECT usuario
    FROM usuarios WHERE cod_usuario = ?");
$stmt->bind_param('i', $cod_usuario);
$stmt->execute(); 
$stmt->store_result();
$stmt->bind_result($usuario);
$stmt->fetch();

// Deleta Grupo-Usuario
$deleta_stmt = $mysqli->prepare("DELETE FROM grupo_usuario
  WHERE cod_usuario = ?");
$deleta_stmt->bind_param('i', $cod_usuario);
$deleta_stmt->execute();
$deleta_stmt->close();

// Deleta quota 
$deleta_stmt = $mysqli->prepare("DELETE FROM quota_usuario
  WHERE usuario = ?");
$deleta_stmt->bind_param('s', $usuario);
$deleta_stmt->execute();
$deleta_stmt->close();

// Deleta Usuario 
$deleta_stmt = $mysqli->prepare("DELETE FROM usuarios
  WHERE cod_usuario = ?");
$deleta_stmt->bind_param('i', $cod_usuario);
$deleta_stmt->execute();
$deleta_stmt->close();

$p = $_GET['p'];
if ($p > 1) {
   header("Location: index.php?p=" . $p);
} else {
   header("Location: index.php");
} 

exit();
  
?>
