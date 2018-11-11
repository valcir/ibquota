<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 11/11/2018 - Valcir C.
 *
 * Pagina de primeiro acesso para definir senha do usuario admin
 */  

include_once 'includes/db.php';
include_once 'includes/functions.php';
 
if (primeiro_acesso($mysqli) == false) {
    header("Location: index.php");
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
      echo "<div class=\"alert alert-danger\" role=\"alert\">O tamanho da senha deve ser de no m&iacute;nimo 6.</div>";
    }


  // Houve erro?
  if (strlen($campo_senha) == 0){
  
      $password = hash('sha256', $senha);
 
      // Altera senha do usuario no banco de dados 
      if ($update_stmt = $mysqli->prepare("UPDATE adm_users SET senha = ? WHERE login = 'admin'")) {
            $update_stmt->bind_param('s', $password);
            // Executar a tarefa pré-estabelecida.
            if (! $update_stmt->execute()) {
		           header('Location: error.php?err=Na definicao da Senha: UPDATE');
            }
      }
      echo "<br><br><div class=\"alert alert-success\" role=\"alert\">Senha definida com Sucesso!!! Utilize o login <b>admin</b>.</div>";
      echo "<a href=\"login.php\" class=\"btn btn-primary btn-lg active\" role=\"button\" aria-pressed=\"true\">Login de acesso.</a><br><br>";
      include 'includes/footer.php';
      exit();
  }

}

?>

<br><br>
<center>
  <div class="card mb-4 shadow-sm" style="width: 28rem;">
            <div class="card-header bg-success">
      <h4 class="my-0 font-weight-normal">Primeiro acesso: admin</h4>
    </div>
    <div class="card-body">
      <h5 class="card-title pricing-card-title">Definir senha de acesso ao Gerenciador Gráfico</h5>

     <form action="primeiro_acesso.php" method="post">

      <div class="form-group">
         Nova Senha:
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Senha" name="senha" value="">
          <?php echo $span_senha; ?>
      </div>

     <button type="submit" class="btn btn-primary">&nbsp;Definir Senha&nbsp;</button>

    </form>
    </div>
  </div>
</center>


<?php  include 'includes/footer.php'; ?>
