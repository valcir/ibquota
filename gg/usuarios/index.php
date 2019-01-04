<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 08/11/2018 - Valcir C.
 *
 * Lista Usuarios
 */  
include_once '../includes/db.php';
include_once '../includes/functions.php';

 
sec_session_start();

if (login_check($mysqli) == false) {
  header("Location: ../login.php");
  exit();
}

if ($_SESSION['permissao'] < 1){
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
        FROM usuarios")) {
    $num_stmt->execute();
    $num_stmt->bind_result($p_num_registros);
    $num_stmt->fetch();
    $num_stmt->close();
}


 
// Busca grupos no banco de dados 
if ($stmt = $mysqli->prepare("SELECT cod_usuario,usuario
        FROM usuarios ORDER BY usuario LIMIT ?, ?")) {
    $stmt->bind_param('ii', $p_inicio,$p_qtde_por_pagina);
    $stmt->execute(); 
    $stmt->store_result();
    $stmt->bind_result($cod_usuario,$usuario);
}
?>


<center>
  <h2><font color=#428bca>Usu&aacute;rios</font></h2>
<br>

<table width="500" border="0">
<tr><td>

 <table class="table table-striped">
   <tr>
    <th>Usu&aacute;rio</th>
    <th align="right"><p align="right">A&ccedil;&otilde;es</p></th>
  </tr>

<?php
 while ($stmt->fetch()) {
    echo "<tr>";
?>    

    <td><?php echo $usuario; ?></td>
    <td align="right">

      <a href="usuario_grupo.php<?php echo '?cod_usuario=' . $cod_usuario; ?>">
        <button type="button" class="btn btn-info btn-sm" title="Grupo">G</button>
      </button></a>

      <a href="usuario_editar.php<?php echo '?cod_usuario=' . $cod_usuario; ?>">
        <button type="button" class="btn btn-info btn-sm" title="Editar Usu&aacute;rio">E</button>
      </button></a>

      <a href="usuario_excluir.php<?php echo '?cod_usuario=' . $cod_usuario; ?>">
        <button type="button" class="btn btn-danger btn-sm" title="Excluir Usu&aacute;rio">X</button>
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
<h4>Cadastro r&aacute;pido</h4>

<form  action="usuario_add.php" method="post">
  <div class="form-group">
    <input type="text" class="form-control" placeholder="Nome do Usu&aacute;rio" name="usuario">
  </div>
  <button type="submit" class="btn btn-primary">Cadastrar</button>
</form><br>

</div></div>

</td></tr>



</table>
</center>




<?php include '../includes/footer.php'; ?>
