-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Sam 22 Octobre 2016 à 01:28
-- Version du serveur :  5.6.25-1~dotdeb+7.1
-- Version de PHP :  5.6.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `wdsqpw_socialdb`
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
(1, 'Matiboux', '$2y$10$jlO4AWR2Qk4hK6RjRo4HcOoo0N/hTgtuT6xTDUjclkt3u0Z7qifN.', 'matiboux@gmail.com', '2000-10-20', '2016-06-08 17:54:32', 'OWNER', 'fr', 'Eli\'s babe'),
(2, 'Dav', '$2y$10$oQ8lYQ0JCud.g22E.p200u2C18ZOMffUSQhHoJUmhLB0Vho7BgH3G', 'david.tougeron@laposte.net', NULL, '2016-06-08 20:58:43', 'USER', 'fr', ''),
(3, 'felixjules', '$2y$10$hD1yngoVpm3a8eEHI5aI3.UCl5OivcOrm2fATLHff36XZ8x/Oa7mi', 'felixsauvourel@laposte.net', '2001-07-10', '2016-06-10 21:22:09', 'USER', 'fr', ''),
(4, 'swan2000', '$2y$10$mpzBquNUy6kOWYCCfa6WuOM1Yjmu8cNa79o3e9fGu/JQexkBJ3Z3e', 'swan.fruitet@gmail.com', NULL, '2016-06-10 22:55:19', 'USER', '', ''),
(5, 'TheKiller678', '$2y$10$Nqzw/Q8lTH6mkpPsZOxk1eas7Hkp9PRiykwvTVzjdYhDpt8LjBdpS', 'gabri18@free.fr', '2001-10-02', '2016-06-15 18:58:45', 'USER', 'fr', 'Le gabite'),
(6, 'Eliotitto', '$2y$10$DEZHatu3y4M1q6t7EwOPlu/Oka7CzSMVcOVCGZ5NZuwKaZnNbv8yq', 'eliott@dyjix.eu', '1999-08-24', '2016-06-19 18:19:12', 'VIP', 'fr', 'Mati\'s babe'),
(7, 'glenn', '$2y$10$rI7wvu5/tKuMzFeJkdsKyO2R50XxEaV07wqvyS6k03.cVOqZg6H6a', 'glennmaeltymen@yahoo.fr', '1997-03-21', '2016-06-20 13:51:02', 'USER', 'fr', ''),
(8, 'Fred', '$2y$10$DbYhJcpydJHX6mwdMDvGd.KmKieXonvv7q.pCzx43FLsZ8bRfzA4u', 'frederique.retail@gmail.com', NULL, '2016-07-05 22:57:19', 'USER', 'fr', ''),
(9, 'Herve', '$2y$10$bQ2aRgsxGYZyKhZWqPkmT.aZfAnldciyZHw.TvYNObgeJj3lxHGdO', 'rve.guerin@gmail.com', NULL, '2016-07-05 23:04:33', 'USER', 'fr', ''),
(10, 'Maatiboux', '$2y$10$./nwXSUIV8Gcu0O1P58BXuxOdoIU2tDGG7qD8R7rCgRgauNKzDmFW', 'maatiboux@gmail.com', '2000-10-20', '2016-07-07 04:30:07', 'USER', 'fr', ''),
(11, 'Titouan', '$2y$10$KehfFA//AsNulLKgHWdHZ.TrjKjDMJYaA/KSQ5zoqIswynjqE9yhK', 'titouan.pasquier@laposte.net', '2000-08-13', '2016-07-10 12:10:19', 'USER', 'en', ''),
(12, 'hexicans', '$2y$10$lWoQum.9ZVj2ihUP3G3iruNG.rVatCNYM1pi/B6Y3pu6AdqKSxiOG', 'contact@hexicans.eu', NULL, '2016-08-04 10:37:02', 'USER', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `accounts_infos`
--

CREATE TABLE `accounts_infos` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `biography` text NOT NULL,
  `gender` varchar(32) NOT NULL,
  `job` varchar(64) NOT NULL,
  `location` varchar(256) NOT NULL,
  `website` varchar(256) NOT NULL,
  `show_activity` tinyint(1) NOT NULL,
  `hide_announce` tinyint(1) NOT NULL,
  `hide_major_announce` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `accounts_infos`
--

INSERT INTO `accounts_infos` (`id`, `username`, `name`, `biography`, `gender`, `job`, `location`, `website`, `show_activity`, `hide_announce`, `hide_major_announce`) VALUES
(1, 'Matiboux', 'Mati', 'Matiboux™\r\nDeveloper, Drawer and a dang Gamer. I always been a weird furry dog.\r\nI miss him..', 'male', 'Developer, Drawer & Gamer', 'somewhere', 'http://matiboux.com/', 1, 0, 0),
(2, 'Dav', 'Dav', 'j\'aime bien les nudistes en calaçon', 'male', 'Futur Chomeur', '', '', 0, 0, 0),
(3, 'felixjules', 'felixjules', 'Viva Britagna', 'male', 'Bed tester', 'Ikea Land', 'Prout.com', 1, 0, 0),
(4, 'Swan Fruitet', '', '', '', '', '', '', 0, 0, 0),
(5, 'TheKiller678', 'TheKiller678', 'Best meth of the world. Better than Heisenberg\'s', 'male', 'a dealer', 'your ass', 'http://www.mybabyblue.com', 0, 0, 0),
(6, 'Eliotitto', 'Eliott', 'CEO @ Natrox & @ Dyjix', 'male', 'Studient', 'Caen', '', 1, 0, 0),
(7, 'glenn', 'glenn', '', 'male', 'Administrateur Reseau', 'paris', '', 1, 0, 0),
(8, 'Fred', 'Fred', '', 'female', '', 'Nantes', '', 0, 0, 0),
(9, 'Herve', 'Hervé', '', 'male', '', 'Nantes', '', 0, 0, 0),
(10, 'Maatiboux', 'Matiboux²', 'Comme sur Twitter, un deuxième Mati plus con que l\'autre :D', 'male', '', 'Inside @Matiboux', '', 0, 0, 0),
(11, 'Titouan', 'Titouan', 'J\'aime les gros pistons ', 'male', 'Mécanisien ', '', '', 0, 0, 0),
(12, 'hexicans', '', '', '', '', '', '', 0, 0, 0);

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

--
-- Contenu de la table `accounts_requests`
--

INSERT INTO `accounts_requests` (`id`, `username`, `activate_key`, `action`, `request_date`, `expire_date`) VALUES
(1, 'Matiboux', 'ojZXkI', 'change-password', '2016-09-08 00:10:32', '2016-09-11 00:10:32');

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
(0, 'NEW-USER', 'NEW', 'Nouveau', '{"0":"no","1":"permissions","2":"yet"}'),
(1, 'BANNED', 'BAN', 'Banni', '{"0":"no","1":"permissions","2":"yet"}'),
(2, 'USER', '', 'Utilisateur', '{"0":"no","1":"permissions","2":"yet"}'),
(3, 'VIP', '', 'Privilégié', '{"0":"no","1":"permissions","2":"yet"}'),
(4, 'MODERATOR', 'MOD', 'Modérateur', '{"0":"no","1":"permissions","2":"yet"}'),
(5, 'ADMIN', '', 'Admin', '{"0":"no","1":"permissions","2":"yet"}'),
(6, 'OWNER', '', 'Fondateur', '*');

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
(4, 'felixjules', 'UoZOW9v4P6lvTu9scP4Jx4BMj1NCiKat4fqG7ezjDOHJioyXdaFL7PXzLc4yGuvPSEjMkwsN49zWBLAuWJNeQcbcByna9KAyJBuI', '88.172.64.179', '', '2016-07-11 00:17:56', '2016-07-26 00:17:56', '2016-07-16 14:47:23', 'favicon.ico'),
(7, 'TheKiller678', 'n9fHIaT9HOWDxD9wkM0uFG49J0tAfO3WYtBXgFjr3ZR7zPd71kVicuEohu9iuaLLEAh7jEvYmHUmuttioEy6LNRW1aAofq3jDbgi', '88.172.64.41', '', '2016-07-12 16:30:13', '2016-07-27 16:30:13', '2016-07-12 16:30:30', 'home'),
(28, 'hexicans', 'hfWWi4cDeCLWW5XwcKdVfmoko2Ziy1ZPUqDr7oZQYGpCorYTn1ItbUu74oRAqq4xsNJkSspJ5daCFdVfFNzHGwuKlcsP3kh5hkai', '80.215.37.201', '', '2016-08-04 10:38:07', '2016-08-19 10:38:07', '2016-08-04 12:17:08', 'post/49'),
(30, 'hexicans', 'yE1yKJDoqYtpucjc3x1orGxT7MElJA3vF0K0sH8mj1xbzP00URlTkZbfhzZwTjhrlQ1gtKrnspV10iAEmDlVSBVs2L3wNmOkEvuL', '164.132.237.192', '', '2016-08-20 21:34:54', '2016-09-04 21:34:54', '2016-08-22 17:37:12', 'user/Matiboux'),
(32, 'Matiboux', 'uOVo1uV2rTAD9lbJDmIMYENcKzNHpkhwn8RuGBGqJVhPqPH9trm3X7G3IbMzgKTqYqcL5NEURSKFvfIhUZFmHmpXvQ3PxCaEGrIm', '80.215.156.186', '', '2016-08-28 21:26:54', '2016-09-12 21:26:54', '2016-08-28 21:28:15', 'admin/log-as/Dav/confirmed'),
(33, 'Matiboux', 'rUwTKmsL1W7gL3xpBzBsLciRVm9C5dZNMxwuPm1rCCKoRQGy6LEBbXnS2Mdqx4R6CrIPX0UT0Zwqdvhtl6RUfUehU7btPpCG5J5b', '89.91.144.175', '', '2016-09-07 23:57:08', '2016-09-22 23:57:08', '2016-09-08 00:10:34', 'settings/requests'),
(34, 'hexicans', '1bc794984a301651b2dd58f97332b39c3e0a6a5d', '91.134.249.214', '', '2016-09-23 23:38:04', '2016-10-08 23:38:04', '2016-09-23 23:40:57', 'http://social.matiboux.com//home'),
(35, 'hexicans', '031af767f3c373a9446a1acdec9c86355e135be1', '149.202.12.57', '', '2016-10-02 19:13:03', '2016-10-17 19:13:03', '2016-10-02 19:18:10', 'http://social.matiboux.com//login.php/logout');

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'url', 'social.matiboux.com/'),
(2, 'name', 'Matiboux Social'),
(3, 'description', 'Petit réseau Social simple et indépendant'),
(4, 'force_https', '0'),
(5, 'version', '1.1'),
(6, 'creation_date', '2016-03-06'),
(7, 'status', 'beta'),
(8, 'auth_key_cookie_name', 'AuthKey'),
(9, 'owner', 'Matiboux');

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
(1, 'home', 'http://social.matiboux.com/'),
(2, 'login', 'http://social.matiboux.com/login.php'),
(4, 'cdn', 'http://cdn.matiboux.com/');

