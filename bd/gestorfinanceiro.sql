-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 19-Maio-2013 às 11:55
-- Versão do servidor: 5.5.31
-- versão do PHP: 5.4.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `gestorfinanceiro`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `gf_contas`
--

CREATE TABLE IF NOT EXISTS `gf_contas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_grupo` bigint(20) unsigned NOT NULL,
  `str_nome` varchar(140) DEFAULT NULL,
  `num_valor` decimal(10,2) DEFAULT NULL,
  `dte_inicial` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dte_compra` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario` bigint(20) unsigned DEFAULT NULL,
  `bol_paga` TINYINT(1) NOT NULL DEFAULT 0,
  `id_relacao` bigint(20) unsigned,
  UNIQUE KEY `id` (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_grupo` (`id_grupo`),
  KEY `id_relacao` (`id_relacao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=201 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `gf_grupos`
--

CREATE TABLE IF NOT EXISTS `gf_grupos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `str_nome` varchar(140) DEFAULT NULL,
  `str_tipo` char(1) DEFAULT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL COMMENT 'Usuário criador do grupo',
  UNIQUE KEY `id` (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `gf_relacoes_usuarios`
--

CREATE TABLE IF NOT EXISTS `gf_relacoes_usuarios` (
  `id_usuario1` bigint(20) unsigned NOT NULL,
  `id_usuario2` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_usuario1`,`id_usuario2`),
  KEY `id_usuario2` (`id_usuario2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `gf_usuarios`
--

CREATE TABLE IF NOT EXISTS `gf_usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `str_nome` varchar(140) DEFAULT NULL,
  `str_login` varchar(140) DEFAULT NULL,
  `pas_senha` varchar(140) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `str_login` (`str_login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `gf_contas`
--
ALTER TABLE `gf_contas`
  ADD CONSTRAINT `gf_contas_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `gf_grupos` (`id`),
  ADD CONSTRAINT `gf_contas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `gf_usuarios` (`id`),
  ADD CONSTRAINT `gf_contas_ibfk_3` FOREIGN KEY (`id_relacao`) REFERENCES `gf_contas` (`id`);

--
-- Limitadores para a tabela `gf_grupos`
--
ALTER TABLE `gf_grupos`
  ADD CONSTRAINT `gf_grupos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `gf_usuarios` (`id`);

--
-- Limitadores para a tabela `gf_relacoes_usuarios`
--
ALTER TABLE `gf_relacoes_usuarios`
  ADD CONSTRAINT `gf_relacoes_usuarios_ibfk_2` FOREIGN KEY (`id_usuario2`) REFERENCES `gf_usuarios` (`id`),
  ADD CONSTRAINT `gf_relacoes_usuarios_ibfk_1` FOREIGN KEY (`id_usuario1`) REFERENCES `gf_usuarios` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Base de Dados: `gestorfinanceiro`
--

--
-- Extraindo dados da tabela `gf_usuarios`
--

INSERT INTO `gf_usuarios` (`id`, `str_nome`, `str_login`, `pas_senha`) VALUES
(1, 'Teste', 'teste', '<>VA08694ffb66c11558ea5c2cb36c7cce9d60d59b954KMB');
/*teste1*/

--
-- Extraindo dados da tabela `gf_grupos`
--

INSERT INTO `gf_grupos` (`str_nome`, `str_tipo`, `id_usuario`) VALUES
('Dízimo e Oferta', 's', 1),
('Outros', 's', 1),
('Tecnologia', 's', 1),
('Contas Fixas (Energia, Água, Telefone)', 's', 1),
('Roupa', 's', 1),
('Carro', 's', 1),
('Supermercado', 's', 1),
('Restaurante', 's', 1),
('Saúde', 's', 1),
('Presentes', 's', 1),
('Presentes', 'e', 1),
('Outros', 'e', 1),
('Salário', 'e', 1),
('Educação (Livros, Escola)', 's', 1),
('Jóias / Bijouterias', 's', 1),
('Mobília', 's', 1),
('Poupança', 's', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
