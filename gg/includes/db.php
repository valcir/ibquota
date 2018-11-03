<?php
/**
 * IBQUOTA 3
 * GG - Gerenciador Grafico do IBQUOTA
 * 
 * 30/10/2018 - Valcir C.
 *
 * Seguem os detalhes para acesso ao banco de dados
 * Obs.: Voce deve utilizar a mesma configuracao no arquivo Backend
 *
 */  
define("HOST", "localhost");     // Servidor com o qual voce quer se conectar.
define("USER", "ibquota");       // Usuário para acessar o banco de dados. 
define("PASSWORD", "ibquota");   // Senha de acesso ao banco de dados. 
define("DATABASE", "ibquota3");  // O nome do banco de dados. 


/**
* NAO ALTERAR 
*/ 

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
