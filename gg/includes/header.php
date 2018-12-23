<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 29/10/2018 - Valcir C.
 *
 * Cabecalho das paginas. Com o menu.
 */ 

if (file_exists("css")) {
  $path_raiz = "";
} else {
  $path_raiz = "../";
}


?>

<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $path_raiz;?>css/bootstrap.min.css" type="text/css" />
        
    <link rel="icon" href="/favicon.png" />
    <meta name="description" content="Controle de Quota de Impressão">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IBQUOTA 3 - Controle de Quota de Impressão</title>
  </head>
  <body>
   

   <nav class="navbar navbar-expand-lg navbar-light bg-success shadow rounded">
  <a class="navbar-brand" href="#"><b>IBQUOTA 3</b></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo $path_raiz;?>index.php">Home</a>
      </li>
<?php
 if ($_SESSION['permissao'] > 0){
?>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Cadastros
        </a>
        <div class="dropdown-menu bg-success" aria-labelledby="navbarDropdown" >
          <a class="dropdown-item" href="<?php echo $path_raiz;?>usuarios/">Usu&aacute;rios</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>grupos/">Grupos</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>usuarios/usuario_quota_add.php">Quota Adicional</a>
        </div>
      </li>
<?php
 }
?>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Relat&oacute;rios
        </a>
        <div class="dropdown-menu bg-success" aria-labelledby="navbarDropdown" >
          <a class="dropdown-item" href="<?php echo $path_raiz;?>relatorios/impressoes.php">Impress&otilde;es</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>relatorios/impressoes_com_erro.php">Impress&otilde;es com erro</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>relatorios/usuarios.php">Quota de Usu&aacute;rio</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>relatorios/ibquota_logs.php">Erros Backend</a>
        </div>
      </li> 

<?php
 if ($_SESSION['permissao'] == 2){
?>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Avan&ccedil;ado
        </a>
        <div class="dropdown-menu bg-success" aria-labelledby="navbarDropdown" >

          <a class="dropdown-item" href="<?php echo $path_raiz;?>configuracao.php">Configura&ccedil;&atilde;o Geral </a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>politicas/">Pol&iacute;ticas de Impress&atilde;o</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>politicas/init_quota_politica.php">Inicializa Quota de Impress&atilde;o</a>

          <a class="dropdown-item" href="<?php echo $path_raiz;?>adm_users/">Usu&aacute;rios Administrativos</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>test_ldap.php">Teste de Conex&atilde;o LDAP</a>
        </div>
      </li>
<?php
 }
?>

    </ul>

    <ul class="navbar-nav navbar-right">
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Conta
        </a>
        <div class="dropdown-menu bg-success" aria-labelledby="navbarDropdown" >
          <a class="dropdown-item" href="<?php echo $path_raiz;?>trocarsenha.php">Trocar Senha</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>ajuda.php">Ajuda</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>includes/logout.php">Sair</a>
        </div>
      </li>
     </ul>
  </div>
</nav>
<br><br>
