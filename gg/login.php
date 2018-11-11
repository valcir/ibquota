<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 03/11/2018 - Valcir C.
 *
 * Pagina de login
 */ 
include_once 'includes/db.php';
include_once 'includes/functions.php';

if (primeiro_acesso($mysqli) == true) {
    header("Location: primeiro_acesso.php");
    exit();
}

sec_session_start();
 
if (login_check($mysqli) == true) {
    header("Location: includes/logout.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="favicon.png" />
        <title>IBQUOTA 3 - GG Login</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>



    <body class="bg-light">
        <?php
        if (isset($_GET['error'])) {
            echo "<br><center><h3><span class=\"label label-danger\">Erro ao tentar fazer o login. Por favor, tentar novamente!</span></h3></center>";
        } else {
            echo "<br><br>";    
        }
        ?> 

<br>



   
<center>
        <div class="card mb-4 shadow-sm" style="width: 18rem;">
          <div class="card-header bg-success">
            <h4 class="my-0 font-weight-normal"><b>IBQUOTA 3</b></h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title"><small><small>Gerenciador Grafico</small></small></h1>

           <form action="includes/process_login.php" method="post" name="login_form">

            <div class="form-group">
               
                <div class="input-group">

                  <div class="input-group-prepend">
                    <div class="input-group-text"> <img src="png/icon-username.png" class="img-rounded"></div>
                  </div>
                  <input type="text" class="form-control" id="login" name="login"placeholder="Usu&aacute;rio" autofocus>
                </div>
            </div>


            <div class="form-group">

            
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><img src="png/icon-password.png" class="img-rounded"></div>
                  </div>
                  <input type="password" class="form-control" id="senha" name="senha"placeholder="Senha">
                </div>
            </div>
                                          
            <p align="left"><small><a href="lembrarsenha.php">Esqueceu sua senha?</a></small>
            </p>

            <button type="submit" class="btn btn-primary btn-lg">&nbsp;&nbsp;&nbsp;Entrar&nbsp;&nbsp;&nbsp;</button><br><br>

          </form>
          </div>
        </div>
</center>





  <script type="text/JavaScript" src="js/jquery-3.3.1.min.js"></script>
  <script type="text/JavaScript" src="js/bootstrap.min.js"></script>

  </body>
</html>
