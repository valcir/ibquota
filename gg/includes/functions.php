<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 01/11/2018 - Valcir C.
 *
 * Funcoes definidas para utilizacao no GG
 */  


include_once 'db.php';


function sec_session_start() {
    $session_name = 'sec_session_id';   
    $secure = SECURE;    // Impede JavaScript de acessar identificacao da sessao.
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
    if ($stmt = $mysqli->prepare("SELECT cod_adm_users, nome, senha, premissao
        FROM adm_users
        WHERE login = ? LIMIT 1")) {
        $stmt->bind_param('s', $login);
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($user_id, $nome, $db_password, $premissao );
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
            $_SESSION['premissao'] = $premissao;

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
