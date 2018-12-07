<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 06/11/2018 - Valcir C.
 *
 * Adiciona Novo Usuario Administrativo
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
if (isset($_POST['login']) == false ) {
  
?>


  <center><br>
    <h2><font color=#428bca>Cadastro de Usu&aacute;rio Administrativo</font></h2>
  <br><br>
  <table border="0" width="500" align="center">
  <tr><td>
  <div class="panel panel-default">
      <div class="container-fluid">
        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">          

           <div class="form-group row">
                <label class="form-group col-form-label-sm">Login:&nbsp;&nbsp;</label>
                <input type="text" class="form-control form-control-sm" name="login" placeholder="Login">
           </div>

           <div class="form-group row">
                <label class="form-group col-form-label-sm">Senha:&nbsp;&nbsp;</label>
                <input type="password" class="form-control form-control-sm" name="senha" placeholder="Password">
           </div>

           <div class="form-group row">
                <label class="form-group col-form-label-sm">Nome:&nbsp;&nbsp;</label>
                <input type="text" class="form-control form-control-sm" name="nome" placeholder="Name">
           </div>

           <div class="form-group row">
                <label class="form-group col-form-label-sm">E-mail:&nbsp;&nbsp;</label>
                <input type="text" class="form-control form-control-sm" name="email" placeholder="E-mail">
           </div>

           <div class="form-group row">
                <label class="form-group col-form-label-sm">Nível de Permissão:&nbsp;&nbsp;</label>
                <select class="form-control form-control-sm" name="permissao">
                    <option value="2">Administrador Geral do IBQUOTA</option>
                    <option value="1" selected>Administrador de Impress&atilde;o</option>
                    <option value="0">Visualiza Relat&oacute;rio</option>
                </select>
           </div>

           <button type="submit" class="btn btn-primary">&nbsp;Cadastrar Usu&aacute;rio Administrativo&nbsp;</button>

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


$span_grupo="";
$login = trim($_POST['login']);

if (strlen($login)<1) {
   echo "<div class=\"alert alert-danger\" role=\"alert\">Campo Login n&atilde;o preenchido.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"adm_users_add.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
   include '../includes/footer.php';
   exit(); 
}

$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = hash('sha256',trim($_POST['senha']), FALSE);
$permissao = trim($_POST['permissao']);


// Login Existe?
$select_stmt = $mysqli->prepare("SELECT cod_adm_users FROM adm_users 
     WHERE login = ?");
$select_stmt->bind_param('s', $login);
$select_stmt->execute();
$select_stmt->store_result();
if ($select_stmt->num_rows > 0) {
    //Login ja existe
   echo "<div class=\"alert alert-danger\" role=\"alert\">O login <em>". $login ."</em> j&aacute; existe na Base de Dados.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
   
   include '../includes/footer.php';
   exit();
}  
$select_stmt->close();

if ($insert_stmt = $mysqli->prepare("INSERT INTO adm_users (cod_adm_users,
         login,nome,email,senha,permissao)
     VALUES (0,?,?,?,?,?)")) {
    $insert_stmt->bind_param('ssssi', $login,$nome,$email,$senha,$permissao);
    // Executar a tarefa pré-estabelecida.
    if (! $insert_stmt->execute()) {
      header('Location: error.php?err=Registration failure: INSERT adm_users');
    }
    echo "<div class=\"alert alert-success\" role=\"alert\">Usu&aacute;rio Administrador <em>". $login ."</em> Cadastrado com Sucesso.</div><br><br><center>";
    echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a>&nbsp;&nbsp;";
    echo "<a class=\"btn btn-primary\" href=\"adm_users_add.php\" role=\"button\" aria-expanded=\"false\">Novo Usu&aacute;rio Administrador</a>";
    echo "</center><br>";
    include '../includes/footer.php';
    exit();
}



?>

<?php include '../includes/footer.php'; ?>
