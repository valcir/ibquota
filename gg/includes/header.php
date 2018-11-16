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
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo $path_raiz;?>configuracao.php">Configuração</a>
      </li>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Cadastros
        </a>
        <div class="dropdown-menu bg-success" aria-labelledby="navbarDropdown" >
          <a class="dropdown-item" href="<?php echo $path_raiz;?>usuarios/">Usu&aacute;rios</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>grupos/">Grupos</a>
          <a class="dropdown-item" href="#">Impressora</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>politicas/">Pol&iacute;tica de Impressão</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>adm_users/">Usu&aacute;rios Administrativos</a>
        </div>
      </li>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Relat&oacute;rios
        </a>
        <div class="dropdown-menu bg-success" aria-labelledby="navbarDropdown" >
          <a class="dropdown-item" href="<?php echo $path_raiz;?>relatorios/impressoes.php">Impressoes</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>relatorios/impressoes_com_erro.php">Impressoes com erro</a>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>relatorios/grupos.php">Grupos</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>relatorios/ibquota_logs.php">Erros Backend</a>
        </div>
      </li>      
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Documentação
        </a>
        <div class="dropdown-menu bg-success" aria-labelledby="navbarDropdown" >
          <a class="dropdown-item" href="#">Documentacao</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">FAQ - Perguntas Frequentes</a>
        </div>
      </li>
    </ul>

    <ul class="navbar-nav navbar-right">
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Conta
        </a>
        <div class="dropdown-menu bg-success" aria-labelledby="navbarDropdown" >
          <a class="dropdown-item" href="<?php echo $path_raiz;?>trocarsenha.php">Trocar Senha</a>
          <a class="dropdown-item" href="#">Ajuda</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo $path_raiz;?>includes/logout.php">Sair</a>
        </div>
      </li>
     </ul>
  </div>
</nav>
<br><br>

