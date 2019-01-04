<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 08/11/2018 - Valcir C.
 *
 * Editar Grupos do Usuario
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

// Apagar relacao grupo_usuario
if ( isset($_GET['cod_usuario']) && isset($_GET['cod_grupo']) ) {
    $cod_grupo = $_GET['cod_grupo'];
    $cod_usuario = $_GET['cod_usuario'];
    // Deleta Grupo-Usuarios 
    $deleta_stmt = $mysqli->prepare("DELETE FROM grupo_usuario
      WHERE cod_usuario = ? AND cod_grupo = ?");
    $deleta_stmt->bind_param('ii', $cod_usuario, $cod_grupo);
    $deleta_stmt->execute();
    $deleta_stmt->close();
    
    header("Location: usuario_grupo.php?cod_usuario=" . $cod_usuario);
    exit();
}


//Insere cabecalho
include '../includes/header.php';

if (isset($_GET['cod_usuario']) && !isset($_GET['cod_grupo'])) { 
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

<center><h2><font color=#428bca>Usu&aacute;rio: <?php echo "$usuario"; ?></font></h2><br><br>
   <table border="0" width="600" align="center">
    <tr><td>
    <div class="panel panel-default">
      <div class="container-fluid">
       <ul class="list-group">


<?php
   $select_stmt = $mysqli->prepare("SELECT grupos.cod_grupo,grupos.grupo
      FROM grupo_usuario, grupos
      WHERE grupos.cod_grupo = grupo_usuario.cod_grupo AND grupo_usuario.cod_usuario = ?");
   $select_stmt->bind_param('i', $cod_usuario);
   $select_stmt->execute();
   $select_stmt->bind_result($cod_grupo, $grupo);
   $sem_grupo = 1;

   // Lista grupos deste usuario
   while ($select_stmt->fetch()) {
      echo "<li class=\"list-group-item\">". $grupo ."&nbsp;&nbsp;";
      echo "<a href=\"usuario_grupo.php?cod_usuario=". $cod_usuario . "&cod_grupo=". $cod_grupo ."\">";
      echo "<button type=\"button\" class=\"btn btn-danger btn-sm\" title=\"Tirar Grupo\">X";
      echo "</button></a>";
      echo "</li>\n";
      $sem_grupo = 0;
   } 
   if ($sem_grupo == 1) {
      echo "<li class=\"list-group-item\"><i>Sem Grupo</i></li>";
   }
     


?>
  </ul>
  <div class="card">
  <div class="card-body">   
        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">
         <input type="hidden" name="cod_usuario" value="<?php echo $cod_usuario; ?>">
         <div class="form-group row">
           <label class="form-group col-form-label-lg">Atribuir ao Grupo:&nbsp;&nbsp;</label>
              <select class="form-control" name="cod_grupo">

<?php

       $select_stmt = $mysqli->prepare("SELECT cod_grupo,grupo
          FROM grupos");
       $select_stmt->execute();
       $select_stmt->bind_result($cod_grupo, $grupo);

       // Lista grupos nao pertencentes a este usuario
       while ($select_stmt->fetch()) {
          echo "<option value=\"". $cod_grupo ."\">". $grupo ."</option>\n";
        }

?>
              </select>
          </div>
           <button type="submit" class="btn btn-primary">Atribuir Grupo ao Usu&aacute;rio</button>&nbsp;&nbsp;
           <a class="btn btn-primary" href="index.php" role="button" aria-expanded="false">Voltar</a>

        </form>
        </div>
       </div>    
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
if ((isset($_POST['cod_usuario'])) && (isset($_POST['cod_grupo']))) {

  $cod_usuario = trim($_POST['cod_usuario']);
  $cod_grupo = trim($_POST['cod_grupo']);
  

   // Já existe?
   $select_stmt = $mysqli->prepare("SELECT * FROM grupo_usuario  
      WHERE cod_usuario = ? and cod_grupo = ? LIMIT 1");
   $select_stmt->bind_param('ii', $cod_usuario, $cod_grupo);
   if (! $select_stmt->execute()) {
      header('Location: error.php?err=Registration failure: SELECT GRUPO_USUARIO.');
      exit();
   }
   $select_stmt->store_result();
   if ($select_stmt->num_rows > 0) {
     echo "<div class=\"alert alert-danger\" role=\"alert\">Grupo j&aacute; estava atribuido!</div><br>";
     echo "<a class=\"btn btn-primary\" href=\"usuario_grupo.php?cod_usuario=". $cod_usuario ."\" role=\"button\" aria-expanded=\"false\">Voltar</a>";
     include '../includes/footer.php';
     exit();
   }





  if ($insert_stmt = $mysqli->prepare("INSERT INTO grupo_usuario (cod_usuario, cod_grupo)
     VALUES (?, ?)")) {
    $insert_stmt->bind_param('ii', $cod_usuario,$cod_grupo);
    // Executar a tarefa pré-estabelecida.
    if (! $insert_stmt->execute()) {
      header('Location: error.php?err=Registration failure: INSERT Usuario-Grupo');
    }
  }  

  echo "<div class=\"alert alert-success\" role=\"alert\">Grupo atribuido com Sucesso!</div><br>";
  echo "<a class=\"btn btn-primary\" href=\"usuario_grupo.php?cod_usuario=". $cod_usuario ."\" role=\"button\" aria-expanded=\"false\">Voltar</a>";


}
include '../includes/footer.php';


?>
