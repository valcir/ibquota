<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 06/11/2018 - Valcir C.
 *
 * Lista Usuarios Administradores do IBQUOTA 
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

include '../includes/header.php';


//PAGINACAO
$p = (isset($_GET['p'])) ? (int)$_GET['p'] : 1;
$p = ($p < 1) ? 1 : $p;
$p_inicio = (QTDE_POR_PAGINA * $p) - QTDE_POR_PAGINA;
$p_qtde_por_pagina = (int)QTDE_POR_PAGINA; 
$p_num_registros=0;
if ($num_stmt = $mysqli->prepare("SELECT count(*)
        FROM adm_users")) {
    $num_stmt->execute();
    $num_stmt->bind_result($p_num_registros);
    $num_stmt->fetch();
    $num_stmt->close();
}

 
// Busca grupos no banco de dados 
if ($stmt = $mysqli->prepare("SELECT cod_adm_users,login,nome,email,permissao
        FROM adm_users ORDER BY login LIMIT ?, ?")) {
    $stmt->bind_param('ii', $p_inicio,$p_qtde_por_pagina);
    $stmt->execute(); 
    $stmt->store_result();
    $stmt->bind_result($cod_adm_users,$login,$nome,$email,$permissao);
}
?>


<center>
  <h2><font color=#428bca>Usu&aacute;rios Administrativos do IBQUOTA</font></h2>
<br>

<table width="600" border="0">
<tr><td>

 <table class="table table-striped">
   <tr>
    <th>Login</th>
    <th align="right"><p align="right">A&ccedil;&otilde;es</p></th>
  </tr>

<?php
 while ($stmt->fetch()) {
    echo "<tr>";
?>    

    <td><?php 
          echo "<b>". $login . " </b> " ;
          if ($permissao == 0) echo "<span class=\"badge badge-info\">Adm</span>";
          echo "<br><small>". $nome ." (". $email .")</small>"
    ?></td>
    <td align="right">

      <a href="adm_users_editar.php<?php echo '?cod_adm_users=' . $cod_adm_users; ?>">
        <button type="button" class="btn btn-info btn-sm" title="Editar Usu&aacute;rio Administrativo">E</button>
      </button></a>

      <a href="adm_users_excluir.php<?php echo '?cod_adm_users=' . $cod_adm_users; ?>">
        <button type="button" class="btn btn-danger btn-sm" title="Excluir Usu&aacute;rio Administrativo">X</button>
      </a>

    </td>
  </tr>

<?php
//Fim do while
}
?>

</table>
<?php barra_de_paginas($p,$p_num_registros); ?>
</td></tr>

<tr><td align="center">

<div class="card">
  <div class="card-body">

<h4>Cadastro R&aacute;pido</h4>



<form  action="adm_users_add.php" method="post">

     <div class="form-group row">
          <label class="form-group col-form-label-sm">Login:&nbsp;&nbsp;</label>
          <input type="text" class="form-control form-control-sm" name="login" placeholder="Login">
     </div>

     <div class="form-group row">
          <label class="form-group col-form-label-sm">Senha:&nbsp;&nbsp;</label>
          <input type="password" class="form-control form-control-sm" name="senha" placeholder="Password">
     </div>

     <div class="form-group row">
          <label class="form-group col-form-label-sm">Nome:&nbsp;&nbsp;</label>
          <input type="text" class="form-control form-control-sm" name="nome" placeholder="Name">
     </div>

     <div class="form-group row">
          <label class="form-group col-form-label-sm">E-mail:&nbsp;&nbsp;</label>
          <input type="text" class="form-control form-control-sm" name="email" placeholder="E-mail">
     </div>

     <div class="form-group row">
          <label class="form-group col-form-label-sm">N&iacute;vel de Permiss&atilde;o:&nbsp;&nbsp;</label>
          <select class="form-control form-control-sm" name="permissao">
                    <option value="2">Administrador Geral do IBQUOTA</option>
                    <option value="1" selected>Administrador de Impress&atilde;o</option>
                    <option value="0">Visualiza Relat&oacute;rio</option>
          </select> 
     </div>


   <button type="submit" class="btn btn-primary">&nbsp;Cadastrar Usu&aacute;rio Administrativo&nbsp;</button>

</form>

</div></div>

</td></tr>



</table>
</center>




<?php include '../includes/footer.php'; ?>
