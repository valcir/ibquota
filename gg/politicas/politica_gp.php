<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 16/11/2018 - Valcir C.
 *
 * Exclui/Atribui Grupo ou Impressora na Politica
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



// Não teve variavel cod_politica enviada
if ( !isset($_GET['cod_politica']) && !isset($_POST['cod_politica']) ) {
    header("Location: index.php");
    exit();
}


//
// Excluir Grupo da Politica
//
if ( isset($_GET['cod_politica_grupo']) && isset($_GET['cod_politica'])) {
    
    $cod_politica_grupo = trim($_GET['cod_politica_grupo']);
    $cod_politica = trim($_GET['cod_politica']);

    // Busca nome Grupo
    $select_stmt = $mysqli->prepare("SELECT grupo
      FROM politica_grupo
      WHERE cod_politica_grupo = ? LIMIT 1");
    $select_stmt->bind_param('i', $cod_politica_grupo);
    $select_stmt->execute();
    $select_stmt->bind_result($grupo);
    $select_stmt->fetch();

    if (strlen($grupo) > 0) {
      // Deleta Politica Quota Usuario
      $deleta_stmt = $mysqli->prepare("DELETE FROM quota_usuario
        WHERE cod_politica = ? AND grupo = ?");
      $deleta_stmt->bind_param('is', $cod_politica, $grupo);
      $deleta_stmt->execute();
      $deleta_stmt->close();
    }
 
    // Deleta Grupo-Politica 
    $deleta_stmt = $mysqli->prepare("DELETE FROM politica_grupo
      WHERE grupo = ? AND cod_politica = ?");
    $deleta_stmt->bind_param('si', $grupo,$cod_politica);
    $deleta_stmt->execute();
    $deleta_stmt->close();
           
}

//
// Excluir Impressora da Politica
//
if ( isset($_GET['cod_politica_impressora']) && isset($_GET['cod_politica'])) {

    $cod_politica_impressora = trim($_GET['cod_politica_impressora']);
    $cod_politica = trim($_GET['cod_politica']);
    // Deleta Impressora-Politica 
    $deleta_stmt = $mysqli->prepare("DELETE FROM politica_impressora
      WHERE cod_politica_impressora = ? AND cod_politica = ?");
    $deleta_stmt->bind_param('ii', $cod_politica_impressora,$cod_politica);
    $deleta_stmt->execute();
    $deleta_stmt->close();

}

//
// Add Grupo na Politica
//
if ( isset($_POST['grupo']) && isset($_POST['cod_politica'])) {

    $grupo = trim($_POST['grupo']);
    $cod_politica = trim($_POST['cod_politica']);
   
    if (strlen($grupo) > 0) {

     //Já existe o grupo na politica?
      $stmt = $mysqli->prepare("SELECT *
          FROM politica_grupo 
          WHERE grupo = ? AND cod_politica = ?");
      $stmt->bind_param('si', $grupo,$cod_politica);
      $stmt->execute();
      $stmt->store_result();
      if ($stmt->num_rows > 0) {
         header("Location: politica_editar.php?cod_politica=" . $cod_politica);
         exit();
      }

     //Não, entrao adicionar 
      $insert_stmt = $mysqli->prepare("INSERT INTO politica_grupo (grupo, cod_politica)
       VALUES (?, ?)");
      $insert_stmt->bind_param('si', $grupo,$cod_politica);
      $insert_stmt->execute();
    }
}

//
// Add Impressora na Politica
//
if ( isset($_POST['impressora']) && isset($_POST['cod_politica'])) {

    $impressora = trim($_POST['impressora']);
    $cod_politica = trim($_POST['cod_politica']);
   
    if (strlen($impressora) > 0) {
       //Já existe a Impressora na politica?
        $stmt = $mysqli->prepare("SELECT *
            FROM politica_impressora 
            WHERE impressora = ? AND cod_politica = ?");
        $stmt->bind_param('si', $impressora,$cod_politica);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
           header("Location: politica_editar.php?cod_politica=" . $cod_politica);
           exit();
        }
       
       //Não, entrao adicionar 
        $insert_stmt = $mysqli->prepare("INSERT INTO politica_impressora 
          (impressora, cod_politica,prioridade,peso) VALUES (?, ?,1,1)");
        $insert_stmt->bind_param('si', $impressora,$cod_politica);
        $insert_stmt->execute();
    }
}


header("Location: politica_editar.php?cod_politica=" . $cod_politica);

?>
