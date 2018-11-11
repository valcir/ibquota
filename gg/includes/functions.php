<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 30/10/2018 - Valcir C.
 *
 * Funcoes definidas para utilizacao no GG
 */  


include_once 'db.php';

/** Quantidades de registro por pagina - PAGINACAO **/
define('QTDE_POR_PAGINA', 10);


function sec_session_start() {
    $session_name = 'sec_session_id';   
    $secure = false;    // Impede JavaScript de acessar identificacao da sessao.
    $httponly = true;    // Forca sessao usar apenas cookies. 
   if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    session_name($session_name);
    session_start();            // Inicia a sessao PHP 
    session_regenerate_id();    // Recupera a sessão e deleta a anterior. 
}

function login($login, $password, $mysqli) { 
    if ($stmt = $mysqli->prepare("SELECT cod_adm_users, login, senha, permissao
        FROM adm_users
        WHERE login = ? LIMIT 1")) {
        $stmt->bind_param('s', $login);
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($user_id, $nome, $db_password, $permissao );
        $stmt->fetch();
 
        if ($stmt->num_rows == 0) {
            return false;
        }

        // faz o hash da senha.
        $password = hash('sha256', $password, FALSE);

        if ($db_password == $password) {
            // A senha está correta!
            // Obtém o string usuário-agente do usuário. 
            $user_browser = $_SERVER['HTTP_USER_AGENT'];
            // XSS protect
            $user_id = preg_replace("/[^0-9]+/", "", $user_id);
            $_SESSION['user_id'] = $user_id;
            $nome = preg_replace("/[^a-zA-Z0-9_\-]+/","",$nome);
            $_SESSION['username'] = $nome;
            $_SESSION['login_string'] = hash('sha256', $password . $user_browser);
            $_SESSION['permissao'] = $permissao;

            // Login concluído com sucesso.
            return true;
        }
    }
    return false;
}


function login_check($mysqli) {
    // Verifica se todas as variáveis das sessões foram definidas 
    if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'],
                        $_SESSION['permissao'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Pega a string do usuário.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT senha 
                                      FROM adm_users 
                                      WHERE cod_adm_users = ? LIMIT 1")) {
            // Atribui "$user_id" ao parâmetro. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute(); 
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // Caso o usuário exista, pega variáveis a partir do resultado.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha256', $password . $user_browser);
 
                if ($login_check == $login_string) {
                    // Logado!!!
                    return true;
                } 
            } 
        } 
    } 
    // Não foi logado 
    return false;
}

function esc_url($url) {
// limpa o resultado da variável de servidor PHP_SELF 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        return '';
    } else {
        return $url;
    }
}


function barra_de_paginas($p,$p_registros) {
    # Mostra barra de navegacao de Paginas
    //Sera que o usuario foi safado?
    if ($p < 1) $p=1;
    $p_total = ceil($p_registros/QTDE_POR_PAGINA);
    //Sera que o usuario foi safado?
    if ($p > $p_total) $p=$p_total;
    $p_anterior = (($p -1) <= 0) ? 1: $p-1;
    $p_posterior = (($p +1) >= $p_total) ? $p_total : $p+1;
    if ($p_posterior == 0) $p_posterior = 1;
    
    echo "<center><nav aria-label=\"...\">";
    echo " <ul class=\"pagination pagination-sm\">";

    //Tem mais de 10 registros?
    $inicio_desabilitado="";
    if ($p <= 1){
        $inicio_desabilitado = "class=\"page-item disabled\"";
    } else {
        $inicio_desabilitado = "class=\"page-item\"";
    }

    //Final?
    $fim_desabilitado="";
    if ($p_total <= $p){
        $fim_desabilitado = "class=\"page-item disabled\"";
    } else {
        $fim_desabilitado = "class=\"page-item\"";
    }

    $urlbarra = $_SERVER["PHP_SELF"] . "?";
    if ($urlbarra <> $_SERVER["REQUEST_URI"]) {
      if (isset($_GET['cod_usuario'])) $urlbarra .= "cod_usuario=" . $_GET['cod_usuario'] ."&";
      if (isset($_GET['cod_grupo'])) $urlbarra .= "cod_grupo=" . $_GET['cod_grupo'] ."&";
      if (isset($_GET['q'])) $urlbarra .= "q=" . $_GET['q'] ."&";
    }


    echo "<li " .$inicio_desabilitado. "><a class=\"page-link\" href=\"". $urlbarra . "p=1\" aria-label=\"Previous\">&laquo;</a></li>\n";
    echo "<li " .$inicio_desabilitado. "><a class=\"page-link\" href=\"". $urlbarra. "p=" .$p_anterior. "\" aria-label=\"Previous\">&laquo;</a></li>\n";
      
    // Botao "..." inicial
    if ($p > 3) {
        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"". $urlbarra ."p=" . ($p - 3) ."\">...</a></li>\n";
    }

    //botoes numerados
    if ($p < 4) {
        $p_botao_inicial = 1;
    } elseif (($p > 3) and ($p == $p_total)) {
        $p_botao_inicial = $p -4;
    } elseif (($p > 3) and ($p == ($p_total -1))) {
        $p_botao_inicial = $p -3;
    } elseif (($p > 3) and ($p < ($p_total -1))) {
        $p_botao_inicial = $p -2;
    }
        
    for ($i=$p_botao_inicial; $i < ($p_botao_inicial + 5); $i++) { 
        # code...
        if ($i <= $p_total) {
            echo "<li class=\"page-item ";
            if ($p == $i) echo " active";
            echo "\"><a class=\"page-link\" href=\"". $urlbarra . "p=" . $i ."\">$i ";
            
            echo "</a></li>\n";
        }
    }

    // Botao "..." final
    if (($p_total > 5) AND ($p_total - 2) > $p) {
        echo "<li  class=\"page-item\"><a class=\"page-link\" href=\"". $urlbarra ."p=";
        if ($p < 4) {
            echo "6";
        } else {
            echo ($p + 3);
        } 
        echo "\">...</a></li>\n";
    }
    
    echo " <li " .$fim_desabilitado. "><a class=\"page-link\" href=\"". $urlbarra ."p=" .$p_posterior. "\" aria-label=\"Next\">&raquo;</a></li>\n"; 
    echo " <li " .$fim_desabilitado. "><a class=\"page-link\" href=\"". $urlbarra ."p=" .$p_total. "\" aria-label=\"Next\">&raquo;</a></li>\n";
    echo "  </ul>\n </nav>\n</center>";
}

function primeiro_acesso($mysqli) {
    if ($stmt = $mysqli->prepare("SELECT senha 
                                  FROM adm_users 
                                  WHERE login = 'admin' LIMIT 1")) {
        $stmt->execute(); 
        $stmt->bind_result($password);
        $stmt->fetch();

        if (strlen($password) < 1) {
            // Senha em branco
            return true;
        } 
        return false;
    } 
    return false;
}
