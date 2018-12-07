<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 15/11/2018 - Valcir C.
 *
 * Add Politica de Impressao 
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


// Add Politica?
if (isset($_POST['nome']) == true ) {
   $nome = trim($_POST['nome']);
   $quota_padrao = (int) trim($_POST['quota_padrao']);
   $quota_infinita = (int) trim($_POST['quota_infinita']);
   $quota_acumulativa = (int) trim($_POST['quota_acumulativa']);

   if ($quota_infinita != 1) {
      $quota_infinita = 0;
   } else {
      $quota_padrao = 0;
   }
   if ($quota_acumulativa != 1) {
      $quota_acumulativa = 0;
   }

   //Nome em branco
   if (strlen($nome) < 1) {
     include '../includes/header.php';
     echo "<div class=\"alert alert-danger\" role=\"alert\">Nome de Pol&iacute;tica inv&aacute;lido.</div><br><br><center>";
     echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
     
     include '../includes/footer.php';
     exit();    
   }


   // Politica Existe?
   $select_stmt = $mysqli->prepare("SELECT cod_politica FROM politicas 
       WHERE nome = ?");
   $select_stmt->bind_param('s', $nome);
   $select_stmt->execute();
   $select_stmt->store_result();
   if ($select_stmt->num_rows > 0) {
     include '../includes/header.php';
     echo "<div class=\"alert alert-danger\" role=\"alert\">Pol&iacute;tica <em>". $nome ."</em> j&aacute; existe na Base de Dados.</div><br><br><center>";
     echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
     
     include '../includes/footer.php';
     exit();
   }  
   $select_stmt->close();

   if ($insert_stmt = $mysqli->prepare("INSERT INTO politicas (cod_politica, nome,
       quota_acumulativa, quota_infinita, quota_padrao, prioridade)
       VALUES (0,?,?,?,?,0)")) {
       $insert_stmt->bind_param('siii', $nome,$quota_acumulativa,$quota_infinita,$quota_padrao);
       if (! $insert_stmt->execute()) {
        header('Location: error.php?err=Registration failure: INSERT Politica');
       }
       include '../includes/header.php';

       echo "<div class=\"alert alert-success\" role=\"alert\">Pol&iacute;tica <em>". $nome ."</em> Cadastrada com Sucesso.</div><br><br><center>";
       echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a>&nbsp;&nbsp;";
       echo "<a class=\"btn btn-primary\" href=\"politica_add.php\" role=\"button\" aria-expanded=\"false\">Nova Pol&iacute;tica</a>";
       echo "</center><br>";
       include '../includes/footer.php';
       exit();
   }
}




include '../includes/header.php';

?>


<center>
  <h2><font color=#428bca>Pol&iacute;tica de Impress&atilde;o</font></h2>
<br>

<table width="800" border="0">

<tr><td align="center">

<div class="card">
  <div class="card-body">

<form  action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">

     <div class="form-group row">
          <label class="form-group col-form-label-sm">Nome da Pol&iacute;tica:&nbsp;&nbsp;</label>
          <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome">
     </div>

     <div class="form-group row">
          <label class="form-group col-form-label-sm">Quota Padr&atilde;o:&nbsp;&nbsp;</label>
          <input type="text" class="form-control form-control-sm" name="quota_padrao" value=0>
     </div>

     <div class="form-group row">
          <label class="form-group col-form-label-sm">Quota Infinita:&nbsp;&nbsp;</label>
          <select class="form-control form-control-sm" name="quota_infinita">
              <option value="0" selected>N&atilde;o</option>
              <option value="1">Sim</option>
          </select>
     </div>

     <div class="form-group row">
          <label class="form-group col-form-label-sm">Quota Acumulativa:&nbsp;&nbsp;</label>
          <select class="form-control form-control-sm" name="quota_acumulativa">
              <option value="0" selected>N&atilde;o</option>
              <option value="1">Sim</option>
          </select>
     </div>


   <button type="submit" class="btn btn-primary">&nbsp;Cadastrar Pol&iacute;tica&nbsp;</button>

</form>

</div></div>
</td></tr>

</table>
</center>


<?php include '../includes/footer.php'; ?>
