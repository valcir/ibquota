<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 06/12/2018 - Valcir C.
 *
 * Pagina de Help
 */  
include_once 'includes/db.php';
include_once 'includes/functions.php';

 
sec_session_start();

if (login_check($mysqli) == false) {
  header("Location: ../login.php");
  exit();
}

//if ($_SESSION['permissao'] != 0){
//  header("Location: ../login.php");
//  exit();
//}
 
include 'includes/header.php';

?>

<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Permiss&atilde;o de Usu&aacute;rios Administrativos 
        </button>
      </h5>
    </div>
	<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
  
		<table class="table" align="center">
		  <thead>
		    <tr align="center">
		      <th scope="col"> </th>
		      <th scope="col">Administrador Geral do IBQUOTA</th>
		      <th scope="col">Administrador de Impress&atilde;o</th>
		      <th scope="col">Visualiza Relat&oacute;rio</th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr align="center">
		      <th scope="row">Visualizar Relat&oacute;rios</th>
		      <td>&#10004;</td>
		      <td>&#10004;</td>
		      <td>&#10004;</td>
		    </tr>		  
		    <tr align="center">
		      <th scope="row">Cadastro de Usu&aacute;rios</th>
		      <td>&#10004;</td>
		      <td>&#10004;</td>
		      <td>&#10006;</td>
		    </tr>
		    <tr align="center">
		      <th scope="row">Cadastro de Grupos</th>
		      <td>&#10004;</td>
		      <td>&#10004;</td>
		      <td>&#10006;</td>
		    </tr>
		    <tr align="center">
		      <th scope="row">Quota Adicional para usu&aacute;rios</th>
		      <td>&#10004;</td>
		      <td>&#10004;</td>
		      <td>&#10006;</td>
		    </tr>		    
		    <tr align="center">
		      <th scope="row">Cadastro Pol&iacute;tica de Impress&atilde;o</th>
		      <td>&#10004;</td>
		      <td>&#10006;</td>
		      <td>&#10006;</td>
		    </tr>
		    <tr align="center">
		      <th scope="row">Cadastro Usu&aacute;rio Administrativo</th>
		      <td>&#10004;</td>
		      <td>&#10006;</td>
		      <td>&#10006;</td>
		    </tr>
		    <tr align="center">
		      <th scope="row">Configura&ccedil;&atilde;o Geral</th>
		      <td>&#10004;</td>
		      <td>&#10006;</td>
		      <td>&#10006;</td>
		    </tr>	
		    <tr align="center">
		      <th scope="row">Inicializa Quota de Impress&atilde;o</th>
		      <td>&#10004;</td>
		      <td>&#10006;</td>
		      <td>&#10006;</td>
		    </tr>			   	    
		   </tbody>
		</table>
 
      </div>
    </div>
  </div>
 </div>







<?php include 'includes/footer.php'; ?>
