<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 


sec_session_start();

if (login_check($mysqli) == false) {
  header("Location: login.php");
  exit();
}

include 'includes/header.php';



// Sinalizacao de Campo com problema
$campo_senha="";
$span_senha="";

if (isset($_POST['senha'])) {
        
    $senha = trim($_POST['senha']);
    if (strlen($senha)<6) {
      $campo_senha="has-error";
      $span_senha="<span id=\"helpBlock4\" class=\"help-block\">O tamanho da senha deve ser de no m&iacute;nimo 6.</span>";
    }


  // Houve erro?
  if (strlen($campo_senha) == 0){
  
      $password = hash('sha256', $senha);
 
      // Altera senha do usuario no banco de dados 
      if ($update_stmt = $mysqli->prepare("UPDATE adm_users SET senha=? WHERE cod_cod_adm_users=?")) {
            $update_stmt->bind_param('si', $password, $_SESSION['user_id']);
            // Executar a tarefa pré-estabelecida.
            if (! $update_stmt->execute()) {
		       header('Location: error.php?err=Na troca da Senha: UPDATE');
            }
        }
      echo "<br><br><center><h2>Senha trocada com Sucesso!!!</h2>";
      echo "<br><a href=login.php>Sua sess&atilde;o expirou, entre novamente.</a></center><br><br>\n";
      include 'includes/footer.php';
      include(FOOTER_TEMPLATE);
      exit();
  }

} else {
 // Inicia Variável do formulario
  $senha=""; 
}

?>


<center>
  <h2><font color=#428bca>Trocar senha do Gerenciador Gráfico</font></h2>
<br><br>
<table border="0" width="500" align="center">
<tr><td>
<div class="panel panel-default">
    <div class="container-fluid">
    <center><h1><?php echo htmlentities($_SESSION['username']); ?></h1></center>
        <?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>

        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">          
          <div class="form-group <?php echo "$campo_senha"; ?> ">
            <label for="exampleInputPassword">Nova Senha</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Senha" name="senha" value="<?php echo $senha; ?>">
            <?php echo $span_senha; ?>
          </div>
          <center>
           <button type="submit" class="btn btn-primary">&nbsp;Trocar Senha&nbsp;</button>
          </center>
          <br><br>
        </form>
    </div>
</div>
</td></tr>
</table>
</center>

<?php  include 'includes/footer.php'; ?>
