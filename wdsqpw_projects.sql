-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Sam 22 Octobre 2016 à 01:27
-- Version du serveur :  5.6.25-1~dotdeb+7.1
-- Version de PHP :  5.6.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `wdsqpw_projects`
--

-- --------------------------------------------------------

--
-- Structure de la table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `birthday` date DEFAULT NULL,
  `register_date` datetime NOT NULL,
  `user_right` varchar(32) NOT NULL,
  `language` varchar(32) NOT NULL,
  `admin_note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `birthday`, `register_date`, `user_right`, `language`, `admin_note`) VALUES
(1, 'Matiboux', '$2y$10$vUFTbrBzV1wxtRot9NxEQeOJzHmSVtZ63OgDuHyfixGtd/6vyAwy.', 'matiboux@gmail.com', '2000-10-20', '2016-01-13 17:21:35', 'OWNER', 'en', 'Eli\'s babe');

-- --------------------------------------------------------

--
-- Structure de la table `accounts_infos`
--

CREATE TABLE `accounts_infos` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `pseudonym` varchar(64) NOT NULL,
  `nickname` varchar(64) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `displayed_name` varchar(32) NOT NULL,
  `add_pseudonym` tinyint(1) NOT NULL,
  `gender` varchar(32) NOT NULL,
  `biography` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `accounts_infos`
--

INSERT INTO `accounts_infos` (`id`, `username`, `pseudonym`, `nickname`, `firstname`, `lastname`, `displayed_name`, `add_pseudonym`, `gender`, `biography`) VALUES
(1, 'Matiboux', 'Matiboux', 'Mati', 'Mathieu', 'Guérin', 'fullname', 1, 'male', 'Sad boy who made this entire website with his useless hands.\r\nI have hidden some things in it for my only one. ');

-- --------------------------------------------------------

--
-- Structure de la table `accounts_requests`
--

CREATE TABLE `accounts_requests` (
  `id` bigint(20) NOT NULL,
  `username` varchar(64) NOT NULL,
  `activate_key` varchar(256) NOT NULL,
  `action` varchar(64) NOT NULL,
  `request_date` datetime NOT NULL,
  `expire_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `accounts_rights`
--

