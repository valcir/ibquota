<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 29/11/2018 - Valcir C.
 *
 * Funcoes de acesso a Diretorio via LDAP
 *
 * Colaboracao do Bruno X. e Allan F.
 * INTRANET do IB/Unicamp
 */

/**
 * Conecta com o Diretorio via ldap
 * @return resource - Conexao com o ldap
 */

function ibquota_ldap_connect() {
   $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
   $stmt = $mysqli->prepare("SELECT LDAP_server,LDAP_port
                              FROM config_geral WHERE id = 1 LIMIT 1");
   $stmt->execute();
   $stmt->bind_result($ldap_server,$ldap_porta);
   $stmt->fetch();
   
   if ($ldap_porta == 0) {
      $ldap_porta = 389;
   } 
   if ($conn = ldap_connect($ldap_server,$ldap_porta)) {
      if (ldap_errno($conn) == 0) {
         if (ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3)) {
            if (ldap_errno($conn) == 0) {
               if (ldap_set_option($conn, LDAP_OPT_REFERRALS, 0)) {
                  if (ldap_errno($conn) == 0) {
                     if (ldap_bind($conn)) {
                         if (ldap_errno($conn) == 0) {
                            return $conn;
                          }
                     }
                  }
               }
            }
         }
      }
   }
   return false;
}


/**
 * Efetua o bind no servidor de Diretorio
 * @param $conn resource - Conexao com o ldap
 * @return bool - Retorna true se efetuou o bind com sucesso ou false se nao
 */
function ibquota_ldap_bind($conn) {
   $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
   $stmt = $mysqli->prepare("SELECT LDAP_user, LDAP_password
                              FROM config_geral WHERE id = 1 LIMIT 1");
   $stmt->execute();
   $stmt->bind_result($ldap_usuario, $ldap_senha);
   $stmt->fetch();

   // Super MiGUE do Bruno: 3xBASE64
   // Sabe fazer melhor? Queremos saber sua sugestão...
   $senha = base64_decode(base64_decode(base64_decode($ldap_senha)));

   //Verifica se efetua o bind corretamente
   if ((ldap_bind($conn, $ldap_usuario, $ldap_senha))
        && (ldap_errno($conn) == 0)) {
            $return = true;
    } else {
        $return = false;
    }
    return $return;
}


/**
 * Busca qtde usuários
 * 
 * @return int - Qtde de usuarios na base.
 */
function ibquota_ldap_qtde_usuarios($conn,$ldap_base,$ldap_filtro) {
    $filtro = str_replace('$user', "*", $ldap_filtro); 
    $search = ldap_search($conn, $ldap_base,$filtro, array('cn'));
    $info = ldap_get_entries($conn, $search);
    return $info["count"];
}

/**
 * Busca qtde Grupos
 * 
 * @return int - Qtde de grupos na base.
 */
function ibquota_ldap_qtde_grupos($conn,$ldap_base,$ldap_filtro) {
    $filtro = "(&(cn=*)(|(memberof=*)(member=*))(|(objectClass=group)(objectClass=groupOfNames)))";
    $search = ldap_search($conn, $ldap_base,$filtro, array('cn'));
    $info = ldap_get_entries($conn, $search);
    return $info["count"];
}


?>
