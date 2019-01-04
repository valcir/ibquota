<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 07/11/2018 - Valcir C.
 *
 * Editar Usuario Administrativo
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

// Não teve variavel enviada pelo Formulario
if ( !isset($_GET['cod_adm_users']) && !isset($_POST['cod_adm_users']) ) {
       header("Location: index.php");
       exit();
}

//Insere cabecalho
include '../includes/header.php';

if (isset($_GET['cod_adm_users'])) { 
   $cod_adm_users = trim($_GET['cod_adm_users']);
   // Usuario Adm existe?
   $select_stmt = $mysqli->prepare("SELECT nome,login,email,permissao
      FROM adm_users
      WHERE cod_adm_users = ? LIMIT 1");
   $select_stmt->bind_param('i', $cod_adm_users);
   if (! $select_stmt->execute()) {
      header('Location: error.php?err=Registration failure: SELECT ADM USERS.');
      exit();
   }

   $select_stmt->store_result();
   if ($select_stmt->num_rows < 1) {
      echo "<div class=\"alert alert-danger\" role=\"alert\">Usu&aacute;rio inexistente!</div>";
      echo "<center><a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
      include '../includes/footer.php';
      exit();
   }
   
   $select_stmt->bind_result($nome,$login,$email,$permissao);
   $select_stmt->fetch();
   

?>

<center><h2><font color=#428bca>Altera&ccedil;&atilde;o de Usu&aacute;rio Administrativo</font></h2><br><br>
   <table border="0" width="600" align="center">
    <tr><td>
    <div class="panel panel-default">
      <div class="container-fluid">

        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">
         <input type="hidden" name="cod_adm_users" value="<?php echo $cod_adm_users; ?>">

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Login:&nbsp;&nbsp;</label>
              <input type="text" class="form-control form-control-sm" name="login" placeholder="Login" value="<?php echo $login;?>">
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Senha:&nbsp;&nbsp;</label>
              <input type="password" class="form-control form-control-sm" name="senha" placeholder="Password" value="">
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Nome:&nbsp;&nbsp;</label>
              <input type="text" class="form-control form-control-sm" name="nome" placeholder="Name" value="<?php echo $nome;?>">
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-sm">E-mail:&nbsp;&nbsp;</label>
              <input type="text" class="form-control form-control-sm" name="email" placeholder="E-mail" value="<?php echo $email;?>">
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-sm">N&iacute;vel de Permiss&atilde;o:&nbsp;&nbsp;</label>
              <select class="form-control form-control-sm" name="permissao">
                  <option value="0" <?php if ($permissao == 0) echo "selected";?> >Administrador Geral do IBQUOTA</option>
                  <option value="1"  <?php if ($permissao == 1) echo "selected";?> >Administrador de Impress&atilde;o</option>
                  <option value="2"  <?php if ($permissao == 2) echo "selected";?> >Visualiza Relat&oacute;rio</option>
              </select>
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
if ((isset($_POST['cod_adm_users'])) AND (isset($_POST['login']))) {

  $login = trim($_POST['login']);
  $cod_adm_users = trim($_POST['cod_adm_users']);
  $nome = trim($_POST['nome']);
  $email = trim($_POST['email']);
  $permissao = trim($_POST['permissao']);
  $senha = hash('sha256',trim($_POST['senha']), FALSE);
 
  // Nome antigo do Grupo
  $select_stmt = $mysqli->prepare("SELECT login
      FROM adm_users
      WHERE login = ? AND cod_adm_users != ? LIMIT 1");
  $select_stmt->bind_param('si', $login, $cod_adm_users);
  if (! $select_stmt->execute()) {
      header('Location: error.php?err=Registration failure: SELECT ADM USERS.');
      exit();
  }
  $select_stmt->store_result();
  
  if ($select_stmt->num_rows > 0) {
     echo "<div class=\"alert alert-danger\" role=\"alert\">Administrador com mesmo login j&aacute; existente cadastrado!</div>";
     echo "<a class=\"btn btn-primary\" href=adm_users_editar.php?cod_adm_users=". $cod_adm_users ." role=\"button\" aria-expanded=\"false\">Voltar</a>";
     include '../includes/footer.php';
     exit();
  }
  $select_stmt->close();
 
  // grava novo nome grupo
  $update_stmt = $mysqli->prepare("UPDATE adm_users
      SET login = ?, nome = ?, email = ?, senha = ?, permissao = ?
     WHERE cod_adm_users = ?");
  $update_stmt->bind_param('ssssii',$login,$nome,$email,$senha,$permissao,$cod_adm_users);
  // Executar a tarefa pré-estabelecida.
  $update_stmt->execute();
  $update_stmt->close();

  
  echo "<div class=\"alert alert-success\" role=\"alert\">Usu&aacute;rio Administrador Alterado com Sucesso!</div><br>";
  echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a>";

}
include '../includes/footer.php';


?>