CREATE TABLE `accounts_rights` (
  `id` int(11) NOT NULL,
  `user_right` varchar(64) NOT NULL,
  `acronym` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `permissions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `accounts_rights`
--

INSERT INTO `accounts_rights` (`id`, `user_right`, `acronym`, `name`, `permissions`) VALUES
(0, 'VISITOR', '', 'Visitor', '{"0":"no","1":"permissions","2":"yet"}'),
(1, 'NEW-USER', 'NEW', 'New user', '{"0":"no","1":"permissions","2":"yet"}'),
(2, 'BANNED', 'BAN', 'Banned user', '{"0":"no","1":"permissions","2":"yet"}'),
(3, 'USER', '', 'Regular user', '{"0":"no","1":"permissions","2":"yet"}'),
(4, 'VIP', '', 'VIP user', '{"0":"no","1":"permissions","2":"yet"}'),
(5, 'MODERATOR', 'MOD', 'Moderator', '{"0":"no","1":"permissions","2":"yet"}'),
(6, 'ADMINISTRATOR', 'ADMIN', 'Administrator', '{"0":"no","1":"permissions","2":"yet"}'),
(7, 'OWNER', '', 'Owner', '*');

-- --------------------------------------------------------

--
-- Structure de la table `accounts_sessions`
--

CREATE TABLE `accounts_sessions` (
  `id` bigint(20) NOT NULL,
  `username` varchar(64) NOT NULL,
  `auth_key` varchar(256) NOT NULL,
  `user_ip` varchar(64) NOT NULL,
  `port` varchar(32) NOT NULL,
  `login_date` datetime NOT NULL,
  `expire_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `last_seen_page` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `accounts_sessions`
--

INSERT INTO `accounts_sessions` (`id`, `username`, `auth_key`, `user_ip`, `port`, `login_date`, `expire_date`, `update_date`, `last_seen_page`) VALUES
(2, 'Matiboux', '047c64d0c9e4bf4f02243c7e6b8b200ad02c7c6f', '176.151.9.138', '', '2016-09-11 12:25:15', '2016-09-26 12:25:15', '2016-09-11 14:45:47', 'http://projects.matiboux.com//favicon.ico'),
(3, 'Matiboux', '1de513a385df793d84f394446f1c2f03737561c3', '89.91.144.175', '', '2016-09-11 20:51:33', '2016-09-26 20:51:33', '2016-09-23 18:21:48', 'http://accounts.matiboux.com//login/logout'),
(4, 'Matiboux', 'b1a1a494747eb7fa41f5d1fd6285819a99ddbc48', '176.151.9.138', '', '2016-09-17 12:21:32', '2016-10-02 12:21:32', '2016-09-18 03:13:52', 'http://accounts.matiboux.com//login/logout'),
(5, 'Matiboux', 'e34b43221ea9024d706c6652090fa17c16839430', '176.151.9.138', '', '2016-09-18 03:14:16', '2016-10-03 03:14:16', '2016-09-18 03:30:35', 'http://accounts.matiboux.com//login/logout'),
(6, 'Matiboux', '4e2c8fe30cd74299f15e7b9fbc59127e397bfc58', '176.151.9.138', '', '2016-09-18 03:30:52', '2016-10-03 03:30:52', '2016-09-18 16:08:25', 'http://projects.matiboux.com//status'),
(7, 'Matiboux', '95faceebbf2b9286ef0c7e271858da48e4d376a3', '89.91.144.175', '', '2016-09-23 18:21:56', '2016-10-08 18:21:56', '2016-10-08 10:04:21', 'http://keygen.matiboux.com//_keygen.php');

-- --------------------------------------------------------

--
-- Structure de la table `keygen_settings`
--

CREATE TABLE `keygen_settings` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `default_keygen_mode` varchar(64) NOT NULL,
  `default_hash_mode` varchar(64) NOT NULL,
  `use_default_inputs` tinyint(1) NOT NULL,
  `keep_history` tinyint(1) NOT NULL,
  `keygen_inputs` text NOT NULL,
  `hash_inputs` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `keygen_settings`
--

INSERT INTO `keygen_settings` (`id`, `username`, `default_keygen_mode`, `default_hash_mode`, `use_default_inputs`, `keep_history`, `keygen_inputs`, `hash_inputs`) VALUES
(1, 'Matiboux', '', '', 0, 0, '{}', '{}');

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'url', 'matiboux.net/'),
(2, 'name', 'Matiboux'),
(3, 'description', 'Matiboux\'s website'),
(4, 'media_path', 'content/media/'),
(5, 'theme_path', 'content/theme/'),
(6, 'force_https', '0'),
(7, 'version', 'alpha'),
(8, 'creation_date', ''),
(9, 'status', 'standby'),
(10, 'auth_key_cookie_name', 'ProjectsAuthKey'),
(11, 'owner', 'Matiboux');

-- --------------------------------------------------------

--
-- Structure de la table `settings_accounts`
--

CREATE TABLE `settings_accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_accounts`
--

INSERT INTO `settings_accounts` (`id`, `name`, `value`) VALUES
(1, 'url', 'accounts.matiboux.com/'),
(2, 'name', 'Matiboux Accounts'),
(3, 'description', 'Account management and personnal dashboard'),
(5, 'theme_path', 'content/accounts/'),
(6, 'force_https', '0'),
(7, 'version', 'null'),
(8, 'creation_date', '2016-08-03'),
(9, 'status', 'standby');

-- --------------------------------------------------------

--
-- Structure de la table `settings_admin`
--

