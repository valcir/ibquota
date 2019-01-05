<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 04/01/2019 - Valcir C.
 *
 * Mostra Impressoras ativas no CUPS (
 * Sugestao do Romulo/UFRN
 */  
include_once 'includes/db.php';
include_once 'includes/functions.php';

 
sec_session_start();

if (login_check($mysqli) == false) {
  header("Location: ../login.php");
  exit();
}

if ($_SESSION['permissao'] < 1){
  header("Location: ../login.php");
  exit();
}
 
include 'includes/header.php';

$file_printers = "/etc/cups/printers.conf";

if (! file_exists($file_printers)) {
	include 'includes/footer.php';
	exit;
}
?>


<center><h2>Lista de Impressoras do CUPS</h2><br>

   <table border="0" width="1000" align="center">
    <tr><td>
    <div class="panel panel-default">
      <div class="container-fluid">
       
        <table class="table table-hover table-sm">
          <thead>
            <tr>
             
              <th scope="col">Impressora</th>
              <th scope="col">Device</th>
              <th scope="col">Status</th>
              <th scope="col">Backend IBQUOTA</th>
              <th scope="col">Pol&iacute;ticas associadas</th>
              <th scope="col">Job em 6 meses</th>
            </tr>
          </thead>
          <tbody>

<?php


$nome_impressora = "";
$device_impressora = "";
$status_impressora = "";

$linhas= file($file_printers);
foreach($linhas as $linha)
{
   $linha = trim(strtolower($linha));
   if (strpos($linha, "<printer ") === FALSE) {
    if (strlen($nome_impressora) > 1) {
   	  	  if (substr($linha, 0, 6) == "state ") {
   	  	  	$status_impressora = substr($linha, 6);
   	  	  }
   	  	  if (substr($linha, 0, 9) == "deviceuri ") {
   	  	  	$device_impressora = substr($linha, 9);
   	  	  }
   	}
   } else {
      $nome_impressora = trim(substr($linha, 9,-1));        
   }
   
   if (! strpos($linha, "</printer>") === FALSE) {
   	 // Fim sessao </Printer>
     if (strlen($nome_impressora) > 1) {
     	echo "<tr>";
     	echo " <td>$nome_impressora</td>";
     	echo " <td>$device_impressora</td>";
     	echo " <td>$status_impressora</td>";
     	if (strpos($nome_impressora,"ibquota3:") === FALSE) {
     	   echo " <td><font color=red>Sem Backend ibquota3</font></td>";
     	} else {
     	   echo " <td>Com backend ibquota3</td>";
        } 
     	echo " <td>?</td>";
     	echo " <td>?</td>";
     	echo "</tr>\n";
     }
     
   }



}


?>
      </tbody>
     </table>
     </div>
    </div>
   </td></tr>
  </table>
</center>



<?php include 'includes/footer.php'; ?>
