<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 15/11/2018 - Valcir C.
 *
 * Lista Politicas de Impressao
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

include '../includes/header.php';

//PAGINACAO
$p = (isset($_GET['p'])) ? (int)$_GET['p'] : 1;
$p = ($p < 1) ? 1 : $p;
$p_inicio = (QTDE_POR_PAGINA * $p) - QTDE_POR_PAGINA;
$p_qtde_por_pagina = (int)QTDE_POR_PAGINA; 
$p_num_registros=0;
if ($num_stmt = $mysqli->prepare("SELECT count(*) 
        FROM politicas")) {
    $num_stmt->execute();
    $num_stmt->bind_result($p_num_registros);
    $num_stmt->fetch();
    $num_stmt->close();
}


 
// Busca Politicas no banco de dados 
if ($stmt = $mysqli->prepare("SELECT cod_politica,nome,quota_acumulativa,quota_infinita,
          quota_padrao,prioridade
      FROM politicas
      Order by prioridade DESC LIMIT ?, ?")) {
    $stmt->bind_param('ii', $p_inicio,$p_qtde_por_pagina);
    $stmt->execute(); 
    $stmt->store_result();
    $stmt->bind_result($cod_politica,$nome,$quota_acumulativa,$quota_infinita,$quota_padrao,$prioridade);
}


?>

<center><h2><font color=#428bca>Pol&iacute;ticas de Impress&atilde;o</font></h2><br>
   <table border="0" width="1000" align="center">
    <tr><td>
    <div class="panel panel-default">
      <div class="container-fluid">
       
        <table class="table table-hover table-sm">
          <thead>
            <tr>
              <th scope="col">Nome</th>
              <th scope="col">Quota_padr&atilde;o</th>
              <th scope="col">Quota Acumulativa</th>
              <th scope="col">Prioridade</th>
              <th scope="col">A&ccedil;&otilde;es</th>              
            </tr>
          </thead>
          <tbody>



<?php
   $sem_politica = 1;

   // Lista politicas deste
   while ($stmt->fetch()) {
      echo "<tr>";
      echo "<td>$nome</td><td>";
      if ($quota_infinita == 1) {
        echo "<i>Quota Infinita</i>";
      } else {
        echo "<b>$quota_padrao</b> impress&otilde;es";
      }
      echo "</td><td>";
      if ($quota_acumulativa == 1) {
        echo "Acumulativa";
      } else {
        echo "N&atilde;o Acumulativa";
      }
      echo "</td><td>";
      echo "$prioridade";
      echo "</td><td>";
      ?>
      <a href="politica_editar.php<?php echo '?cod_politica=' . $cod_politica; ?>">
        <button type="button" class="btn btn-info btn-sm" title="Editar Pol&iacute;tica">E</button>
      </button></a>

      <a href="politica_excluir.php<?php echo '?cod_politica=' . $cod_politica; ?>">
        <button type="button" class="btn btn-danger btn-sm" title="Excluir Pol&iacute;tica">X</button>
      </a>

      <?php
      echo "</td>\n";
      $sem_politica = 0;
   } 
   if ($sem_politica == 1) {
      echo "<tr><td colspan=\"7\"><i>N&atilde;o h&aacute; pol&iacute;tica cadastrada!</i></td></tr>";
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


    <tr><td align="center">



    <form  action="politica_add.php" method="post">
      <button type="submit" class="btn btn-primary">Cadastrar Nova Pol&iacute;tica</button>
    </form><br>


    </td></tr>





   </table>
   </center>

<?php
   include '../includes/footer.php';
?>
