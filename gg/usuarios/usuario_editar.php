<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 08/11/2018 - Valcir C.
 *
 * Editar Usuario
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

// Não teve variavel enviada pelo Formulario
if ( !isset($_GET['cod_usuario']) && !isset($_POST['cod_usuario']) ) {
       header("Location: index.php");
       exit();
}

//Insere cabecalho
include '../includes/header.php';

if (isset($_GET['cod_usuario'])) { 
   $cod_usuario = trim($_GET['cod_usuario']);
   // Usuario existe?
   $select_stmt = $mysqli->prepare("SELECT usuario
      FROM usuarios
      WHERE cod_usuario = ? LIMIT 1");
   $select_stmt->bind_param('i', $cod_usuario);
   if (! $select_stmt->execute()) {
      header('Location: error.php?err=Registration failure: SELECT USUARIO.');
      exit();
   }

   $select_stmt->store_result();
   if ($select_stmt->num_rows < 1) {
      echo "<div class=\"alert alert-danger\" role=\"alert\">Usu&aacute;rio inexistente!</div>";
      echo "<center><a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
      include '../includes/footer.php';
      exit();
   }
   
   $select_stmt->bind_result($usuario);
   $select_stmt->fetch();
   

?>

<center><h2><font color=#428bca>Altera&ccedil;&atilde;o de Usu&aacute;rio</font></h2><br><br>
   <table border="0" width="600" align="center">
    <tr><td>
    <div class="panel panel-default">
      <div class="container-fluid">
      <blockquote><h4>Usu&aacute;rio: <?php echo "$usuario"; ?> </h4></blockquote>
      
        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">
         <input type="hidden" name="cod_usuario" value="<?php echo $cod_usuario; ?>">

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Novo nome:&nbsp;&nbsp;</label>
              <input type="text" class="form-control form-control-sm" name="usuario" placeholder="User" value="<?php echo $usuario;?>">
         </div>

           <button type="submit" class="btn btn-primary">Alterar Usu&aacute;rio</button>&nbsp;&nbsp;
           <a class="btn btn-primary" href="index.php" role="button" aria-expanded="false">Voltar</a></center>
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

//
// Entao GRAVA 
//
if ((isset($_POST['cod_usuario'])) AND (isset($_POST['usuario']))) {

  $usuario = trim($_POST['usuario']);
  $cod_usuario = trim($_POST['cod_usuario']);
  $usuario_antigo = "";
 
  // Nome antigo do Usuario
  $select_stmt = $mysqli->prepare("SELECT usuario
      FROM usuarios
      WHERE cod_usuario = ? LIMIT 1");
  $select_stmt->bind_param('i', $cod_usuario);
  if (! $select_stmt->execute()) {
      header('Location: error.php?err=Registration failure: SELECT USUARIO.');
      exit();
  }
  $select_stmt->store_result();
  $select_stmt->bind_result($usuario_antigo);
  $select_stmt->fetch();

 // Novo usuario ja Existe?
 $select_stmt = $mysqli->prepare("SELECT usuario
    FROM usuarios
    WHERE cod_usuario != ? AND usuario = ? LIMIT 1");
 $select_stmt->bind_param('is', $cod_usuario, $usuario);
 // Executar a tarefa pré-estabelecida.
 if (! $select_stmt->execute()) {
    header('Location: error.php?err=Registration failure: SELECT USUARIO.');
    exit();
 }
 $select_stmt->store_result();
 if ($select_stmt->num_rows > 0) {
    echo "<div class=\"alert alert-danger\" role=\"alert\">Usu&aacute;rio com mesmo nome j&aacute; existente cadastrado!</div>";
    echo "<a class=\"btn btn-primary\" href=usuario_editar.php?cod_usuario=". $cod_usuario ." role=\"button\" aria-expanded=\"false\">Voltar</a>";
    include '../includes/footer.php';
    exit();
 }
 $select_stmt->close();

  // grava novo nome usuario
  $update_stmt = $mysqli->prepare("UPDATE usuarios
      SET usuario = ?
     WHERE cod_usuario = ?");
  $update_stmt->bind_param('si',$usuario, $cod_usuario);
  $update_stmt->execute();
  $update_stmt->close();

  // grava novo nome usuario na politica criada
  $update_stmt = $mysqli->prepare("UPDATE quota_usuario
      SET usuario = ?
     WHERE usuario = ?");
  $update_stmt->bind_param('ss',$usuario, $usuario_antigo);
  // Executar a tarefa pré-estabelecida.
  $update_stmt->execute();
  $update_stmt->close();
  echo "<div class=\"alert alert-success\" role=\"alert\">Usu&aacute;rio Alterado com Sucesso!</div><br>";
  echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a>";


}
include '../includes/footer.php';


?>
