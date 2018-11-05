<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 05/11/2018 - Valcir C.
 *
 * Index.php Pagina Principal
 */  
include_once 'includes/db.php';
include_once 'includes/functions.php';

 
sec_session_start();

if (login_check($mysqli) == false) {
  header("Location: login.php");
  exit();
}

//if ($_SESSION['permissao'] != 0){
//  header("Location: ../login.php");
//  exit();
//}
 
include 'includes/header.php';

?>









<?php include 'includes/footer.php'; ?>
