<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 05/11/2018 - Valcir C.
 *
 * Lista Grupos
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
        FROM grupos")) {
    $num_stmt->execute();
    $num_stmt->bind_result($p_num_registros);
    $num_stmt->fetch();
    $num_stmt->close();
}


 
// Busca grupos no banco de dados 
if ($stmt = $mysqli->prepare("SELECT cod_grupo,grupo
        FROM grupos ORDER BY grupo LIMIT ?, ?")) {
    $stmt->bind_param('ii', $p_inicio,$p_qtde_por_pagina);
    $stmt->execute(); 
    $stmt->store_result();
    $stmt->bind_result($cod_grupo,$grupo);
}
?>


<center>
  <h2><font color=#428bca>Grupos</font></h2>
<br>

<table width="500" border="0">
<tr><td>

 <table class="table table-striped">
   <tr>
    <th>Grupo</th>
    <th align="right"><p align="right">Ações</p></th>
  </tr>

<?php
 while ($stmt->fetch()) {
    echo "<tr>";
?>    

    <td><?php echo $grupo; ?></td>
    <td align="right">

      <a href="grupo_editar.php<?php echo '?cod_grupo=' . $cod_grupo; ?>">
        <button type="button" class="btn btn-info btn-sm" title="Editar Grupo">E</button>
      </button></a>

      <a href="grupo_excluir.php<?php echo '?cod_grupo=' . $cod_grupo; ?>">
        <button type="button" class="btn btn-danger btn-sm" title="Excluir Grupo">X</button>
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

<form  action="grupo_add.php" method="post">
  <div class="form-group">
    <input type="text" class="form-control" placeholder="Nome do Grupo" name="grupo">
  </div>
  <button type="submit" class="btn btn-primary">Cadastrar</button>
</form><br>

</div></div>

</td></tr>



</table>
</center>




















<?php include '../includes/footer.php'; ?>
