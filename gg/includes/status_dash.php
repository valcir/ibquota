<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 30/10/2018 - Valcir C.
 *
 * Modelo/template para seguir
 */  

function top_usuarios_hoje($mysqli) { 
    $stmt = $mysqli->prepare("SELECT usuario, sum(paginas) AS qte_impre
        FROM impressoes
        WHERE cod_status_impressao = 1 AND data_impressao = CURRENT_DATE() 
        GROUP BY usuario ORDER BY qte_impre DESC LIMIT 10");
    // $stmt->bind_param('s', $login);
    $stmt->execute();    
    $stmt->store_result();
    $stmt->bind_result($usuario, $impressoes );


    echo "<div class=\"card border-success mb-3\" style=\"max-width: 18rem;\">";
    echo "<div class=\"card-header\">Top10 usu&aacute;rios hoje</div>";
    echo " <div class=\"card-body text-success\">";

    echo "<ul class=\"list-group\">";
    $tem_usuarios = 0;
    while ($stmt->fetch()) {
      $tem_usuarios = 1;
	  echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
	  echo $usuario;
	  echo "<span class=\"badge badge-primary badge-pill\">". $impressoes ."</span>";
	  echo "</li>";
    }
    if ($tem_usuarios == 0) {
      echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
	  echo "<i>N&atilde;o h&aacute; registros...</i>";
	  echo "</li>";
    }
    echo "</ul>";
    echo "</div></div>";  
}


function top_usuarios_mes($mysqli) { 
	$dia = date("d");
	$dia--;
    $stmt = $mysqli->prepare("SELECT usuario, sum(paginas) AS qte_impre
        FROM impressoes
        WHERE cod_status_impressao = 1 AND 
        data_impressao between (CURRENT_DATE() - ?) and CURRENT_DATE() 
        GROUP BY usuario ORDER BY qte_impre DESC LIMIT 10");
    $stmt->bind_param('i', $dia);
    $stmt->execute();    
    $stmt->store_result();
    $stmt->bind_result($usuario, $impressoes);

    echo "<div class=\"card border-success mb-3\" style=\"max-width: 18rem;\">";
    echo "<div class=\"card-header\">Top10 usu&aacute;rios este m&ecirc;s</div>";
    echo " <div class=\"card-body text-success\">";

    echo "<ul class=\"list-group\">";
    $tem_usuarios = 0;
    while ($stmt->fetch()) {
      $tem_usuarios = 1;
	  echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
	  echo $usuario;
	  echo "<span class=\"badge badge-primary badge-pill\">". $impressoes ."</span>";
	  echo "</li>";
    }
    if ($tem_usuarios == 0) {
      echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
	  echo "<i>N&atilde;o h&aacute; registros...</i>";
	  echo "</li>";
    }
    echo "</ul>";
    echo "</div></div>";  
}

function qtde_impressoes_hoje($mysqli) { 
    $stmt = $mysqli->prepare("SELECT sum(paginas)
        FROM impressoes
        WHERE cod_status_impressao = 1 AND 
        data_impressao = CURRENT_DATE()");
    //$stmt->bind_param('i', $dia);
    $stmt->execute();    
    $stmt->store_result();
    $stmt->bind_result($impressoes);
    $stmt->fetch();

    $e_stmt = $mysqli->prepare("SELECT sum(paginas)
        FROM impressoes
        WHERE cod_status_impressao <> 1 AND 
        data_impressao = CURRENT_DATE()");
    //$stmt->bind_param('i', $dia);
    $e_stmt->execute();    
    $e_stmt->store_result();
    $e_stmt->bind_result($impressoes_erro);
    $e_stmt->fetch();

    if ( !isset($impressoes) ) $impressoes = 0;
    if ( !isset($impressoes_erro) ) $impressoes_erro = 0;

    echo "<div class=\"card border-success mb-3\" style=\"max-width: 18rem;\">";
    echo "<div class=\"card-header\">Qtde Impress&otilde;es hoje</div>";
    echo " <div class=\"card-body text-success\">";

    echo "<ul class=\"list-group\">";

    echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
    echo "Sucesso";
	echo "<span class=\"badge badge-primary badge-pill\">". $impressoes ."</span>";
    echo "</li>";

    echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
    echo "Erro";
	echo "<span class=\"badge badge-danger badge-pill\">". $impressoes_erro ."</span>";
    echo "</li>";

    echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
    echo "<b>Total</b>";
	echo "<span class=\"badge badge-primary badge-pill\">". ($impressoes + $impressoes_erro) ."</span>";
    echo "</li>";

    echo "</ul>";
    echo "</div></div>";  
}

function qtde_impressoes_mes($mysqli) { 
	$dia = date("d");
	$dia--;
    $stmt = $mysqli->prepare("SELECT sum(paginas)
        FROM impressoes
        WHERE cod_status_impressao = 1 AND 
        data_impressao between (CURRENT_DATE() - ?) and CURRENT_DATE()");
    $stmt->bind_param('i', $dia);
    $stmt->execute();    
    $stmt->store_result();
    $stmt->bind_result($impressoes);
    $stmt->fetch();

    $e_stmt = $mysqli->prepare("SELECT sum(paginas)
        FROM impressoes
        WHERE cod_status_impressao <> 1 AND 
        data_impressao between (CURRENT_DATE() - ?) and CURRENT_DATE()");
    $e_stmt->bind_param('i', $dia);
    $e_stmt->execute();    
    $e_stmt->store_result();
    $e_stmt->bind_result($impressoes_erro);
    $e_stmt->fetch();

    if ( !isset($impressoes) ) $impressoes = 0;
    if ( !isset($impressoes_erro) ) $impressoes_erro = 0;


    echo "<div class=\"card border-success mb-3\" style=\"max-width: 18rem;\">";
    echo "<div class=\"card-header\">Qtde Impress&otilde;es este m&ecirc;s</div>";
    echo " <div class=\"card-body text-success\">";

    echo "<ul class=\"list-group\">";

    echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
    echo "Sucesso";
	echo "<span class=\"badge badge-primary badge-pill\">". $impressoes ."</span>";
    echo "</li>";

    echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
    echo "Erro";
	echo "<span class=\"badge badge-danger badge-pill\">". $impressoes_erro ."</span>";
    echo "</li>";

    echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
    echo "<b>Total</b>";
	echo "<span class=\"badge badge-primary badge-pill\">". ($impressoes + $impressoes_erro) ."</span>";
    echo "</li>";

    echo "</ul>";
    echo "</div></div>";  
}

function erros_log_ibquota($mysqli) { 
    $stmt = $mysqli->prepare("SELECT mensagem, datahora
        FROM log_ibquota 
        ORDER BY datahora DESC LIMIT 10");
    // $stmt->bind_param('s', $login);
    $stmt->execute();    
    $stmt->store_result();
    $stmt->bind_result($mensagem, $datahora );

    echo "<div class=\"card border-success mb-3\" style=\"max-width: 28rem;\">";
    echo "<div class=\"card-header\">LOG - &uacute;ltimos erros</div>";
    echo " <div class=\"card-body text-success\">";

    echo "<ul class=\"list-group\">";
    $tem_log = 0;
    while ($stmt->fetch()) {
      $hora = date("H:i:s", strtotime($datahora));
      $dia = date("d/m/Y", strtotime($datahora));
      $tem_log = 1;
	 // echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
	  echo "<li>";
	  echo "<p title=\"$dia\"><small>$hora - $mensagem</small></p>";
	  echo "</li>";
    }
    if ($tem_log == 0) {
      echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">";
	  echo "<i>N&atilde;o h&aacute; registros...</i>";
	  echo "</li>";
    }
    echo "</ul>";
    echo "</div></div>";  
}

?>




