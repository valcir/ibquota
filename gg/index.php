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

include_once 'includes/status_dash.php';
include 'includes/header.php';


echo "<div class=\"card-columns\">\n";

top_usuarios_hoje($mysqli);
top_usuarios_mes($mysqli);
qtde_impressoes_hoje($mysqli);
qtde_impressoes_mes($mysqli);
erros_log_ibquota($mysqli);

echo "</div>\n";

?>









<?php include 'includes/footer.php'; ?>
