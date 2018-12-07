<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 02/12/2018 - Valcir C.
 *
 * Inicialização Manual de Quota de usuarios
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


// Não teve variavel enviada pelo Formulario
if (isset($_POST['cod_politica']) == false ) {
  
?>


  <center>
    <h2><font color=#428bca>Inicializa&ccedil;&atilde;o manual de quota de usu&aacute;rios</font></h2>
  <br>
  <table border="0" width="800" align="center">
  <tr><td>
  <div class="panel panel-default">
      <div class="container-fluid">
          <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">          

       
              <div class="card mb-4 shadow-sm" >
    <div class="card-header bg-success">
      <h4 class="my-0 font-weight-normal">Selecione a Pol&iacute;tica de Impress&atilde;o</h4>
    </div>
    <div class="card-body">
            <fieldset class="form-group">
              <div class="row">
                <div class="col-sm-10">

<?php

$stmt = $mysqli->prepare("SELECT cod_politica,nome, quota_padrao, quota_infinita 
        FROM politicas
        ORDER BY nome");
// $stmt->bind_param('ii', $p_inicio,$p_qtde_por_pagina);
$stmt->execute(); 
$stmt->store_result();
$stmt->bind_result($cod_politica,$nome, $quota_padrao, $quota_infinita);
while ($stmt->fetch()) {

    echo "<div class=\"form-check";
    if ($quota_infinita == 1) {
      echo " disabled\">\n";
    } else {
      echo "\">\n";
    }
    echo "<input class=\"form-check-input\" type=\"radio\" name=\"cod_politica\" value=\"";
    echo $cod_politica . "\"";
    if ($quota_infinita == 1) {
      echo " disabled>\n";
    } else {
      echo ">\n";
    }
    echo "<label class=\"form-check-label\">";
    echo "<h4>Pol&iacute;tica: <b>". $nome . "</b></h4>";
    if ($quota_infinita == 1) {
      echo "<b>Quota Infinita</b><br>";
    } else {
      echo "Quota padr&atilde;o: <b>". $quota_padrao ."</b><br>";
    }
    echo "\n</label>";
    echo "</div>\n";
}
echo "</div>";
echo "</div>";
echo "</fieldset>";

?>

<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Aten&ccedil;&atilde;o!</h4>
  <p>Este procedimento ir&aacute; iniciar a quota de todos os usu&aacute;rios da pol&iacute;tica de impress&atilde;o selecionada com o valor <b>Quota Padr&atilde;o</b>. Ap&oacute;s executado n&atilde;o ser&aacute; poss&iacute;vel reverter.</p>
</div>

    </div>
    </div>


            <center>

            
             <button type="submit" class="btn btn-primary">Iniciar quota dos usu&aacute;rios</button>
            </center>
            <br><br>
          </form>
      </div>
   </div>
   </td></tr>
   </table>
   </center>

   <?php 
   include '../includes/footer.php';
   exit(); 
}

$cod_politica = trim($_POST['cod_politica']);

if (strlen($cod_politica)<1) {
   echo "<div class=\"alert alert-danger\" role=\"alert\">Pol&iacute;tica n&atilde;o selecionada.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"init_quota_politica.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
   include '../includes/footer.php';
   exit(); 
}


// Deleta Quota de Usuario
$deleta_stmt = $mysqli->prepare("DELETE FROM quota_usuario
  WHERE cod_politica = ?");
$deleta_stmt->bind_param('i', $cod_politica);
$deleta_stmt->execute();
$deleta_stmt->close();

$stmt = $mysqli->prepare("SELECT nome, quota_padrao 
        FROM politicas
        WHERE cod_politica = ?");
$stmt->bind_param('i', $cod_politica);
$stmt->execute(); 
$stmt->store_result();
$stmt->bind_result($nome, $quota_padrao);
$stmt->fetch();




echo "<center><div class=\"card mb-4 shadow-sm\" style=\"width: 30rem;\">";
echo " <div class=\"card-header bg-success\">";
echo " <h4 class=\"my-0 font-weight-normal\">Quota iniciada com Sucesso</h4>
</div>";
echo "<div class=\"card-body\">\n";
echo " <ul class=\"list-group list-group-flush\">";
echo "  <li class=\"list-group-item\"><h4>Pol&iacute;tica: <b>". $nome . "</b></h4></li>\n";
echo "  <li class=\"list-group-item\"><b>Quota Padr&atilde;o: $quota_padrao </b></li>\n";
echo " </ul>\n";
echo "</div></div>";


echo "<a class=\"btn btn-primary\" href=\"init_quota_politica.php\" role=\"button\" aria-expanded=\"false\">Inicializar quota de outra pol&iacute;tica</a>";
echo "</center><br>";
include '../includes/footer.php';
 ?>