CREATE TABLE `settings_admin` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_admin`
--

INSERT INTO `settings_admin` (`id`, `name`, `value`) VALUES
(1, 'url', 'admin.matiboux.com/'),
(2, 'name', 'Admin Panel'),
(3, 'description', 'Admin tools to manage websites, users and content'),
(4, 'force_https', '0'),
(5, 'version', 'null'),
(6, 'creation_date', '2016-08-03'),
(7, 'status', 'not_available');

-- --------------------------------------------------------

--
-- Structure de la table `settings_draws`
--

CREATE TABLE `settings_draws` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_draws`
--

INSERT INTO `settings_draws` (`id`, `name`, `value`) VALUES
(1, 'url', 'draws.matiboux.com/'),
(2, 'name', 'Matiboux Draws'),
(3, 'description', 'My drawings on a simple blog'),
(4, 'force_https', '0'),
(5, 'version', ''),
(6, 'creation_date', ''),
(7, 'status', 'not_available');

-- --------------------------------------------------------

--
-- Structure de la table `settings_eli`
--

CREATE TABLE `settings_eli` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_eli`
--

INSERT INTO `settings_eli` (`id`, `name`, `value`) VALUES
(1, 'url', 'eli.matiboux.com/'),
(2, 'name', 'Eli...'),
(3, 'description', '“The only one”'),
(4, 'force_https', '0'),
(5, 'version', '1.0'),
(6, 'creation_date', '2016-09-07'),
(7, 'status', 'available');

-- --------------------------------------------------------

--
-- Structure de la table `settings_imgshot`
--

CREATE TABLE `settings_imgshot` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_imgshot`
--

INSERT INTO `settings_imgshot` (`id`, `name`, `value`) VALUES
(1, 'url', 'imgshot.matiboux.com/'),
(2, 'name', 'ImgShot'),
(3, 'description', 'A simple image hosting service'),
(5, 'theme_path', 'content/imgshot/'),
(6, 'force_https', '0'),
(7, 'version', '2.3.0-dev'),
(8, 'creation_date', '2014-07-26'),
(9, 'status', 'not_available');

-- --------------------------------------------------------

--
-- Structure de la table `settings_keygen`
--

CREATE TABLE `settings_keygen` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_keygen`
--

INSERT INTO `settings_keygen` (`id`, `name`, `value`) VALUES
(1, 'url', 'keygen.matiboux.com/'),
(2, 'name', 'Keygen'),
(3, 'description', 'A password and hashes generator'),
(5, 'theme_path', 'content/keygen/'),
(6, 'force_https', '0'),
(7, 'version', '2.2.0-dev'),
(8, 'creation_date', '2014-07-30'),
(9, 'status', 'standby');

-- --------------------------------------------------------

--
-- Structure de la table `settings_ncloud`
--

CREATE TABLE `settings_ncloud` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_ncloud`
--

INSERT INTO `settings_ncloud` (`id`, `name`, `value`) VALUES
(1, 'url', 'ncloud.matiboux.com/'),
(2, 'name', 'Natrox Cloud'),
(3, 'description', 'Natrox cloud service for online file hosting '),
(4, 'force_https', '0'),
(5, 'version', '1.2'),
(6, 'creation_date', '2015-11-27'),
(7, 'status', 'standby');

-- --------------------------------------------------------

--
-- Structure de la table `settings_oli`
--

CREATE TABLE `settings_oli` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_oli`
--

INSERT INTO `settings_oli` (`id`, `name`, `value`) VALUES
(1, 'url', 'oliframework.github.io/Oli/'),
(2, 'name', 'Oli Framework'),
(3, 'description', 'An open source PHP framework made to help web developers creating their website'),
(4, 'force_https', '1'),
(5, 'version', 'BETA 1.7.1 (dev)'),
(6, 'creation_date', '2014-11-16'),
(7, 'status', 'standby');

-- --------------------------------------------------------

--
-- Structure de la table `settings_projects`
--

CREATE TABLE `settings_projects` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_projects`
--

INSERT INTO `settings_projects` (`id`, `name`, `value`) VALUES
(1, 'url', 'projects.matiboux.com/'),
(2, 'name', 'Matiboux Projects'),
(3, 'description', 'Matiboux\'s little projects world center'),
(5, 'theme_path', 'content/projects/'),
(6, 'force_https', '0'),
(7, 'version', '1.0.3'),
(8, 'creation_date', '2016-07-31'),
(9, 'status', 'standby');

