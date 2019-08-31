<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 12/11/2018 - Valcir C.
 *
 * Lista Impressoes
 */ 
include_once '../includes/db.php';
include_once '../includes/functions.php';
 
sec_session_start();


if (login_check($mysqli) == false) {
  header("Location: ../login.php");
  exit();
}

include '../includes/header.php';

//PAGINACAO
$p = (isset($_GET['p'])) ? (int)$_GET['p'] : 1;
$p = ($p < 1) ? 1 : $p;
$p_inicio = (QTDE_POR_PAGINA * $p) - QTDE_POR_PAGINA;
$p_qtde_por_pagina = (int)QTDE_POR_PAGINA; 
$p_num_registros=0;
if ($num_stmt = $mysqli->prepare("SELECT count(*) 
        FROM impressoes")) {
    $num_stmt->execute();
    $num_stmt->bind_result($p_num_registros);
    $num_stmt->fetch();
    $num_stmt->close();
}


 
// Busca impressoes no banco de dados 
if ($stmt = $mysqli->prepare("SELECT DATE_FORMAT(data_impressao,'%d/%m/%y') ,hora_impressao,job_id,impressora,
              usuario,estacao, nome_documento, paginas, cod_politica, cod_status_impressao
      FROM impressoes
      Order by cod_impressoes DESC LIMIT ?, ?")) {
    $stmt->bind_param('ii', $p_inicio,$p_qtde_por_pagina);
    $stmt->execute(); 
    $stmt->store_result();
    $stmt->bind_result($data_impressao,$hora_impressao,$job_id,$impressora,$usuario, $estacao,$nome_documento,$paginas,$cod_politica,$cod_status_impressao);
}


?>

<center><h2><font color=#428bca>Impress&otilde;es</font></h2><br>
   <table border="0" width="1000" align="center">
    <tr><td>
    <div class="panel panel-default">
      <div class="container-fluid">
       
        <table class="table table-hover table-sm">
          <thead>
            <tr>
              <th scope="col">Data</th>
              <th scope="col">Hora</th>
              <th scope="col">Job ID</th>
              <th scope="col">Usu&aacute;rio</th>
              <th scope="col">Impressora</th>
              <th scope="col">Esta&ccedil;&atilde;o</th>
              <th scope="col">Documento</th>
              <th scope="col">P&aacute;gina</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>



<?php
   $sem_grupo = 1;

   // Lista impressoes deste usuario
   while ($stmt->fetch()) {
      if ($cod_status_impressao == 1) {
         echo "<tr>";
      } else {
         echo "<tr class=\"bg-danger\">";
      }
      echo "<td>$data_impressao</td>";
      echo "<td>$hora_impressao</td>";    
      echo "<td>$job_id</td>"; 
      echo "<td><b>$usuario</b></td>";
      echo "<td>$impressora</td>";
      echo "<td>$estacao</td>";
      echo "<td>$nome_documento</td>";
      echo "<td>$paginas</td>";
      echo "<td>";
      echo status_impressao($cod_status_impressao); 
      echo "</td>\n";
      $sem_grupo = 0; 
   } 
   if ($sem_grupo == 1) {
      echo "<tr><td colspan=\"7\"><i>Sem registro de Impress&otilde;o</i></td></tr>";
   } else {
      echo "<tr><td colspan=\"7\">";
      barra_de_paginas($p,$p_num_registros);
      echo "</td></tr>";
   }


?>
            </tbody>
        </table>

      </div>
     </div>

    </td></tr>
   </table>
   </center>

<?php
   include '../includes/footer.php';
?>
