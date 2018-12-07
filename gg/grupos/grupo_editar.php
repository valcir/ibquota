<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 06/11/2018 - Valcir C.
 *
 * Editar Grupo
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
if ( !isset($_GET['cod_grupo']) && !isset($_POST['cod_grupo']) ) {
       header("Location: index.php");
       exit();
}

//Insere cabecalho
include '../includes/header.php';

if (isset($_GET['cod_grupo'])) { 
   $cod_grupo = trim($_GET['cod_grupo']);
   // Grupo existe?
   $select_stmt = $mysqli->prepare("SELECT grupo
      FROM grupos
      WHERE cod_grupo = ? LIMIT 1");
   $select_stmt->bind_param('i', $cod_grupo);
   if (! $select_stmt->execute()) {
      header('Location: error.php?err=Registration failure: SELECT GRUPO.');
      exit();
   }

   $select_stmt->store_result();
   if ($select_stmt->num_rows < 1) {
      echo "<div class=\"alert alert-danger\" role=\"alert\">Grupo inesistente!</div>";
      echo "<center><a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
      include '../includes/footer.php';
      exit();
   }
   
   $select_stmt->bind_result($grupo);
   $select_stmt->fetch();
   

?>

<center><h2><font color=#428bca>Altera&ccedil;&atilde;o de Grupo</font></h2><br><br>
   <table border="0" width="600" align="center">
    <tr><td>
    <div class="panel panel-default">
      <div class="container-fluid">
      <blockquote><h4>Grupo: <?php echo "$grupo"; ?> </h4></blockquote>
      
        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">
         <input type="hidden" name="cod_grupo" value="<?php echo $cod_grupo; ?>">

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Novo nome:&nbsp;&nbsp;</label>
              <input type="text" class="form-control form-control-sm" name="grupo" placeholder="Group" value="<?php echo $grupo;?>">
         </div>

           <button type="submit" class="btn btn-primary">Alterar Grupo</button>&nbsp;&nbsp;
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
if ((isset($_POST['cod_grupo'])) AND (isset($_POST['grupo']))) {

  $grupo = trim($_POST['grupo']);
  $cod_grupo = trim($_POST['cod_grupo']);
  $grupo_antigo = "";
 
  // Nome antigo do Grupo
  $select_stmt = $mysqli->prepare("SELECT grupo
      FROM grupos
      WHERE cod_grupo = ? LIMIT 1");
  $select_stmt->bind_param('i', $cod_grupo);
  if (! $select_stmt->execute()) {
      header('Location: error.php?err=Registration failure: SELECT GRUPO.');
      exit();
  }
  $select_stmt->store_result();
  $select_stmt->bind_result($grupo_antigo);
  $select_stmt->fetch();

 // Novo grupo ja Existe?
 $select_stmt = $mysqli->prepare("SELECT grupo
    FROM grupos
    WHERE cod_grupo != ? AND grupo = ? LIMIT 1");
 $select_stmt->bind_param('is', $cod_grupo, $grupo);
 // Executar a tarefa pré-estabelecida.
 if (! $select_stmt->execute()) {
    header('Location: error.php?err=Registration failure: SELECT GRUPO.');
    exit();
 }
 $select_stmt->store_result();
 if ($select_stmt->num_rows > 0) {
    echo "<div class=\"alert alert-danger\" role=\"alert\">Grupo com mesmo nome j&aacute; existente cadastrado!</div>";
    echo "<a class=\"btn btn-primary\" href=grupo_editar.php?cod_grupo=". $cod_grupo ." role=\"button\" aria-expanded=\"false\">Voltar</a>";
    include '../includes/footer.php';
    exit();
 }
 $select_stmt->close();

  // grava novo nome grupo
  $update_stmt = $mysqli->prepare("UPDATE grupos
      SET grupo = ?
     WHERE cod_grupo = ?");
  $update_stmt->bind_param('si',$grupo, $cod_grupo);
  // Executar a tarefa pré-estabelecida.
  $update_stmt->execute();
  $update_stmt->close();

  // grava novo nome grupo na politica criada
  $update_stmt = $mysqli->prepare("UPDATE politica_grupo
      SET grupo = ?
     WHERE grupo = ?");
  $update_stmt->bind_param('ss',$grupo, $grupo_antigo);
  // Executar a tarefa pré-estabelecida.
  $update_stmt->execute();
  $update_stmt->close();
  echo "<div class=\"alert alert-success\" role=\"alert\">Grupo Alterado com Sucesso!</div><br>";
  echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a>";


}
include '../includes/footer.php';


?>
