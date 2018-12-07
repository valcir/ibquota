<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 05/11/2018 - Valcir C.
 *
 * Exclui Grupo e seus relacionamentos
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

// Não teve variavel enviada pelo Formulario
if ( !isset($_GET['cod_grupo']) ) {
       header("Location: index.php");
       exit();
}


$cod_grupo = trim($_GET['cod_grupo']);


// Existe politica criada para este grupo?
$stmt = $mysqli->prepare("SELECT politica_grupo.cod_politica
    FROM politica_grupo, grupos 
    WHERE grupos.cod_grupo = ? AND politica_grupo.grupo = grupos.grupo");
$stmt->bind_param('i', $cod_grupo);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
   include '../includes/header.php';
   echo "<div class=\"alert alert-danger\" role=\"alert\">Existe Política criada para estre Grupo</div>";
   include '../includes/footer.php';
   exit();
}




// Deleta Grupo-Usuarios 
$deleta_stmt = $mysqli->prepare("DELETE FROM grupo_usuario
  WHERE cod_grupo = ?");
$deleta_stmt->bind_param('i', $cod_grupo);
$deleta_stmt->execute();
$deleta_stmt->close();

// Deleta Grupo 
$deleta_stmt = $mysqli->prepare("DELETE FROM grupos
  WHERE cod_grupo = ?");
$deleta_stmt->bind_param('i', $cod_grupo);
$deleta_stmt->execute();
$deleta_stmt->close();

$p = $_GET['p'];
if ($p > 1) {
   header("Location: index.php?p=" . $p);
} else {
   header("Location: index.php");
} 

exit();
  
?>
