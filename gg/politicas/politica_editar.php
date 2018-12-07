<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 15/11/2018 - Valcir C.
 *
 * Editar Politica de Impressao
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
if ( !isset($_GET['cod_politica']) && !isset($_POST['cod_politica']) ) {
       header("Location: index.php");
       exit();
}

//Insere cabecalho
include '../includes/header.php';

if (isset($_GET['cod_politica'])) { 
   $cod_politica = trim($_GET['cod_politica']);
   // Politica existe?
   $select_stmt = $mysqli->prepare("SELECT nome,quota_acumulativa,quota_infinita,quota_padrao, 
    prioridade
      FROM politicas
      WHERE cod_politica = ? LIMIT 1");
   $select_stmt->bind_param('i', $cod_politica);
   if (! $select_stmt->execute()) {
      header('Location: error.php?err=Registration failure: SELECT POLITICA.');
      exit();
   }

   $select_stmt->store_result();
   if ($select_stmt->num_rows < 1) {
      echo "<div class=\"alert alert-danger\" role=\"alert\">Pol&iacute;tica inesistente!</div>";
      echo "<center><a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a></center>";
      include '../includes/footer.php';
      exit();
   }
   
   $select_stmt->bind_result($nome,$quota_acumulativa,$quota_infinita,$quota_padrao,$prioridade);
   $select_stmt->fetch();
   

?>

<center><h2><font color=#428bca>Altera&ccedil;&atilde;o de Pol&iacute;tica</font></h2><br>
   <table border="0" width="600" align="center">
    <tr><td>

     <div class="card">
        <div class="card-header">
          Pol&iacute;tica: <?php echo "$nome"; ?> 
        </div>
        <div class="card-body">

        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">
         <input type="hidden" name="cod_politica" value="<?php echo $cod_politica; ?>">

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Novo nome:&nbsp;&nbsp;</label>
              <input type="text" class="form-control form-control-sm" name="nome" placeholder="Nome" value="<?php echo $nome;?>">
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Quota Padr&atilde;o:&nbsp;&nbsp;</label>
              <input type="text" class="form-control form-control-sm" name="quota_padrao" value="<?php echo $quota_padrao; ?>">
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Quota Infinita:&nbsp;&nbsp;</label>
              <select class="form-control form-control-sm" name="quota_infinita">
                  <option value="0" 
                  <?php
                    if ($quota_infinita == 0) echo " selected ";
                  ?>  
                    >N&atilde;o</option>
              <option value="1" 
                    <?php
                    if ($quota_infinita == 1) echo " selected ";
                  ?>
                  >Sim</option>
              </select>
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Quota Acumulativa:&nbsp;&nbsp;</label>
              <select class="form-control form-control-sm" name="quota_acumulativa">
              <option value="0" 
                  <?php
                    if ($quota_acumulativa == 0) echo " selected ";
                  ?>  
                    >N&atilde;o</option>
              <option value="1" 
                    <?php
                    if ($quota_acumulativa == 1) echo " selected ";
                  ?>
                  >Sim</option>
              </select>
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-sm">Prioridade:&nbsp;&nbsp;</label>
              <input type="text" class="form-control form-control-sm" name="prioridade" placeholder="0" value="<?php echo $prioridade;?>">
         </div>


           <button type="submit" class="btn btn-primary">Alterar Pol&iacute;tica</button>&nbsp;&nbsp;
           <a class="btn btn-primary" href="index.php" role="button" aria-expanded="false">Voltar</a>
        </form>
        </div></div>


    </td></tr>
    <tr><td> <br>
      <div class="card">
        <div class="card-header">
          <b>Grupos</b> da Pol&iacute;tica
        </div>
        <div class="card-body">

        <ul class="list-group list-group-flush">

<?php

     // Busca Politicas no banco de dados 
     if ($stmt = $mysqli->prepare("SELECT cod_politica_grupo,grupo 
          FROM politica_grupo 
          WHERE cod_politica = ? 
          ORDER by grupo")) {
        $stmt->bind_param('i',$cod_politica);
        $stmt->execute(); 
        $stmt->store_result();
        $stmt->bind_result($cod_politica_grupo,$grupo);
     }
     $sem_politica_grupo = 0;
     // Lista politica_grupo 
     while ($stmt->fetch()) {
        $sem_politica_grupo = 1;
        echo "<li class=\"list-group-item\">";
        echo "<a href=\"politica_gp.php?cod_politica_grupo=". $cod_politica_grupo;
        echo "&cod_politica=$cod_politica\">";
        echo "<button type=\"button\" class=\"btn btn-danger btn-sm\" title=\"Excluir Grupo da Pol&iacute;tica\">X</button></a>";
        echo "&nbsp;&nbsp;<b>$grupo</b></li>\n";
     }
     if ($sem_politica_grupo == 0) {
        echo "<li class=\"list-group-item\"><i> Sem grupo nesta pol&iacute;tica</i></li>";
     }


     echo "<li class=\"list-group-item\">"; 
     // Formulario Add grupo na Politica

     echo "<form class=\"form-inline\" action=\"politica_gp.php\" method=\"post\">";
     echo "<input type=\"hidden\" name=\"cod_politica\" value=". $cod_politica .">";

     if (is_base_local($mysqli)) {
         // Base SQL

        echo "  <div class=\"form-group\">";

        echo "<select class=\"form-control\" name=\"grupo\">";

        $select_stmt = $mysqli->prepare("SELECT cod_grupo, grupo
           FROM grupos 
           WHERE grupo not in ( select grupo from politica_grupo 
           where cod_politica = ?)");
        $select_stmt->bind_param('i',$cod_politica);
        $select_stmt->execute();
        $select_stmt->bind_result($cod_grupo,$grupo);
        // Lista grupos nao pertencentes a esta politica
        while ($select_stmt->fetch()) {
           echo "<option value=\"". $grupo ."\">". $grupo ."</option>\n";
        }
        echo "</select>";
        echo " </div>";


     } else {
         // Base LDAP
         // TODO: QUANDO TIVER TEMPO, BUSCAR OS GRUPOS NO LDAP SERVER E APRESENTAR
         //  
        
        echo "  <div class=\"form-group\">";
        echo "   <input type=\"text\" class=\"form-control\" placeholder=\"Nome do Grupo\" name=\"grupo\">";
        echo " </div>";

     }

     echo "&nbsp;&nbsp; <button type=\"submit\" class=\"btn btn-primary\">Atribuir Grupo</button>";
     echo "</form>";
     

     echo "</li>\n";
?>

        </ul>



        </div>
      </div>
    </td></tr>

    <tr><td> <br>
      <div class="card">
        <div class="card-header">
          <b>Impressoras</b> da Pol&iacute;tica
        </div>
        <div class="card-body">

        <ul class="list-group list-group-flush">

<?php

     // Busca Politicas no banco de dados 
     if ($stmt = $mysqli->prepare("SELECT cod_politica_impressora,impressora,peso 
          FROM politica_impressora 
          WHERE cod_politica = ? 
          ORDER by impressora")) {
        $stmt->bind_param('i',$cod_politica);
        $stmt->execute(); 
        $stmt->store_result();
        $stmt->bind_result($cod_politica_impressora,$impressora,$peso);
     }
     $sem_politica_impressora = 0;
     // Lista politica_impressora 
     while ($stmt->fetch()) {
        $sem_politica_impressora = 1;
        echo "<li class=\"list-group-item\">";
        echo "<a href=\"politica_gp.php?cod_politica_impressora=". $cod_politica_impressora;
        echo "&cod_politica=$cod_politica\">";
        echo "<button type=\"button\" class=\"btn btn-danger btn-sm\" title=\"Excluir Impressora da Pol&iacute;tica\">X</button></a>";
        echo "&nbsp;&nbsp;<b>$impressora </b><small><i> Peso: $peso</i></small></li>\n";
     }
     if ($sem_politica_impressora == 0) {
        echo "<li class=\"list-group-item\"><i> Sem impressora nesta pol&iacute;tica</i></li>";
     }


     echo "<li class=\"list-group-item\">"; 
     // Formulario Add impressora na Politica

     echo "<form class=\"form-inline\" action=\"politica_gp.php\" method=\"post\">";
     echo "<input type=\"hidden\" name=\"cod_politica\" value=". $cod_politica .">";


     // TODO: QUANDO TIVER TEMPO, BUSCAR AS IMPRESSORAS VIA IPP E APRESENTAR
     //       Estudando php-ipp e cups-ipp-php
    
     echo "  <div class=\"form-group\">";
     echo "   <input type=\"text\" class=\"form-control\" placeholder=\"Nome da Impressora\" name=\"impressora\">";
     echo " </div>";

     echo "&nbsp;&nbsp; <button type=\"submit\" class=\"btn btn-primary\">Atribuir Impressora</button>";
     echo "</form>";
     echo "<br><small><p class=\"text-info\">Obs.: O nome da impressora é Case-sensitive. \"HP\" é diferente de \"hp\".</p></small>";
     

     echo "</li>\n";
?>

        </ul>



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
if ((isset($_POST['cod_politica'])) AND (isset($_POST['nome']))) {
   $nome = trim($_POST['nome']);
   $quota_padrao = (int) trim($_POST['quota_padrao']);
   $quota_infinita = (int) trim($_POST['quota_infinita']);
   $quota_acumulativa = (int) trim($_POST['quota_acumulativa']);
   $prioridade = (int) trim($_POST['prioridade']);
   $cod_politica = (int) $_POST['cod_politica'];

   if ($quota_infinita != 1) {
      $quota_infinita = 0;
   } else {
      $quota_padrao = 0;
   }
   if ($quota_acumulativa != 1) {
      $quota_acumulativa = 0;
   }

 // Novo nome ja Existe?
 $select_stmt = $mysqli->prepare("SELECT nome
    FROM politicas
    WHERE cod_politica != ? AND nome = ? LIMIT 1");
 $select_stmt->bind_param('is', $cod_politica, $nome);
 // Executar a tarefa pré-estabelecida.
 if (! $select_stmt->execute()) {
    header('Location: error.php?err=Registration failure: SELECT POLITICA.');
    exit();
 }
 $select_stmt->store_result();
 if ($select_stmt->num_rows > 0) {
    echo "<div class=\"alert alert-danger\" role=\"alert\">Pol&iacute;tica com mesmo nome j&aacute; existente cadastrado!</div>";
    echo "<a class=\"btn btn-primary\" href=politica_editar.php?cod_politica=". $cod_politica ." role=\"button\" aria-expanded=\"false\">Voltar</a>";
    include '../includes/footer.php';
    exit();
 }
 $select_stmt->close();

  // grava  politica
  $update_stmt = $mysqli->prepare("UPDATE politicas
      SET nome = ?, quota_acumulativa = ?, quota_infinita = ?, quota_padrao = ?, prioridade = ?
     WHERE cod_politica = ?");
  $update_stmt->bind_param('siiiii',$nome, $quota_acumulativa, $quota_infinita, $quota_padrao, $prioridade,$cod_politica);
  $update_stmt->execute();
  $update_stmt->close();

  echo "<div class=\"alert alert-success\" role=\"alert\">Pol&iacute;tica Alterada com Sucesso!</div><br>";
  echo "<a class=\"btn btn-primary\" href=\"index.php\" role=\"button\" aria-expanded=\"false\">Voltar</a>";

}
include '../includes/footer.php';


?>
