<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 29/11/2018 - Valcir C.
 *
 * Testa configuracao e conexao com servico de Diretorio via LDAP
 */  

include_once 'includes/db.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == false) {
   header("Location: login.php");
   exit();
}
include 'includes/ldap.php';
include 'includes/header.php';


// Inicia Variável do formulario
$stmt = $mysqli->prepare("SELECT base_local, LDAP_server,LDAP_port,LDAP_filter,
                                 LDAP_base,LDAP_user,LDAP_password 
                          FROM config_geral WHERE id = 1 LIMIT 1");
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($base_local, $ldap_server,$ldap_porta,$ldap_filtro,
                  $ldap_base,$ldap_usuario,$ldap_senha);
$stmt->fetch();

if ($base_local == 1) {
  
   echo "<div class=\"alert alert-warning\" role=\"alert\">";
   echo "Base de Dados de usu&aacute;rios dever estar selecionada";
   echo " como <b>LDAP ou AD</b>!</div>";
   echo "<center><a class=\"btn btn-primary\" href=\"configuracao.php\" role=\"button\" aria-expanded=\"false\">Configura&ccedil;&atilde;o</a></center>";
   include 'includes/footer.php';
   exit();
}

?>

<center>
  <div class="card mb-4 shadow-sm" style="width: 40rem;">
    <div class="card-header bg-success">
      <h4 class="my-0 font-weight-normal">Teste de conex&atilde;o LDAP</h4>
    </div>
    <div class="card-body">
          <p class="text-left">Servidor LDAP: <b><?php echo $ldap_server; ?></b></p>
          <p class="text-left">Porta IP: <b><?php echo $ldap_porta; ?></b></p>
          <p class="text-left">Usu&aacute;rio LDAP: <b><?php echo $ldap_usuario; ?></b></p>
          <p class="text-left">Filtro LDAP: <b><?php echo $ldap_filtro; ?></b></p>
          <p class="text-left">Base LDAP: <b><?php echo $ldap_base; ?></b></p>
          <hr class="my-1">

          <p class="text-left">Modulo LDAP instalado no PHP: 
<?php
if (function_exists("ldap_connect")) {
  echo "<span class=\"badge badge-success\">Sucesso</span>";
} else {
  echo "<span class=\"badge badge-danger\">Falhou</span>";
  echo "</div></div></center>";
  include 'includes/footer.php';
  exit();
}

?>


          <p class="text-left">Conex&atilde;o com o servidor LDAP: 
<?php
$conn = ibquota_ldap_connect();
if (!$conn) {
  echo "<span class=\"badge badge-danger\">Falhou</span>";
} else {
  echo "<span class=\"badge badge-success\">Sucesso</span>";
}
?>
          </p>
          <p class="text-left">BIND com usu&aacute;rio <i><small><?php echo $ldap_usuario;?></small></i>: 

<?php

if (ibquota_ldap_bind($conn)) {
   echo "<span class=\"badge badge-success\">Sucesso</span>";
} else {
   echo "<span class=\"badge badge-danger\">Falhou</span>";
}
?>
          </p>
          <p class="text-left">Usu&aacute;rios: 

          <span class="badge badge-success">
<?php
$qtde_usuarios = ibquota_ldap_qtde_usuarios($conn,$ldap_base,$ldap_filtro);
if (ldap_errno($conn) == 4) echo "+";
echo $qtde_usuarios;
?>

</span>

          </p>
          <p class="text-left">Grupos: 

          <span class="badge badge-success">
<?php
$qtde_grupos = ibquota_ldap_qtde_grupos($conn,$ldap_base,$ldap_filtro);
if (ldap_errno($conn) == 4) echo "+";
echo $qtde_grupos;
?>

          </span>

          </p>





    </div>
  </div>
</center>








<?php  include 'includes/footer.php'; ?>
