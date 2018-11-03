<?php
include_once 'includes/db.php';
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
      $span_senha="<div class=\"alert alert-danger\" role=\"alert\">O tamanho da senha deve ser de no m&iacute;nimo 6.</div>";
    }


  // Houve erro?
  if (strlen($campo_senha) == 0){
  
      $password = hash('sha256', $senha);
 
      // Altera senha do usuario no banco de dados 
      if ($update_stmt = $mysqli->prepare("UPDATE adm_users SET senha=? WHERE cod_adm_users=?")) {
            $update_stmt->bind_param('si', $password, $_SESSION['user_id']);
            // Executar a tarefa pré-estabelecida.
            if (! $update_stmt->execute()) {
		       header('Location: error.php?err=Na troca da Senha: UPDATE');
            }
      }
      session_destroy();
      echo "<br><br><div class=\"alert alert-success\" role=\"alert\">Senha trocada com Sucesso!!!</div>";
      echo "<a href=\"login.php\" class=\"btn btn-primary btn-lg active\" role=\"button\" aria-pressed=\"true\">Sua sess&atilde;o expirou, entre novamente.</a><br><br>";
      include 'includes/footer.php';
      exit();
  }

} else {
 // Inicia Variável do formulario
  $senha=""; 
}

?>

<br><br>
<center>
  <div class="card mb-4 shadow-sm" style="width: 28rem;">
            <div class="card-header bg-success">
      <h4 class="my-0 font-weight-normal"><?php echo htmlentities($_SESSION['username']); ?></h4>
    </div>
    <div class="card-body">
      <h5 class="card-title pricing-card-title">Trocar senha do Gerenciador Gráfico</h5>

     <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">

      <div class="form-group">
         Nova Senha:
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Senha" name="senha" value="<?php echo $senha; ?>">
          <?php echo $span_senha; ?>
      </div>

     <button type="submit" class="btn btn-primary">&nbsp;Trocar Senha&nbsp;</button>

    </form>
    </div>
  </div>
</center>


<?php  include 'includes/footer.php'; ?>
