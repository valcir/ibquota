<?php 
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 23/12/2018 - Valcir C.
 *
 * Lista Quotas de um usuario especifico
 */ 
include_once '../includes/db.php';
include_once '../includes/functions.php';
 
sec_session_start();


if (login_check($mysqli) == false) {
  header("Location: ../login.php");
  exit();
}

include '../includes/header.php';


// Não teve variavel usuario recebida
if ( !isset($_POST['usuario']) OR strlen($_POST['usuario']) < 1 ) {
?>

  <center><br>
    <h2><font color=#428bca>Digite o login do Usu&aacute;rio</font></h2>
  <br><br>
  <table border="0" width="500" align="center">
  <tr><td>
  <div class="panel panel-default">
      <div class="container-fluid">
          <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post">          

            <div class="form-group">
              <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Login do Usuario" name="usuario">
            </div>

            <center>
             <button type="submit" class="btn btn-primary">Visualizar Usu&aacute;rio</button>
            </center>
            <br><br>
          </form>
      </div>
   </div>
   </td></tr>
   </table>
   </center>
<?php
   include '../includes/footer.php';
   exit;

}


$usuario = $_POST['usuario'];



$stmt = $mysqli->prepare("SELECT cod_politica, nome, quota_infinita 
      FROM politicas");
    //$stmt->bind_param('ii', $p_inicio,$p_qtde_por_pagina);
$stmt->execute(); 
$stmt->store_result();
$stmt->bind_result($cod_politica,$nome_politica,$quota_infinita);

?>

<center><h2><font color=#428bca>Usu&aacute;rio: <?php echo "$usuario"; ?></font></h2><br>

   <table border="0" width="1000" align="center">
    <tr><td>
    <div class="panel panel-default">
      <div class="container-fluid">
       
        <table class="table table-hover table-sm">
          <thead>
            <tr>
              <th scope="col">Pol&iacute;tica</th>
              <th scope="col">Quota</th>
            </tr>
          </thead>
          <tbody>



<?php
   
   // Lista Politicas deste usuario
   while ($stmt->fetch()) {
      $grupo = grupo_usuario_politica($cod_politica,$usuario);
      if ( $grupo != "") {
        echo "<tr>";
        echo "<td>$nome_politica</td>";
        if ($quota_infinita == 1) {
           echo "<td>Quota Infinita</td>";
        } else {
           echo "<td>" . quota_usuario($cod_politica,$usuario) ."</td>";
        }
        echo "</tr>";
      }
   }


?>
            </tbody>
        </table>

      </div>
     </div>

    </td></tr>
   </table>
   <a class="btn btn-primary" href="usuarios.php" role="button" aria-expanded="false">Voltar</a>
   </center>

<?php

   include '../includes/footer.php';
?>
