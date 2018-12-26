SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ibquota3`
--
CREATE DATABASE IF NOT EXISTS ibquota3;
USE ibquota3;

-- --------------------------------------------------------

--
-- Estrutura da tabela `config_geral`
--

CREATE TABLE IF NOT EXISTS `config_geral` (
`id` int(11) NOT NULL,
  `base_local` int(10) unsigned NOT NULL,
  `LDAP_server` varchar(250) DEFAULT NULL,
  `LDAP_port` int(10) unsigned DEFAULT NULL,
  `LDAP_filter` varchar(500) DEFAULT NULL,
  `LDAP_base` varchar(250) DEFAULT NULL,
  `LDAP_user` varchar(250) DEFAULT NULL,
  `LDAP_password` varchar(250) DEFAULT NULL,
  `path_pkpgcounter` varchar(255) NOT NULL DEFAULT '/usr/bin/pkpgcounter',
  `path_python` varchar(255) NOT NULL DEFAULT '/usr/bin/python',
  `Debug` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `config_geral`
--

INSERT INTO `config_geral` (`id`, `base_local`, `LDAP_server`, `LDAP_port`, `LDAP_filter`, `LDAP_base`, `LDAP_user`, `LDAP_password`, `path_pkpgcounter`, `path_python`, `Debug`) VALUES
(1, 1, NULL, NULL, '(|(cn=$user)(samaccountname=$user)(uid=$user))', NULL, NULL, NULL, '/usr/bin/pkpgcounter', '/usr/bin/python', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos`
--

