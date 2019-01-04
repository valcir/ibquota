<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 08/11/2018 - Valcir C.
 *
 * Adiciona Novo Usuario
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
if (isset($_POST['usuario']) == false ) {
  
?>


  <center><br>
    <h2><font color=#428bca>Cadastro de Novo Usu&aacute;rio</font></h2>
  <br><br>
  <table border="0" width="500" align="center">
  <tr><td>
  <div class="panel panel-default">
      <div class="container-fluid">
          <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">          

            <div class="form-group">
              <label for="exampleInputPassword">Nome do Usu&aacute;rio:</label>
              <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Nome do Usu&aacute;rio" name="usuario">
            </div>

            <center>

            <a class="btn btn-primary" href="index.php" role="button" aria-expanded="false">Voltar</a>&nbsp;&nbsp;
             <button type="submit" class="btn btn-primary">Cadastrar Usu&aacute;rio</button>
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


$span_grupo="";
$span_grupo1="";
$usuario = trim($_POST['usuario']);

if (strlen($usuario)<1) {
   echo "<div class=\"alert alert-danger\" role=\"alert\">Campo usuario n&atilde;o preenchido.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"usuario_add.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
   include '../includes/footer.php';
   exit(); 
}






// Usuario Existe?
$select_stmt = $mysqli->prepare("SELECT cod_usuario FROM usuarios 
     WHERE usuario = ?");
$select_stmt->bind_param('s', $usuario);
// Executar a tarefa pré-estabelecida.
$select_stmt->execute();
$select_stmt->store_result();
if ($select_stmt->num_rows > 0) {
    //grupo ja existe
   echo "<div class=\"alert alert-danger\" role=\"alert\">O usu&aacute;rio <em>". $usuario ."</em> j&aacute; existe na Base de Dados.</div><br><br><center>";
   echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
   
   include '../includes/footer.php';
   exit();
}  
$select_stmt->close();

if ($insert_stmt = $mysqli->prepare("INSERT INTO usuarios (cod_usuario, usuario)
     VALUES (0, ?)")) {
    $insert_stmt->bind_param('s', $usuario);
    // Executar a tarefa pré-estabelecida.
    if (! $insert_stmt->execute()) {
      header('Location: error.php?err=Registration failure: INSERT Usuarios');
    }
    echo "<div class=\"alert alert-success\" role=\"alert\">Usuario <em>". $usuario ."</em> Cadastrado com Sucesso.</div><br><br><center>";
    echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a>&nbsp;&nbsp;";
    echo "<a class=\"btn btn-primary\" href=\"usuario_add.php\" role=\"button\" aria-expanded=\"false\">Novo Usu&aacute;rio</a>";
    echo "</center><br>";
    include '../includes/footer.php';
    exit();
}



?>

<?php include '../includes/footer.php'; ?>
