<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 06/11/2018 - Valcir C.
 *
 * Exclui Usuario Adminstrativo do IBQUOTA
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

// Não teve variavel enviada pelo Formulario
if ( !isset($_GET['cod_adm_users']) ) {
       header("Location: index.php");
       exit();
}


$cod_adm_users = trim($_GET['cod_adm_users']);

// Não exclui adm principal
if (cod_adm_users == 1) {
   include '../includes/header.php';
   echo "<div class=\"alert alert-danger\" role=\"alert\">N&atilde;o &eacute; poss&iacute;vel excluir o usu&aacute;rio ADMIN</div>";
   include '../includes/footer.php';
   exit();
}

// Deleta Usuarios 
$deleta_stmt = $mysqli->prepare("DELETE FROM adm_users
  WHERE cod_adm_users = ? and login != 'admin'");
$deleta_stmt->bind_param('i', $cod_adm_users);
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
