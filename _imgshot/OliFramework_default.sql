-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Lun 24 Août 2015 à 15:02
-- Version du serveur :  5.5.42-cll
-- Version de PHP :  5.4.36

--
-- Database par defaut du Framework PHP Oli
-- Dévelopé par Matiboux (http://matiboux.com/)
--
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `OliFramework`
--

-- --------------------------------------------------------

--
-- Structure de la table `accounts`
--
-- Création :  Dim 26 Juillet 2015 à 10:34
-- Dernière modification :  Lun 24 Août 2015 à 12:13
-- Dernière vérification :  Dim 02 Août 2015 à 19:14
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `pseudonym` varchar(64) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `user_right` varchar(32) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `pseudonym`, `firstname`, `lastname`, `user_right`) VALUES
(1, 'admin', '$2y$10$yOB3kyL2l5QzO5sXxIJZXuQUzpRWrp/tTD4JM6AbJ50gsBHhE2HZ6', 'admin@oli.fr', '', '', '', 'OWNER'),
(2, 'admin2', '$2y$10$aWzOlJcNIUhpoW63Vm8/WOpo6nMaSB.uzJ6kgVKSo4f9jCewqzgvK', 'admin2@oli.fr', '', '', '', 'ADMIN'),
(3, 'user1', '$2y$10$FjXmv91X3fin3bjBRq1Ga.D6Apyq5KDnWnhxS..KJ0bKzQVSHxwPa', 'user1@oli.fr', '', '', '', 'USER'),
(4, 'user2', '$2y$10$mqPQ6wuZVUj5dJJ2wo94gOuvV/uifsDZeBUcDxuGS/uUjcnFsm0Sy', 'user2@oli.fr', '', '', '', 'USER');

-- --------------------------------------------------------

--
-- Structure de la table `accounts_rights`
--
-- Création :  Dim 26 Juillet 2015 à 10:34
-- Dernière modification :  Dim 26 Juillet 2015 à 10:34
-- Dernière vérification :  Dim 02 Août 2015 à 19:14
--

CREATE TABLE IF NOT EXISTS `accounts_rights` (
  `id` int(11) NOT NULL,
  `user_right` varchar(32) NOT NULL,
  `permissions` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `accounts_sessions`
--
-- Création :  Ven 14 Août 2015 à 13:12
-- Dernière modification :  Ven 14 Août 2015 à 13:12
--

CREATE TABLE IF NOT EXISTS `accounts_sessions` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `auth_key` varchar(256) NOT NULL,
  `user_ip` varchar(64) NOT NULL,
  `port` varchar(32) NOT NULL,
  `login_date` datetime NOT NULL,
  `expire_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--
-- Création :  Dim 26 Juillet 2015 à 10:34
-- Dernière modification :  Lun 24 Août 2015 à 12:13
-- Dernière vérification :  Dim 02 Août 2015 à 19:14
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` varchar(256) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'url', 'http://data.matiboux.com/_OliFramework/'),
(2, 'name', 'Oli Framework'),
(3, 'description', 'Framework PHP developped by Matiboux'),
(4, 'version', 'uitlHpamGQCj'),
(5, 'creation_date', '2015-02-06'),
(10, 'domain', 'matiboux.com');

-- --------------------------------------------------------

--
-- Structure de la table `shortcut_links`
--
-- Création :  Jeu 13 Août 2015 à 13:31
-- Dernière modification :  Dim 16 Août 2015 à 23:54
--

CREATE TABLE IF NOT EXISTS `shortcut_links` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `url` varchar(256) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `shortcut_links`
--

INSERT INTO `shortcut_links` (`id`, `name`, `url`) VALUES
(1, 'abc', 'http://abc.com/'),
(2, 'xyz', 'http://xyz.com/'),
(3, 'hello', 'world!');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `accounts_rights`
--
ALTER TABLE `accounts_rights`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `accounts_sessions`
--
ALTER TABLE `accounts_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `shortcut_links`
--
ALTER TABLE `shortcut_links`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT pour la table `accounts_rights`
--
ALTER TABLE `accounts_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `accounts_sessions`
--
ALTER TABLE `accounts_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT pour la table `shortcut_links`
--
ALTER TABLE `shortcut_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
