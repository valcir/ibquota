<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 13/11/2018 - Valcir C.
 *
 * Lista Logs do DAEMON
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
        FROM log_ibquota")) {
    $num_stmt->execute();
    $num_stmt->bind_result($p_num_registros);
    $num_stmt->fetch();
    $num_stmt->close();
}


 
// Busca log no banco de dados 
if ($stmt = $mysqli->prepare("SELECT id,mensagem,datahora
      FROM log_ibquota
      Order by id DESC LIMIT ?, ?")) {
    $stmt->bind_param('ii', $p_inicio,$p_qtde_por_pagina);
    $stmt->execute(); 
    $stmt->store_result();
    $stmt->bind_result($id,$mensagem,$datahora);
}


?>

<center><h2><font color=#428bca>Log do Backend CUPS</font></h2><br>
   <table border="0" width="800" align="center">
    <tr><td>
    <div class="panel panel-default">
      <div class="container-fluid">
       
        <table class="table table-hover table-sm">
          <thead>
            <tr>
              <th scope="col">Data</th>
              <th scope="col">Mensagem</th>
            </tr>
          </thead>
          <tbody>



<?php
   $sem_log = 1;

   // Lista log deste usuario
   while ($stmt->fetch()) {
      echo "<tr>";
      echo "<td><b>". $datahora ."</b></td>";
      echo "<td>$mensagem</td>\n";
      $sem_log = 0;
   } 
   if ($sem_log == 1) {
      echo "<tr><td colspan=\"2\"><i>Sem registro de Log. Se houve erro de acesso ao Banco de Dados entao o BACKEND/CUPS gravou log em <code> /tmp/ibquota3.log</code>.</i></td></tr>";
   } else {
      echo "<tr><td colspan=\"2\">";
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
