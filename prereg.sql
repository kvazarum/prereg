-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 18 2015 г., 10:50
-- Версия сервера: 5.6.26
-- Версия PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `prereg`
--
CREATE DATABASE IF NOT EXISTS `prereg` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `prereg`;

-- --------------------------------------------------------

--
-- Структура таблицы `cabinets`
--
-- Создание: Сен 07 2015 г., 10:07
--

CREATE TABLE IF NOT EXISTS `cabinets` (
  `id` smallint(6) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Структура таблицы `doctors`
--
-- Создание: Сен 15 2015 г., 11:58
--

CREATE TABLE IF NOT EXISTS `doctors` (
  `id` smallint(6) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `number` int(11) NOT NULL COMMENT 'Табельный номер',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `price_initial` int(11) NOT NULL,
  `price_secondary` int(11) NOT NULL COMMENT 'Цена вторичного посещения',
  `start_time` smallint(5) unsigned NOT NULL,
  `end_time` smallint(5) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



--
-- Структура таблицы `occupations`
--
-- Создание: Сен 15 2015 г., 11:59
--

CREATE TABLE IF NOT EXISTS `occupations` (
  `id` smallint(6) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `period` smallint(6) unsigned NOT NULL COMMENT 'Длительность приёма',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



--
-- Структура таблицы `records`
--
-- Создание: Сен 18 2015 г., 04:44
--

CREATE TABLE IF NOT EXISTS `records` (
  `id` int(10) unsigned NOT NULL COMMENT 'ID',
  `specialist_id` int(10) unsigned NOT NULL COMMENT 'Специалист',
  `cabinet_id` smallint(5) unsigned NOT NULL COMMENT 'Кабинет',
  `start_time` datetime NOT NULL COMMENT 'Время начала приёма',
  `reserved` tinyint(1) NOT NULL COMMENT 'Зарезервировано',
  `visited` tinyint(1) NOT NULL COMMENT 'Посещено',
  `sum` int(10) unsigned NOT NULL COMMENT 'Сумма',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Фамилия Имя Отчество',
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '№ телефона',
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT 'Создано',
  `updated_at` datetime NOT NULL COMMENT 'Изменено'
) ENGINE=InnoDB AUTO_INCREMENT=669 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



--
-- Структура таблицы `specialists`
--
-- Создание: Сен 16 2015 г., 08:56
--

CREATE TABLE IF NOT EXISTS `specialists` (
  `id` int(10) unsigned NOT NULL,
  `doctor_id` smallint(6) NOT NULL,
  `occupation_id` smallint(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Структура таблицы `user`
--
-- Создание: Сен 07 2015 г., 13:08
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cabinets`
--
ALTER TABLE `cabinets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`);

--
-- Индексы таблицы `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `occupations`
--
ALTER TABLE `occupations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialist_id` (`specialist_id`);

--
-- Индексы таблицы `specialists`
--
ALTER TABLE `specialists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `occupation_id` (`occupation_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cabinets`
--
ALTER TABLE `cabinets`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT для таблицы `occupations`
--
ALTER TABLE `occupations`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT для таблицы `records`
--
ALTER TABLE `records`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',AUTO_INCREMENT=669;
--
-- AUTO_INCREMENT для таблицы `specialists`
--
ALTER TABLE `specialists`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `records_specialists` FOREIGN KEY (`specialist_id`) REFERENCES `specialists` (`id`);

--
-- Ограничения внешнего ключа таблицы `specialists`
--
ALTER TABLE `specialists`
  ADD CONSTRAINT `specialists_doctors` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`),
  ADD CONSTRAINT `specialists_occupations` FOREIGN KEY (`occupation_id`) REFERENCES `occupations` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;