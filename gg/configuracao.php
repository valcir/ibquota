<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 02/11/2018 - Valcir C.
 *
 * Atualiza tabela de configuracao Geral
 * É necessário estar logado como usuário com permissao ADM = 0
 */  

include_once 'includes/db.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == false) {
   header("Location: login.php");
   exit();
}


if ($_SESSION['permissao'] != 2){
   header("Location: login.php");
   exit();
}

include 'includes/header.php';

if (isset($_POST['path_pkpgcounter']) or isset($_POST['path_python'])
    or isset($_POST['nivel_debug'])) {
        
    $path_pkpgcounter = trim($_POST['path_pkpgcounter']);
    $path_python = trim($_POST['path_python']);
    $nivel_debug = trim($_POST['nivel_debug']);
    $base_local = trim($_POST['base_local']);
    $ldap_server = trim($_POST['ldap_server']);
    $ldap_porta = trim($_POST['ldap_porta']);
    $ldap_usuario = trim($_POST['ldap_usuario']);
    $ldap_senha = trim($_POST['ldap_senha']);
    $ldap_filtro = trim($_POST['ldap_filtro']);
    $ldap_base = trim($_POST['ldap_base']);

    if ($update_stmt = $mysqli->prepare("UPDATE config_geral SET path_pkpgcounter=?,path_python=?,
                                      base_local=?,LDAP_server=?,LDAP_port=?,LDAP_filter=?,
                                      LDAP_base=?,LDAP_user=?,LDAP_password=?,Debug=? WHERE id=1")) {
          $update_stmt->bind_param('ssissssssi', $path_pkpgcounter,$path_python,$base_local,
                                   $ldap_server,$ldap_porta,$ldap_filtro,$ldap_base,$ldap_usuario,
                                   $ldap_senha,$nivel_debug);
          // Executar a tarefa pré-estabelecida.
          if (! $update_stmt->execute()) {
	           header('Location: error.php?err=Configuracao: UPDATE');
          }
    }
    echo "<br><br><center><h2>Configuração gravada com Sucesso!!!</h2>";
    
    include 'includes/footer.php';
    exit();

} else {
    // Inicia Variável do formulario
    $stmt = $mysqli->prepare("SELECT path_pkpgcounter,path_python,base_local, 
                                     LDAP_server,LDAP_port,LDAP_filter,
                                     LDAP_base,LDAP_user,LDAP_password,Debug 
                              FROM config_geral WHERE id = 1 LIMIT 1");
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($path_pkpgcounter,$path_python,$base_local,
                       $ldap_server,$ldap_porta,$ldap_filtro,$ldap_base,$ldap_usuario,
                       $ldap_senha,$nivel_debug );
    $stmt->fetch();
}


?>





<center>
  <div class="card mb-4 shadow-sm" style="width: 40rem;">
    <div class="card-header bg-success">
      <h4 class="my-0 font-weight-normal">Configuração Geral do IBQUOTA</h4>
    </div>
    <div class="card-body">
      <h5 class="card-title pricing-card-title"> </h5>

      <?php
      if (!empty($error_msg)) {
          echo $error_msg;
      }
      ?>

     <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post" class="form-inline">
  
         <div class="form-group row">
              <label class="form-group col-form-label-lg">Nível de Log:&nbsp;&nbsp;</label>
              <select class="form-control" name="nivel_debug">
                  <option value="0" <?php if($nivel_debug == 0) echo "selected"; ?> >Sem Log</option>
                  <option value="1" <?php if($nivel_debug == 1) echo "selected"; ?>>Log Mínimo</option>
                  <option value="2" <?php if($nivel_debug == 2) echo "selected"; ?>>Log Detalhado</option>
              </select>
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-lg">Caminho do&nbsp;<code> python</code>:&nbsp;&nbsp;</label>
              <input type="text" class="form-control" name="path_python" value="<?php echo $path_python; ?>">
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-lg">Caminho do&nbsp;<code> pkpgcounter</code>:&nbsp;&nbsp;</label>
              <input type="text" class="form-control" name="path_pkpgcounter" value="<?php echo $path_pkpgcounter; ?>">
         </div>

         <div class="form-group row">
              <label class="form-group col-form-label-lg">Base de Dados de usuários:&nbsp;&nbsp;</label>
              <select class="form-control" name="base_local">
                  <option value="1" <?php if($base_local == 1) echo "selected"; ?> >LOCAL (SQL)</option>
                  <option value="0" <?php if($base_local == 0) echo "selected"; ?> >LDAP ou AD</option>
              </select>
         </div>
 
        <div class="jumbotron">
          <h4 class="display-5">Configuração LDAP</h4>
          <hr class="my-1">
          <p>Os parametros abaixo serão utilizados apenas se a Base de Dados de Usuários estiver selecionada como "LDAP ou AD".</p>
             <div class="form-group row">
                  <label class="form-group col-form-label-lg">Servidor LDAP:&nbsp;&nbsp;</label>
                  <input type="text" class="form-control" name="ldap_server" value="<?php echo $ldap_server; ?>">
             </div>

             <div class="form-group row">
                  <label class="form-group col-form-label-lg">Porta LDAP:&nbsp;&nbsp;</label>
                  <input type="text" class="form-control" name="ldap_porta" value="<?php echo $ldap_porta; ?>">
             </div>

             <div class="form-group row">
                  <label class="form-group col-form-label-lg">Usuário LDAP:&nbsp;&nbsp;</label>
                  <input type="text" class="form-control" name="ldap_usuario" value="<?php echo $ldap_usuario; ?>">
                  <small>Deixe em branco para BIND anônimo.</small>
             </div>

             <div class="form-group row">
                  <label class="form-group col-form-label-lg">Senha LDAP:&nbsp;&nbsp;</label>
                  <input type="password" class="form-control" name="ldap_senha" value="<?php echo $ldap_senha; ?>">
             </div>

             <div class="form-group row">
                  <label class="form-group col-form-label-lg">Filtro LDAP:&nbsp;&nbsp;</label>
                  <input type="text" class="form-control" name="ldap_filtro" value="<?php echo $ldap_filtro; ?>">
                  <small>Ex.: (|(cn=$user)(samaccountname=$user)(uid=$user))</small>
             </div>
             <div class="form-group row">
                  <label class="form-group col-form-label-lg">Base LDAP:&nbsp;&nbsp;</label>
                  <input type="text" class="form-control" name="ldap_base" value="<?php echo $ldap_base; ?>">
             </div>
        </div>

        <button type="submit" class="btn btn-primary">&nbsp;Gravar Dados&nbsp;</button>

    </form>
    </div>
  </div>
</center>


<?php  include 'includes/footer.php'; ?>