-- --------------------------------------------------------

--
-- Structure de la table `settings_random`
--

CREATE TABLE `settings_random` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_random`
--

INSERT INTO `settings_random` (`id`, `name`, `value`) VALUES
(1, 'url', 'random.matiboux.com/'),
(2, 'name', 'Random'),
(3, 'description', 'Number generator'),
(4, 'force_https', '0'),
(5, 'version', ''),
(6, 'creation_date', ''),
(7, 'status', 'not_available');

-- --------------------------------------------------------

--
-- Structure de la table `settings_social`
--

CREATE TABLE `settings_social` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_social`
--

INSERT INTO `settings_social` (`id`, `name`, `value`) VALUES
(1, 'url', 'social.matiboux.com/'),
(2, 'name', 'Matiboux Social'),
(3, 'description', 'Small social network made for its users'),
(4, 'force_https', '0'),
(5, 'version', '1.1'),
(6, 'creation_date', '2016-03-06'),
(7, 'status', 'standby');

-- --------------------------------------------------------

--
-- Structure de la table `settings_urlshortener`
--

CREATE TABLE `settings_urlshortener` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings_urlshortener`
--

INSERT INTO `settings_urlshortener` (`id`, `name`, `value`) VALUES
(1, 'url', 'u.matiboux.com/'),
(2, 'name', 'Url Shortener'),
(3, 'description', 'A simple url shortener service\n'),
(5, 'theme_path', 'content/urlshortener/'),
(6, 'force_https', '0'),
(7, 'version', '1.1.0'),
(8, 'creation_date', '2016-02-29'),
(9, 'status', 'standby');

-- --------------------------------------------------------

--
-- Structure de la table `shortcut_links`
--

CREATE TABLE `shortcut_links` (
  `id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(256) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `shortcut_links`
--

INSERT INTO `shortcut_links` (`id`, `name`, `url`) VALUES
(1, 'projects', 'http://projects.matiboux.com/'),
(2, 'accounts', 'http://accounts.matiboux.com/'),
(3, 'admin', 'http://admin.matiboux.com/'),
(4, 'login', 'http://accounts.matiboux.com/login/'),
(5, 'cdn', 'http://cdn.matiboux.com/');

-- --------------------------------------------------------

--
-- Structure de la table `translations`
--

