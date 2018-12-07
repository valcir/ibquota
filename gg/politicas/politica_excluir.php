<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 16/11/2018 - Valcir C.
 *
 * Exclui Politica
 */  
include_once '../includes/db.php';
include_once '../includes/functions.php';
 
sec_session_start();

if (login_check($mysqli) == false) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['permissao'] != 2){
    header("Location: ../login.php");
    exit();
}

// Não teve variavel enviada
if ( !isset($_GET['cod_politica']) ) {
    header("Location: index.php");
    exit();
}

$cod_politica = trim($_GET['cod_politica']);

// Deleta Politica Grupo
$deleta_stmt = $mysqli->prepare("DELETE FROM politica_grupo
  WHERE cod_politica = ?");
$deleta_stmt->bind_param('i', $cod_politica);
$deleta_stmt->execute();
$deleta_stmt->close();

// Deleta Politica Impressora
$deleta_stmt = $mysqli->prepare("DELETE FROM politica_impressora
  WHERE cod_politica = ?");
$deleta_stmt->bind_param('i', $cod_politica);
$deleta_stmt->execute();
$deleta_stmt->close();

// Deleta Politica Quota Usuario
$deleta_stmt = $mysqli->prepare("DELETE FROM quota_usuario
  WHERE cod_politica = ?");
$deleta_stmt->bind_param('i', $cod_politica);
$deleta_stmt->execute();
$deleta_stmt->close();

// Deleta Politica
$deleta_stmt = $mysqli->prepare("DELETE FROM politicas
  WHERE cod_politica = ?");
$deleta_stmt->bind_param('i', $cod_politica);
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
