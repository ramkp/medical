-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Мар 13 2017 г., 10:44
-- Версия сервера: 5.6.35
-- Версия PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `cnausa_lms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `mdl_deposit`
--

DROP TABLE IF EXISTS `mdl_deposit`;
CREATE TABLE IF NOT EXISTS `mdl_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banknum` varchar(255) NOT NULL,
  `amount` varchar(128) NOT NULL,
  `userid` int(11) NOT NULL,
  `added` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `mdl_deposit`
--

INSERT INTO `mdl_deposit` (`id`, `banknum`, `amount`, `userid`, `added`) VALUES
(1, '1', '900', 234, '1485752400'),
(2, 'Ms Comm Prop Schools', '500', 234, '1487307600'),
(3, 'Lunch ABHES', '23', 234, '1487221200'),
(4, 'cash & checks deposit', '855', 234, '1487307600'),
(5, 'Kayla Long cash', '216', 234, '1486702800'),
(6, 'Big Picture Media', '400', 234, '1486702800'),
(7, 'Lee County Taxes', '192.45', 234, '1486530000'),
(8, 'N/A', '465', 234, '1487739600'),
(9, 'refund overpd CNA', '25', 234, '1487739600'),
(10, 'CHECK', '3130', 2, '1488171600'),
(11, 'N/A', '3015', 2, '1488171600'),
(12, 'Sean', '2000', 234, '1489035600'),
(13, 'checks', '100', 234, '1489035600'),
(14, 'cash on hand for change', '37', 234, '1489035600');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