-- --------------------------------------------------------

--
-- Structure de la table `social_follows`
--

CREATE TABLE `social_follows` (
  `id` bigint(20) NOT NULL,
  `username` varchar(64) NOT NULL,
  `follows` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `social_follows`
--

INSERT INTO `social_follows` (`id`, `username`, `follows`) VALUES
(1, 'Matiboux', 'Eliotitto'),
(2, 'Matiboux', 'Dav'),
(5, 'Matiboux', 'swan2000'),
(6, 'Matiboux', 'Maatiboux'),
(7, 'Matiboux', 'Titouan'),
(8, 'Matiboux', 'glenn'),
(9, 'Matiboux', 'Fred'),
(10, 'Matiboux', 'Herve'),
(11, 'felixjules', 'Matiboux'),
(12, 'felixjules', 'TheKiller678'),
(13, 'felixjules', 'Dav'),
(14, 'Eliotitto', 'Matiboux'),
(15, 'Matiboux', 'felixjules'),
(16, 'Matiboux', 'TheKiller678'),
(17, 'hexicans', 'Matiboux'),
(18, 'hexicans', 'Eliotitto'),
(19, 'Matiboux', 'hexicans');

-- --------------------------------------------------------

--
-- Structure de la table `social_likes`
--

CREATE TABLE `social_likes` (
  `id` bigint(20) NOT NULL,
  `username` varchar(64) NOT NULL,
  `post_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `social_likes`
--

INSERT INTO `social_likes` (`id`, `username`, `post_id`) VALUES
(1, 'hexicans', 45),
(2, 'hexicans', 42),
(3, 'hexicans', 37),
(4, 'hexicans', 36),
(5, 'hexicans', 35),
(6, 'hexicans', 6),
(7, 'Matiboux', 48),
(8, 'hexicans', 54),
(9, 'hexicans', 55);

-- --------------------------------------------------------

--
-- Structure de la table `social_medias`
--

CREATE TABLE `social_medias` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `owner` varchar(64) NOT NULL,
  `date` datetime NOT NULL,
  `path_addon` varchar(256) NOT NULL,
  `file_key` varchar(256) NOT NULL,
  `file_type` varchar(64) NOT NULL,
  `file_name` varchar(256) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_hash` varchar(256) NOT NULL,
  `file_hash_algo` varchar(32) NOT NULL,
  `original_file_name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `social_medias`
--

INSERT INTO `social_medias` (`id`, `name`, `owner`, `date`, `path_addon`, `file_key`, `file_type`, `file_name`, `file_size`, `file_hash`, `file_hash_algo`, `original_file_name`) VALUES
(1, '', 'Matiboux', '2016-06-19 18:15:54', 'Matiboux/', 'SyoDQzkUpsWPa5XGiO6R', 'png', 'SyoDQzkUpsWPa5XGiO6R.png', 1261232, '081709ac51405411b3740c6826fda36df625ce5c', 'sha1', 'Capture2.PNG'),
(2, '', 'Eliotitto', '2016-07-03 00:06:36', 'Eliotitto/', 'EGTmh9ofHAZXtU67Dgbp', 'jpg', 'EGTmh9ofHAZXtU67Dgbp.jpg', 15482, '08ca5dc36e03250a607c5167a0782b7013acf649', 'sha1', 'EGTmh9ofHAZXtU67Dgbp.jpg'),
(3, '', 'glenn', '2016-06-20 13:53:55', 'glenn/', 'qr03HNjCcy8z1ZGWlLT4', 'png', 'qr03HNjCcy8z1ZGWlLT4.png', 122785, '7a55b554e71e214d134b1abfa2b87e0dc12a57dc', 'sha1', 'qr03HNjCcy8z1ZGWlLT4.png'),
(4, '', 'Matiboux', '2016-07-02 15:08:14', 'Matiboux/', '6MVm8viNPLFRAQGI5Wqz', 'png', '6MVm8viNPLFRAQGI5Wqz.png', 408659, '8e1c4699575f804300e106151352407594b6edf1', 'sha1', 'Capture.PNG'),
(5, '', 'felixjules', '2016-07-04 01:11:41', 'felixjules/', 'fdnXzyutVOTFqRADZlGj', 'png', 'fdnXzyutVOTFqRADZlGj.png', 306389, '9aa6e87d5813d9a8bddb18f5963a639ca0a3babe', 'sha1', 'Félix la saucisse.png'),
(6, '', 'felixjules', '2016-07-04 02:28:31', 'felixjules/', 'usjLWfnGivFVI8tOxq6R', 'gif', 'usjLWfnGivFVI8tOxq6R.gif', 473848, '1a9c1019601b300ca296a61a0b9449b22bbc62cf', 'sha1', 'ezgif.com-optimize (2).gif'),
(7, '', 'felixjules', '2016-07-05 00:52:01', 'felixjules/', '85KMC0zWF6DUxdlh4Ot7', 'png', '85KMC0zWF6DUxdlh4Ot7.png', 22757, '897da219244dc096e1b9bf1f51bdac5cef1c5a21', 'sha1', '6bZMF.png'),
(8, '', 'felixjules', '2016-07-06 02:41:54', 'felixjules/', 'DP5O4XnvGiMZ8yfg0CW3', 'png', 'DP5O4XnvGiMZ8yfg0CW3.png', 37375, '0fd468ce0588e5c572af218b3245f2764a2f999f', 'sha1', 'bonhomme.png'),
(10, '', 'felixjules', '2016-07-06 02:54:18', 'felixjules/', 'e8WKYxOF49yPtpR5dJqZ', 'jpg', 'e8WKYxOF49yPtpR5dJqZ.jpg', 36739, '6484af144fe98163f25ac66418795eda7da4bcc9', 'sha1', 'maillot de bain.jpg'),
(11, '', 'Matiboux', '2016-07-24 11:24:39', 'Matiboux/', 'zy5hJH69scij3wx8Dt7I', 'jpg', 'zy5hJH69scij3wx8Dt7I.jpg', 1122452, '0b5cb25304ae22c46a0452559d62c342544e06c4', 'sha1', 'IMG_20160724_110507_01_02.jpg'),
(12, '', 'hexicans', '2016-08-20 21:36:36', 'hexicans/', '6aIj21C3ZfWgyxlisVKt', 'jpg', '6aIj21C3ZfWgyxlisVKt.jpg', 43323, 'a8a8a36ac18b09e7578a14373893a1a634ea8c0c', 'sha1', 'trollface.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `social_notifications`
--

CREATE TABLE `social_notifications` (
  `id` bigint(20) NOT NULL,
  `username` varchar(64) NOT NULL,
  `type` varchar(256) NOT NULL,
  `data` text NOT NULL,
  `seen_date` datetime DEFAULT NULL,
  `creation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `social_notifications`
--

INSERT INTO `social_notifications` (`id`, `username`, `type`, `data`, `seen_date`, `creation_date`) VALUES
(1, 'Matiboux', 'mention', '{"postId":15,"owner":"felixjules"}', '2016-07-10 22:36:44', '2016-07-10 22:29:56'),
(2, 'felixjules', 'reply', '{"postId":16,"owner":"Matiboux"}', '2016-07-11 00:18:27', '2016-07-10 22:29:56'),
(3, 'felixjules', 'reply', '{"postId":19,"owner":"Matiboux"}', '2016-07-11 00:18:27', '2016-07-10 22:29:56'),
(4, 'Matiboux', 'mention', '{"postId":20,"owner":"felixjules"}', '2016-07-10 22:36:44', '2016-07-10 22:29:56'),
(5, 'Matiboux', 'reply', '{"postId":21,"owner":"felixjules"}', '2016-07-10 22:36:44', '2016-07-10 22:29:56'),
(6, 'Matiboux', 'reply', '{"postId":23,"owner":"felixjules"}', '2016-07-10 22:36:44', '2016-07-10 22:29:56'),
(7, 'felixjules', 'reply', '{"postId":24,"owner":"Matiboux"}', '2016-07-11 00:18:27', '2016-07-10 22:29:56'),
(8, 'Matiboux', 'reply', '{"postId":25,"owner":"felixjules"}', '2016-07-10 22:36:44', '2016-07-10 22:29:56'),
(9, 'Matiboux', 'mention', '{"postId":28,"owner":"felixjules"}', '2016-07-10 22:36:44', '2016-07-10 22:29:56'),
(10, 'Matiboux', 'reply', '{"postId":33,"owner":"TheKiller678"}', '2016-07-11 17:47:55', '2016-07-10 22:29:56'),
(11, 'TheKiller678', 'reply', '{"postId":35,"owner":"Matiboux"}', '2016-07-12 16:30:19', '2016-07-10 22:33:11'),
(12, 'Eliotitto', 'follow', '{"username":"Matiboux"}', '2016-07-22 14:56:19', '2016-07-10 22:46:34'),
(13, 'Dav', 'follow', '{"username":"Matiboux"}', '2016-08-04 16:45:16', '2016-07-10 22:46:39'),
(16, 'swan2000', 'follow', '{"username":"Matiboux"}', NULL, '2016-07-10 22:51:33'),
(17, 'Maatiboux', 'follow', '{"username":"Matiboux"}', NULL, '2016-07-10 22:52:00'),
(18, 'Titouan', 'follow', '{"username":"Matiboux"}', NULL, '2016-07-10 22:52:06'),
(19, 'glenn', 'follow', '{"username":"Matiboux"}', NULL, '2016-07-10 22:52:14'),
(20, 'Fred', 'follow', '{"username":"Matiboux"}', NULL, '2016-07-10 22:52:19'),
(21, 'Herve', 'follow', '{"username":"Matiboux"}', NULL, '2016-07-10 22:52:24'),
(22, 'all', 'major_announce', '{"message":"Likes, reposts and notifications have been reset!\\r\\nPlease excuse us for these problems."}', NULL, '2016-07-10 22:54:37'),
(23, 'Matiboux', 'follow', '{"username":"felixjules"}', '2016-07-12 12:26:36', '2016-07-11 00:18:44'),
(24, 'TheKiller678', 'follow', '{"username":"felixjules"}', '2016-07-12 16:30:19', '2016-07-11 00:18:55'),
(25, 'Dav', 'follow', '{"username":"felixjules"}', '2016-08-04 16:45:16', '2016-07-11 00:19:04'),
(26, 'Titouan', 'reply', '{"postId":40,"owner":"felixjules"}', NULL, '2016-07-11 00:20:25'),
(27, 'Matiboux', 'follow', '{"username":"Eliotitto"}', '2016-07-23 16:41:07', '2016-07-22 15:03:30'),
(28, 'Eliotitto', 'mention', '{"postId":42,"owner":"Matiboux"}', NULL, '2016-07-24 11:24:39'),
(30, 'felixjules', 'follow', '{"username":"Matiboux"}', NULL, '2016-07-27 14:57:08'),
(32, 'Eliotitto', 'repost', '{"username":"Matiboux","postId":"5"}', NULL, '2016-07-27 19:33:15'),
(36, 'TheKiller678', 'follow', '{"username":"Matiboux"}', NULL, '2016-07-28 18:43:08'),
(37, 'Matiboux', 'like', '{"username":"hexicans","postId":"45"}', '2016-08-04 16:58:10', '2016-08-04 10:39:40'),
(38, 'Matiboux', 'repost', '{"username":"hexicans","postId":"45"}', '2016-08-04 16:58:10', '2016-08-04 10:39:44'),
(39, 'Matiboux', 'like', '{"username":"hexicans","postId":"42"}', '2016-08-04 16:58:10', '2016-08-04 10:39:51'),
(40, 'Matiboux', 'like', '{"username":"hexicans","postId":"37"}', '2016-08-04 16:58:10', '2016-08-04 10:39:52'),
(41, 'Matiboux', 'like', '{"username":"hexicans","postId":"36"}', '2016-08-04 16:58:10', '2016-08-04 10:39:54'),
(42, 'Matiboux', 'like', '{"username":"hexicans","postId":"35"}', '2016-08-04 16:58:10', '2016-08-04 10:39:57'),
(43, 'Matiboux', 'follow', '{"username":"hexicans"}', '2016-08-04 16:58:10', '2016-08-04 10:40:15'),
(44, 'Eliotitto', 'follow', '{"username":"hexicans"}', NULL, '2016-08-04 10:40:23'),
(45, 'Eliotitto', 'like', '{"username":"hexicans","postId":"6"}', NULL, '2016-08-04 10:40:31'),
(46, 'Matiboux', 'mention', '{"postId":48,"owner":"hexicans"}', '2016-08-04 16:58:10', '2016-08-04 10:41:14'),
(50, 'hexicans', 'follow', '{"username":"Matiboux"}', '2016-08-20 21:35:16', '2016-08-04 17:00:36'),
(51, 'hexicans', 'like', '{"username":"Matiboux","postId":"48"}', '2016-08-20 21:35:16', '2016-08-04 17:01:08'),
(52, 'Eliotitto', 'reply', '{"postId":52,"owner":"hexicans"}', NULL, '2016-08-20 21:36:36'),
(53, 'Eliotitto', 'mention', '{"postId":53,"owner":"hexicans"}', NULL, '2016-09-23 23:38:44');

-- --------------------------------------------------------

--
-- Structure de la table `social_posts`
--

CREATE TABLE `social_posts` (
  `id` bigint(20) NOT NULL,
  `content` text NOT NULL,
  `reply_to` varchar(32) NOT NULL,
  `quote_from` varchar(32) NOT NULL,
  `media_key` varchar(64) NOT NULL,
  `owner` varchar(64) NOT NULL,
  `user_ip` varchar(64) NOT NULL,
  `post_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `social_posts`
--

INSERT INTO `social_posts` (`id`, `content`, `reply_to`, `quote_from`, `media_key`, `owner`, `user_ip`, `post_date`) VALUES
(1, 'just setting up my account!', '', '', '', 'Matiboux', '176.151.9.138', '2016-06-15 23:16:52'),
(2, 'Bonjour est bienvenue sur le site de matiboux.', '', '', '', 'Dav', '2.0.153.207', '2016-06-16 14:20:25'),
(3, 'felixjules1 is a sausage. He also has one but a very little one.', '', '', '', 'TheKiller678', '88.172.64.41', '2016-06-17 17:05:30'),
(4, 'Media Support!', '', '', 'SyoDQzkUpsWPa5XGiO6R', 'Matiboux', '176.151.9.138', '2016-06-19 18:15:54'),
(5, 'Hello world !', '', '', '', 'Eliotitto', '86.215.98.216', '2016-06-19 18:21:22'),
(6, 'Allons prendre une douche !', '', '', 'EGTmh9ofHAZXtU67Dgbp', 'Eliotitto', '86.215.98.216', '2016-06-19 18:22:12'),
(8, 'hello world', '', '', '', 'glenn', '176.182.220.4', '2016-06-20 13:51:47'),
(9, 'my wankul', '', '', 'qr03HNjCcy8z1ZGWlLT4', 'glenn', '176.182.220.4', '2016-06-20 13:53:55'),
(10, 'Passion', '', '', '6MVm8viNPLFRAQGI5Wqz', 'Matiboux', '176.151.9.138', '2016-07-02 15:08:14'),
(11, 'Je taffe à nouveau sur le projet.\nLes notifs pour la suite.', '', '', '', 'Matiboux', '176.151.9.138', '2016-07-03 03:27:49'),
(12, 'Je suis la saucisse.', '', '', 'fdnXzyutVOTFqRADZlGj', 'felixjules', '88.172.64.179', '2016-07-04 01:12:23'),
(13, 'La belle baguette  =)', '', '', 'usjLWfnGivFVI8tOxq6R', 'felixjules', '88.172.64.179', '2016-07-04 02:28:31'),
(14, 'New update!\n- You can now follow anyone registered on the network\n- You can mention any member in your post, just type his @username (e.g. @Matiboux)\nand mentioned peoples receive a notification showed in the notifications tab (still experimental)', '', '', '', 'Matiboux', '90.105.160.76', '2016-07-04 03:49:20'),
(15, '@Matiboux Enfin débanne ', '', '', '85KMC0zWF6DUxdlh4Ot7', 'felixjules', '88.172.64.179', '2016-07-05 00:52:01'),
(16, '@felixjules - Yeah. On note la belle faute d\'orthographe', '15', '', '', 'Matiboux', '90.105.160.76', '2016-07-05 03:58:23'),
(17, 'New update! \n- You can now reply to any post (and  the user is notified when someone replied to him)\n- And more minor changes ', '', '', '', 'Matiboux', '80.215.75.3', '2016-07-05 04:36:13'),
(18, 'Bonjour je suis un post', '', '', '', 'felixjules', '88.172.64.179', '2016-07-05 21:10:25'),
(19, '@felixjules - Bonjour je suis une réponse', '18', '', '', 'Matiboux', '90.105.160.76', '2016-07-06 02:39:49'),
(20, '@Matiboux bonjour je suis une mention', '', '', '', 'felixjules', '88.172.64.179', '2016-07-06 02:40:12'),
(21, '@Matiboux - Bonjour je suis la réponse de la réponse\r\n', '19', '', '', 'felixjules', '88.172.64.179', '2016-07-06 02:40:51'),
(22, 'Je suis un bonhomme :)', '', '', 'DP5O4XnvGiMZ8yfg0CW3', 'felixjules', '88.172.64.179', '2016-07-06 02:41:54'),
(23, '@Matiboux - je suis la seconde réponse à la réponse\r\n', '19', '', '', 'felixjules', '88.172.64.179', '2016-07-06 02:42:13'),
(24, '@felixjules - C\'est vraiment trop classe', '22', '', '', 'Matiboux', '90.105.160.76', '2016-07-06 02:43:13'),
(25, '@Matiboux - Ouai je sais #LeTalent', '24', '', '', 'felixjules', '88.172.64.179', '2016-07-06 02:43:28'),
(27, 'Lol le maillot de bain le plus chère du monde \r\nhttp://www.chine-informations.com/actualite/le-maillot-de-bain-le-plus-cher-du-monde-porte-par-mo-wandan_10103.html', '', '', 'e8WKYxOF49yPtpR5dJqZ', 'felixjules', '88.172.64.179', '2016-07-06 02:54:18'),
(28, '@Matiboux A quand un how/pee \r\nhttps://www.youtube.com/watch?v=8amtq5aXRL8', '', '', '', 'felixjules', '88.172.64.179', '2016-07-06 03:03:57'),
(29, 'Du grand art.\r\nhow.matiboux.com/balls\r\nhow.matiboux.com/drug\r\nhow.matiboux.com/pee', '', '', '', 'Matiboux', '90.105.160.76', '2016-07-06 03:50:05'),
(30, 'Sur http://how.matiboux.com/pee, j\'ai fait en sorte de synchroniser les couleurs à la musique c:', '29', '', '', 'Matiboux', '90.105.160.76', '2016-07-06 04:06:44'),
(33, '@Matiboux - T\'as oublié celui-là: http://how.matiboux.com/fist', '29', '', '', 'TheKiller678', '88.172.64.41', '2016-07-07 00:57:12'),
(34, 'Maintenant, on peut utiliser des #Hashtag', '', '', '', 'Matiboux', '90.105.160.76', '2016-07-07 01:00:28'),
(35, '@TheKiller678 - Merci, merci.', '33', '', '', 'Matiboux', '90.105.160.76', '2016-07-07 01:16:29'),
(36, 'Now search for anything on http://social.matiboux.com/search!\r\nYou can search for an username, something in a post or a hashtag', '', '', '', 'Matiboux', '90.105.160.76', '2016-07-07 03:53:35'),
(37, 'Now like any post which catches your attention!\r\nTry it! Just click the like button beside the post', '', '', '', 'Matiboux', '176.151.9.138', '2016-07-10 02:52:42'),
(39, 'Patate', '', '', '', 'Titouan', '90.49.248.35', '2016-07-10 12:11:30'),
(40, '@Titouan - Tomate', '39', '', '', 'felixjules', '88.172.64.179', '2016-07-11 00:20:25'),
(42, 'Petit dessin fait dans le train. \r\nAprès être parti de chez @Eliotitto. ', '', '', 'zy5hJH69scij3wx8Dt7I', 'Matiboux', '80.215.134.116', '2016-07-24 11:24:39'),
(44, '', '', '5', '', 'Matiboux', '', '0000-00-00 00:00:00'),
(45, 'This project is now on standby.\r\nI\'m working on other projects\r\nThanks for undertanding.', '', '', '', 'Matiboux', '176.151.9.138', '2016-08-03 01:18:14'),
(46, 'wow amazing', '', '', '', 'hexicans', '80.215.37.201', '2016-08-04 10:38:21'),
(47, '', '', '45', '', 'hexicans', '', '0000-00-00 00:00:00'),
(48, '@eliotto @matiboux hello mes petites couilles ', '', '', '', 'hexicans', '80.215.37.201', '2016-08-04 10:41:14'),
(49, 'Hey!', '', '', '', 'hexicans', '80.215.37.201', '2016-08-04 10:41:34'),
(50, '', '49', '', '', 'hexicans', '80.215.105.191', '2016-08-04 11:21:53'),
(51, ' ', '', '', '', 'hexicans', '164.132.237.192', '2016-08-20 21:35:08'),
(52, '@Eliotitto -  wesh b1 ou koi', '5', '', '6aIj21C3ZfWgyxlisVKt', 'hexicans', '164.132.237.192', '2016-08-20 21:36:36'),
(53, '@eliotitto wesh', '', '', '', 'hexicans', '91.134.249.214', '2016-09-23 23:38:44'),
(54, 'hey', '', '', '', 'hexicans', '149.202.12.57', '2016-10-02 19:13:20'),
(55, 'RT si tu veux que Mati continue social et le sorte , il faut 10000000', '', '', '', 'hexicans', '149.202.12.57', '2016-10-02 19:15:51');

-- --------------------------------------------------------

--
-- Structure de la table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `messages` text NOT NULL,
  `subject` varchar(64) NOT NULL,
  `priority` varchar(64) NOT NULL,
  `owner` varchar(64) NOT NULL,
  `ticket_key` varchar(256) NOT NULL,
  `creation_date` datetime NOT NULL,
  `last_message_infos` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `title`, `messages`, `subject`, `priority`, `owner`, `ticket_key`, `creation_date`, `last_message_infos`) VALUES
(1, 'Va dormir !', '{"0":{"message":"Dodo !","username":"hexicans","postDate":"2016-09-23 23:39:14"}}', 'talk', 'high', 'hexicans', 'Ef0LKrYQ3o2h', '2016-09-23 23:39:14', '{"username":"hexicans","postDate":"2016-09-23 23:39:14"}');

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
-- Structure de la table `user_avatars`
--

CREATE TABLE `user_avatars` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `owner` varchar(64) NOT NULL,
  `date` datetime NOT NULL,
  `path_addon` varchar(256) NOT NULL,
  `file_key` varchar(256) NOT NULL,
  `file_type` varchar(64) NOT NULL,
  `file_name` varchar(256) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_hash` varchar(256) NOT NULL,
  `file_hash_algo` varchar(32) NOT NULL,
  `original_file_name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user_avatars`
--

INSERT INTO `user_avatars` (`id`, `name`, `owner`, `date`, `path_addon`, `file_key`, `file_type`, `file_name`, `file_size`, `file_hash`, `file_hash_algo`, `original_file_name`) VALUES
(2, 'user_avatar', 'Dav', '2016-08-04 16:51:14', '', 'Dav', 'jpg', 'Dav.jpg', 12302, 'b0ad476847155e80b546f5575d5bff2087419839', 'sha1', 'Capture.jpg'),
(3, 'user_avatar', 'felixjules', '2016-06-10 21:24:04', '', 'felixjules', 'png', 'felixjules.png', 85348, '99c245267b99fd11b4b4e64e553e281c8cea9181', 'sha1', 'Logo F Carré + fleche.png'),
(5, 'user_avatar', 'TheKiller678', '2016-07-09 20:37:07', '', 'TheKiller678', 'png', 'TheKiller678.png', 57994, '7d95a7cba525d4f3242163ba4acbc493fd3c7af9', 'sha1', 'Logo K (TheKiller678 ta mamie la chauve).png'),
(6, 'user_avatar', 'Eliotitto', '2016-07-25 14:51:04', '', 'Eliotitto', 'jpg', 'Eliotitto.jpg', 13434, 'da3da47dced89653060bf48e75dd43f888e605f1', 'sha1', 'Bu78Za5c_400x400.jpg'),
(7, 'user_avatar', 'glenn', '2016-06-20 14:01:35', '', 'glenn', 'png', 'glenn.png', 122785, '7a55b554e71e214d134b1abfa2b87e0dc12a57dc', 'sha1', 'Glenn.png'),
(11, 'user_avatar', 'Titouan', '2016-07-10 12:17:45', '', 'Titouan', 'jpg', 'Titouan.jpg', 4708, '09a3ce1f3b3e8ebff6890de685273e5b330213b1', 'sha1', 'pouce bleu.jpg');

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
-- Index pour la table `social_follows`
--
ALTER TABLE `social_follows`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `social_likes`
--
ALTER TABLE `social_likes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `social_medias`
--
ALTER TABLE `social_medias`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `social_notifications`
--
ALTER TABLE `social_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `social_posts`
--
ALTER TABLE `social_posts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_avatars`
--
ALTER TABLE `user_avatars`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `accounts_requests`
--
ALTER TABLE `accounts_requests`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `accounts_rights`
--
ALTER TABLE `accounts_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `accounts_sessions`
--
ALTER TABLE `accounts_sessions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `shortcut_links`
--
ALTER TABLE `shortcut_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `social_follows`
--
ALTER TABLE `social_follows`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `social_likes`
--
ALTER TABLE `social_likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `social_medias`
--
ALTER TABLE `social_medias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `social_notifications`
--
ALTER TABLE `social_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT pour la table `social_posts`
--
ALTER TABLE `social_posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `user_avatars`
--
ALTER TABLE `user_avatars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