CREATE TABLE IF NOT EXISTS `grupos` (
`cod_grupo` int(10) unsigned NOT NULL,
  `grupo` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Estrutura da tabela `grupo_usuario`
--

CREATE TABLE IF NOT EXISTS `grupo_usuario` (
`cod_grupo_usuario` int(10) unsigned NOT NULL,
  `cod_grupo` int(10) unsigned NOT NULL,
  `cod_usuario` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Estrutura da tabela `impressoes`
--

CREATE TABLE IF NOT EXISTS `impressoes` (
`cod_impressoes` int(10) unsigned NOT NULL,
  `cod_status_impressao` int(10) unsigned NOT NULL,
  `impressora` varchar(150) NOT NULL,
  `usuario` varchar(150) NOT NULL,
  `data_impressao` date NOT NULL,
  `hora_impressao` time NOT NULL,
  `job_id` int(10) unsigned NOT NULL,
  `nome_documento` varchar(100) NOT NULL,
  `paginas` int(10) unsigned NOT NULL,
  `estacao` varchar(50) DEFAULT NULL,
  `cod_politica` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Estrutura da tabela `log_ibquota`
--

CREATE TABLE IF NOT EXISTS `log_ibquota` (
`id` int(11) NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `datahora` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `politicas`
--

CREATE TABLE IF NOT EXISTS `politicas` (
`cod_politica` int(10) unsigned NOT NULL,
  `nome` varchar(128) NOT NULL,
  `quota_acumulativa` tinyint(1) NOT NULL DEFAULT '0',
  `quota_infinita` tinyint(1) NOT NULL DEFAULT '0',
  `quota_padrao` float DEFAULT NULL,
  `prioridade` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `politica_grupo`
--

CREATE TABLE IF NOT EXISTS `politica_grupo` (
`cod_politica_grupo` int(10) unsigned NOT NULL,
  `cod_politica` int(10) unsigned NOT NULL,
  `grupo` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Estrutura da tabela `politica_impressora`
--

CREATE TABLE IF NOT EXISTS `politica_impressora` (
`cod_politica_impressora` int(10) unsigned NOT NULL,
  `cod_politica` int(10) unsigned NOT NULL,
  `impressora` varchar(150) NOT NULL,
  `prioridade` int(10) unsigned NOT NULL,
  `peso` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `printlogs`
--

CREATE TABLE IF NOT EXISTS `printlogs` (
`id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `printer` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `server` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `copies` int(11) NOT NULL,
  `pages` int(11) NOT NULL,
  `options` varchar(255) NOT NULL,
  `spoolfile` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Estrutura da tabela `quota_usuario`
--

CREATE TABLE IF NOT EXISTS `quota_usuario` (
`cod_quota_usuario` int(10) unsigned NOT NULL,
  `cod_politica` int(10) unsigned NOT NULL,
  `grupo` varchar(150) NOT NULL,
  `usuario` varchar(150) NOT NULL,
  `quota` float DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


--
-- Estrutura da tabela `quota_adicional`
--

CREATE TABLE IF NOT EXISTS `quota_adicional` (
`cod_quota_adicional` int(10) unsigned NOT NULL,
  `cod_politica` int(10) unsigned NOT NULL,
  `grupo` varchar(150) NOT NULL,
  `usuario` varchar(150) NOT NULL,
  `quota_adicional` float NOT NULL,
  `motivo` varchar(255) NULL,
  `datahora` datetime NOT NULL,
  `useradmin` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status_impressao`
--

CREATE TABLE IF NOT EXISTS `status_impressao` (
`cod_status_impressao` int(10) unsigned NOT NULL,
  `nome_status` varchar(100) NOT NULL,
  `erro` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `status_impressao`
--

INSERT INTO `status_impressao` (`cod_status_impressao`, `nome_status`, `erro`) VALUES
(1, 'OK - Impresso com sucesso', 0),
(2, 'Usuário não cadastrado', 1),
(3, 'Usuário sem quota', 1),
(4, 'Arquivo temporário de impressão não encontrado', 1),
(5, 'RESERVADO', 1),
(6, 'RESERVADO', 1),
(7, 'Não foi possível determinar quantidade de páginas', 1),
(8, 'Usuario com Quota insuficiente', 1),
(9, 'Nao ha politica de impressao para esta impressora', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
`cod_usuario` int(10) unsigned NOT NULL,
  `usuario` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Estrutura da tabela `adm_users`
--

CREATE TABLE IF NOT EXISTS `adm_users` (
`cod_adm_users` int(11) NOT NULL,
  `nome` varchar(50),
  `login` varchar(50) NOT NULL,
  `email` varchar(100),
  `senha` varchar(64),
  `permissao` int(11)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `adm_users`
--

INSERT INTO `adm_users` (`cod_adm_users`, `nome`, `login`, `email`, `senha`,`permissao`) VALUES
(1, 'Admin IBQUOTA', 'admin', '', '', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `config_geral`
--
ALTER TABLE `config_geral`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `grupos`
--
ALTER TABLE `grupos`
 ADD PRIMARY KEY (`cod_grupo`), ADD UNIQUE KEY `idx_grupoUnico` (`grupo`);

--
-- Indexes for table `grupo_usuario`
--
ALTER TABLE `grupo_usuario`
 ADD PRIMARY KEY (`cod_grupo_usuario`), ADD KEY `grupo_usuario_` (`cod_grupo_usuario`);

--
-- Indexes for table `impressoes`
--
ALTER TABLE `impressoes`
 ADD PRIMARY KEY (`cod_impressoes`), ADD KEY `impressoes_FKIndex1` (`usuario`), ADD KEY `impressoes_FKIndex2` (`impressora`), ADD KEY `impressoes_FKIndex3` (`cod_status_impressao`), ADD KEY `impressoes_FKIndex5` (`cod_politica`);

--
-- Indexes for table `log_ibquota`
--
ALTER TABLE `log_ibquota`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `politicas`
--
ALTER TABLE `politicas`
 ADD PRIMARY KEY (`cod_politica`), ADD KEY `politica_FKIndex1` (`cod_politica`);

--
-- Indexes for table `politica_grupo`
--
ALTER TABLE `politica_grupo`
 ADD PRIMARY KEY (`cod_politica_grupo`), ADD KEY `idx_politica_grupo` (`grupo`);

--
-- Indexes for table `politica_impressora`
--
ALTER TABLE `politica_impressora`
 ADD PRIMARY KEY (`cod_politica_impressora`), ADD KEY `idx_politica_Impressora` (`impressora`);

--
-- Indexes for table `printlogs`
--
ALTER TABLE `printlogs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quota_usuario`
--
ALTER TABLE `quota_usuario`
 ADD PRIMARY KEY (`cod_quota_usuario`), ADD KEY `quota_usuario_FKIndex1` (`cod_politica`), ADD KEY `quota_usuario_FKIndex2` (`grupo`);

--
-- Indexes for table `quota_adicional`
--
ALTER TABLE `quota_adicional` ADD PRIMARY KEY (`cod_quota_adicional`);

--
-- Indexes for table `status_impressao`
--
ALTER TABLE `status_impressao`
 ADD PRIMARY KEY (`cod_status_impressao`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
 ADD PRIMARY KEY (`cod_usuario`), ADD UNIQUE KEY `idx_usuarioUnico` (`usuario`);

--
-- Indexes for table `adm_users`
--
ALTER TABLE `adm_users`
 ADD PRIMARY KEY (`cod_adm_users`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `config_geral`
--
ALTER TABLE `config_geral`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `grupos`
--
ALTER TABLE `grupos`
MODIFY `cod_grupo` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `grupo_usuario`
--
ALTER TABLE `grupo_usuario`
MODIFY `cod_grupo_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `impressoes`
--
ALTER TABLE `impressoes`
MODIFY `cod_impressoes` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `log_ibquota`
--
ALTER TABLE `log_ibquota`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `politicas`
--
ALTER TABLE `politicas`
MODIFY `cod_politica` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `politica_grupo`
--
ALTER TABLE `politica_grupo`
MODIFY `cod_politica_grupo` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `politica_impressora`
--
ALTER TABLE `politica_impressora`
MODIFY `cod_politica_impressora` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `printlogs`
--
ALTER TABLE `printlogs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `quota_usuario`
--
ALTER TABLE `quota_usuario`
MODIFY `cod_quota_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `quota_adicional`
--
ALTER TABLE `quota_adicional`
MODIFY `cod_quota_adicional` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `status_impressao`
--
ALTER TABLE `status_impressao`
MODIFY `cod_status_impressao` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
MODIFY `cod_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;


--
-- AUTO_INCREMENT for table `adm_users`
--
ALTER TABLE `adm_users` 
MODIFY `cod_adm_users` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
