<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 24/11/2018 - Valcir C.
 *
 * Quota Adicional para Usuario
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

include '../includes/header.php';


// Não teve variavel enviada pelo Formulario
if (isset($_POST['cod_politica']) == false ) {
  
?>


  <center>
    <h2><font color=#428bca>Cadastro de quota adicional para usu&aacute;rio</font></h2>
  <br>
  <table border="0" width="800" align="center">
  <tr><td>
  <div class="panel panel-default">
      <div class="container-fluid">
          <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">          

            <div class="form-group">
              <label>Login do Usu&aacute;rio:</label>
              <input type="text" class="form-control" placeholder="Login do Usu&aacute;rio" name="usuario">
            </div>

            <div class="form-group">
              <label>Quota adicional:</label>
              <input type="text" class="form-control" placeholder="Quota adicional" name="quota_adicional">
            </div>

            <div class="form-group">
              <label>Motivo:</label>
              <input type="text" class="form-control" placeholder="Motivo" name="motivo">
            </div>
       
              <div class="card mb-4 shadow-sm" >
    <div class="card-header bg-success">
      <h4 class="my-0 font-weight-normal">Pol&iacute;tica de impress&atilde;o</h4>
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
    </div>
    </div>


            <center>

            
             <button type="submit" class="btn btn-primary">Cadastrar quota adicional</button>
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
$usuario = trim($_POST['usuario']);

if (strlen($usuario)<1) {
   echo "<div class=\"alert alert-danger\" role=\"alert\">Campo login n&atilde;o preenchido.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"usuario_quota_add.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
   include '../includes/footer.php';
   exit(); 
}

$cod_politica = trim($_POST['cod_politica']);
if (strlen($cod_politica)<1) {
   echo "<div class=\"alert alert-danger\" role=\"alert\">Pol&iacute;tica n&atilde;o selecionada.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"usuario_quota_add.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
   include '../includes/footer.php';
   exit(); 
}

$quota_adicional = trim($_POST['quota_adicional']);
if (strlen($quota_adicional)<1) {
   echo "<div class=\"alert alert-danger\" role=\"alert\">Quota adicional n&atilde;o preenchida.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"usuario_quota_add.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
   include '../includes/footer.php';
   exit(); 
}



// Usuario Politica?
$select_stmt = $mysqli->prepare("SELECT cod_politica FROM politicas 
     WHERE cod_politica = ? and quota_infinita != 1");
$select_stmt->bind_param('i', $cod_politica);
// Executar a tarefa pré-estabelecida.
$select_stmt->execute();
$select_stmt->store_result();
if ($select_stmt->num_rows < 1) {
   echo "<div class=\"alert alert-danger\" role=\"alert\">Pol&iacute;tica n&atilde;o existe na Base de Dados.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"usuario_quota_add.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
   
   include '../includes/footer.php';
   exit();
}  
$select_stmt->close();


$motivo = trim($_POST['motivo']);
$useradmin = $_SESSION['username'];
$quota_antiga = quota_usuario($cod_politica,$usuario);

$quota_atual = $quota_antiga + $quota_adicional;

$grupo = grupo_usuario_politica($cod_politica, $usuario);
if (strlen($grupo)<1) {
   echo "<div class=\"alert alert-danger\" role=\"alert\">N&atilde;o foi possível identificar o grupo deste usu&iacute;rio.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"usuario_quota_add.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";   
   include '../includes/footer.php';
   exit();
}  

// INSERE Histório
$insert_stmt = $mysqli->prepare("INSERT INTO quota_adicional (cod_politica, usuario, 
                        quota_adicional, motivo, datahora,useradmin )
                        VALUES (?,?,?,?,NOW(),?)");
$insert_stmt->bind_param('isiss', $cod_politica, $usuario,$quota_adicional,$motivo, $useradmin);
$insert_stmt->execute();

// Já tem registro de quota para este usuario?
$select_stmt = $mysqli->prepare("SELECT cod_quota_usuario FROM quota_usuario 
     WHERE cod_politica = ? and usuario = ? and grupo = ?");
$select_stmt->bind_param('iss', $cod_politica,$usuario, $grupo);
// Executar a tarefa pré-estabelecida.
$select_stmt->execute();
$select_stmt->store_result();
$select_stmt->bind_result($cod_quota_usuario);
if ($select_stmt->num_rows < 1) {
    $insert_stmt = $mysqli->prepare("INSERT INTO quota_usuario (cod_politica, grupo, 
                            usuario, quota )
                            VALUES (?,?,?,?)");
    $insert_stmt->bind_param('issi', $cod_politica, $grupo, $usuario,$quota_atual);
    $insert_stmt->execute();
    $insert_stmt->close();
} else {
  $select_stmt->fetch();
  $update_stmt = $mysqli->prepare("UPDATE quota_usuario 
                        SET quota = quota + ?
                        WHERE cod_quota_usuario = ?");
  $update_stmt->bind_param('ii', $quota_adicional, $cod_quota_usuario);
  $update_stmt->execute();
  $update_stmt->close();
}




    echo "<center><div class=\"card mb-4 shadow-sm\" style=\"width: 30rem;\">";
    echo " <div class=\"card-header bg-success\">";
    echo " <h4 class=\"my-0 font-weight-normal\">Quota adicional inserida com Sucesso</h4>
    </div>";
    echo "<div class=\"card-body\">\n";
    echo " <ul class=\"list-group list-group-flush\">";
    echo "  <li class=\"list-group-item\">  </li>\n";
    echo "  <li class=\"list-group-item\"><b>Usu&aacute;rio:</b> $usuario </li>\n";
    echo "  <li class=\"list-group-item\"><b>Quota Adicionada:</b> $quota_adicional </li>\n";
    echo "  <li class=\"list-group-item\"><b>Quota Antiga: $quota_antiga </b></li>\n";
    echo "  <li class=\"list-group-item\"><b>Quota Atual: $quota_atual </b></li>\n";
    echo "  <li class=\"list-group-item\"><b>Motivo:</b> $motivo </li>\n";
    echo "  <li class=\"list-group-item\"><i>Respons&aacute;vel: $useradmin </i></li>\n";
    echo " </ul>\n";
    echo "</div></div>";


    echo "<a class=\"btn btn-primary\" href=\"usuario_quota_add.php\" role=\"button\" aria-expanded=\"false\">Novo cadastro de quota adicional</a>";
    echo "</center><br>";
    include '../includes/footer.php';
 ?>