CREATE TABLE `translations` (
  `id` bigint(11) NOT NULL,
  `en` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `fr` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `br` varchar(256) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `translations`
--

INSERT INTO `translations` (`id`, `en`, `fr`, `br`) VALUES
(1, 'Hello World!', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `url_shortener_list`
--

CREATE TABLE `url_shortener_list` (
  `id` int(11) NOT NULL,
  `link` varchar(256) NOT NULL,
  `rating` varchar(64) NOT NULL,
  `owner` varchar(64) NOT NULL,
  `date` datetime NOT NULL,
  `link_key` varchar(64) NOT NULL,
  `views` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `url_shortener_list`
--

INSERT INTO `url_shortener_list` (`id`, `link`, `rating`, `owner`, `date`, `link_key`, `views`) VALUES
(1, 'http://projects.matiboux.com/', 'general', 'Matiboux', '2016-09-17 21:15:47', 'BXOVT', 0),
(2, 'http://keygen.matiboux.com/', 'general', 'Matiboux', '2016-09-17 21:16:13', 'ZIJHC', 0),
(3, 'http://dyjix.eu', 'general', '', '2016-09-17 21:17:26', '0WMLY', 0),
(4, 'https://oliframework.github.io/Oli/', 'general', 'Matiboux', '2016-09-18 19:22:50', 'olifw', 0),
(5, 'http://tropdeporntueleporn.fr', 'adult', '', '2016-09-19 16:11:43', 'MI44Z', 0),
(6, 'https://www.sofsole.com', 'general', '', '2016-09-19 23:32:59', 'TVQJA', 0),
(7, 'https://www.youtube.com/watch?v=moahKw4dlDA', 'general', '', '2016-09-21 22:57:39', 'JI7GZ', 0),
(8, 'http://abernathynames.xyz/?q=deal-sale-harris-communications-dvd076-the-tomie-depaola-library-dvd-buy-now&id=hrsc326&f=561', 'general', '', '2016-09-22 22:01:14', 'Z4E03', 0),
(9, 'http://abernathylastnameorigin.xyz/?q=alvarado-family-tree-cortright-the-awesome-sale&id=57be5b62da7ca&f=700', 'general', '', '2016-09-24 03:16:33', 'ZQGIV', 0),
(10, 'http://abneynameorigin.xyz/?q=best-buy-decalgirl-lci4-innertube-lifeproof-iphone-4-case-skin-inner-tube-girls-sale&id=dcgrl174983&f=468', 'general', '', '2016-09-25 08:25:19', '5CQ2C', 0),
(11, 'http://u.matiboux.com/', 'general', '', '2016-10-10 17:42:46', 'W96I1', 0);

-- --------------------------------------------------------

--
-- Structure de la table `url_shortener_settings`
--

CREATE TABLE `url_shortener_settings` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `delay` tinyint(1) NOT NULL,
  `rating` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `url_shortener_settings`
--

INSERT INTO `url_shortener_settings` (`id`, `username`, `delay`, `rating`) VALUES
(1, 'Matiboux', 1, 'adult');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `accounts_infos`
--
ALTER TABLE `accounts_infos`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `accounts_requests`
--
ALTER TABLE `accounts_requests`
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
-- Index pour la table `keygen_settings`
--
ALTER TABLE `keygen_settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_accounts`
--
ALTER TABLE `settings_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_admin`
--
ALTER TABLE `settings_admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_draws`
--
ALTER TABLE `settings_draws`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_eli`
--
ALTER TABLE `settings_eli`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_imgshot`
--
ALTER TABLE `settings_imgshot`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_keygen`
--
ALTER TABLE `settings_keygen`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_ncloud`
--
ALTER TABLE `settings_ncloud`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_oli`
--
ALTER TABLE `settings_oli`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_projects`
--
ALTER TABLE `settings_projects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_random`
--
ALTER TABLE `settings_random`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_social`
--
ALTER TABLE `settings_social`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings_urlshortener`
--
ALTER TABLE `settings_urlshortener`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `shortcut_links`
--
ALTER TABLE `shortcut_links`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `url_shortener_list`
--
ALTER TABLE `url_shortener_list`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `url_shortener_settings`
--
ALTER TABLE `url_shortener_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `accounts_requests`
--
ALTER TABLE `accounts_requests`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `accounts_rights`
--
ALTER TABLE `accounts_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `accounts_sessions`
--
ALTER TABLE `accounts_sessions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `keygen_settings`
--
ALTER TABLE `keygen_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `settings_accounts`
--
ALTER TABLE `settings_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `settings_admin`
--
ALTER TABLE `settings_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `settings_draws`
--
ALTER TABLE `settings_draws`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `settings_eli`
--
ALTER TABLE `settings_eli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `settings_imgshot`
--
ALTER TABLE `settings_imgshot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `settings_keygen`
--
ALTER TABLE `settings_keygen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `settings_ncloud`
--
ALTER TABLE `settings_ncloud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `settings_oli`
--
ALTER TABLE `settings_oli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `settings_projects`
--
ALTER TABLE `settings_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `settings_random`
--
ALTER TABLE `settings_random`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `settings_social`
--
ALTER TABLE `settings_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `settings_urlshortener`
--
ALTER TABLE `settings_urlshortener`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `shortcut_links`
--
ALTER TABLE `shortcut_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `url_shortener_list`
--
ALTER TABLE `url_shortener_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `url_shortener_settings`
--
ALTER TABLE `url_shortener_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
