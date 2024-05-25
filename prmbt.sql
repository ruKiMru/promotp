-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 21 2024 г., 20:23
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `prmbt`
--

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id_client` int NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `middle_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `chat_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id_client`, `first_name`, `middle_name`, `last_name`, `deleted`, `company_name`, `contact_phone`, `email`, `inn`, `username`, `chat_id`) VALUES
(1, 'John', 'Doe', 'Smith', 0, 'ООО \"Ромашка\"', '88005553500', 'example@go.com', '8878787878787', 'danyabroyk', 5204558204),
(2, 'Alice', 'Jane', 'Johnson', 0, NULL, NULL, NULL, NULL, '', 0),
(3, 'Bob', 'Michael', 'Brown', 0, 'ООО \"Бебра\"', '', '', '', '', 0),
(19, 'ыва', 'ыва', 'ыва', 0, 'ыва', 'ыва', 'ыва', 'ыва', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id_comment` int NOT NULL,
  `id_issue` int NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id_comment`, `id_issue`, `comment`, `creation_date`) VALUES
(3, 31, 'hello', '2024-03-10 23:38:32'),
(4, 31, 'Test', '2024-03-10 23:41:18'),
(9, 32, '', '2024-05-09 12:21:15'),
(10, 32, '123', '2024-05-09 12:21:18'),
(11, 31, '123123', '2024-05-12 23:12:27'),
(12, 31, '123123', '2024-05-12 23:12:28'),
(13, 31, '123123', '2024-05-12 23:12:29'),
(14, 31, '123123', '2024-05-12 23:12:29'),
(15, 31, '123123', '2024-05-12 23:12:30'),
(16, 31, '123123', '2024-05-12 23:12:31'),
(17, 31, '123123', '2024-05-12 23:12:31'),
(18, 31, '123123', '2024-05-12 23:12:31'),
(19, 31, '123123', '2024-05-12 23:12:31'),
(20, 31, '123123', '2024-05-12 23:12:31'),
(21, 31, '123123', '2024-05-12 23:12:32'),
(22, 32, '', '2024-05-15 22:42:13'),
(23, 31, 'sdfsdfsdaf', '2024-05-19 20:43:39'),
(24, 31, 'вапвап', '2024-05-19 20:53:58');

-- --------------------------------------------------------

--
-- Структура таблицы `config&equip`
--

CREATE TABLE `config&equip` (
  `id` int NOT NULL,
  `configuration_id` int DEFAULT NULL,
  `equipments_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `config&equip`
--

INSERT INTO `config&equip` (`id`, `configuration_id`, `equipments_id`) VALUES
(21, 11, 7),
(22, 12, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `configurations`
--

CREATE TABLE `configurations` (
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `configurations`
--

INSERT INTO `configurations` (`id`, `name`) VALUES
(11, 'Test config'),
(12, 'Test config 1');

-- --------------------------------------------------------

--
-- Структура таблицы `equipments`
--

CREATE TABLE `equipments` (
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `equipments`
--

INSERT INTO `equipments` (`id`, `name`, `description`) VALUES
(7, 'Test1', 'Test1 desc'),
(8, 'Test2', 'Test2 desc');

-- --------------------------------------------------------

--
-- Структура таблицы `issues`
--

CREATE TABLE `issues` (
  `id_issue` int NOT NULL,
  `id_status` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `id_client` int DEFAULT NULL,
  `id_mark` int DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `start_time` datetime DEFAULT NULL,
  `completion_time` datetime DEFAULT NULL,
  `product_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `issues`
--

INSERT INTO `issues` (`id_issue`, `id_status`, `id_user`, `id_client`, `id_mark`, `deleted`, `name`, `Description`, `start_time`, `completion_time`, `product_id`) VALUES
(31, 2, 36, 1, 3, 0, 'Тестовая задача', '', '2024-03-10 23:41:35', '2024-03-10 23:41:50', 0),
(32, 3, 36, 3, 3, 0, 'Тестовая задача 2', '', '2024-03-09 23:01:56', '2024-03-09 23:01:59', 0),
(37, 3, 36, 19, 2, 0, 'ываыва', '', '2024-05-19 22:56:04', NULL, 0),
(38, 3, 36, 1, 1, 0, 'ывавыа', '', '2024-05-20 10:57:12', NULL, 0),
(39, 1, 36, 1, NULL, 0, 'ыавываываыа', '', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `marks`
--

CREATE TABLE `marks` (
  `id_mark` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `marks`
--

INSERT INTO `marks` (`id_mark`, `name`) VALUES
(1, 'Высокая'),
(2, 'Средняя'),
(3, 'Низкая');

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id_messages` int NOT NULL,
  `contact_id` int NOT NULL,
  `sender_id` int DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id_messages`, `contact_id`, `sender_id`, `message`, `time`, `deleted`) VALUES
(6, 1, NULL, 'asd', '2024-05-21 17:19:16', 0),
(7, 1, 36, 'asd', '2024-05-21 17:19:24', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `model&config`
--

CREATE TABLE `model&config` (
  `id` int NOT NULL,
  `model_id` int NOT NULL,
  `configuration_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `model&config`
--

INSERT INTO `model&config` (`id`, `model_id`, `configuration_id`) VALUES
(22, 9, 11);

-- --------------------------------------------------------

--
-- Структура таблицы `model&equip`
--

CREATE TABLE `model&equip` (
  `id` int NOT NULL,
  `model_id` int NOT NULL,
  `equipment_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `model&equip`
--

INSERT INTO `model&equip` (`id`, `model_id`, `equipment_id`) VALUES
(25, 9, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `models`
--

CREATE TABLE `models` (
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `models`
--

INSERT INTO `models` (`id`, `name`) VALUES
(9, 'Test1 model');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `configuration_id` int DEFAULT NULL,
  `series` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ip` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `release_date` date DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `model_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `configuration_id`, `series`, `ip`, `release_date`, `client_id`, `model_id`) VALUES
(1, NULL, 'PG-Test', '139.234.34.45', '2024-05-20', 1, NULL),
(2, NULL, 'PG-Test1', '139.234.34.32', '2024-05-21', 3, NULL),
(3, NULL, 'PG-Test2', '139.102.34.45', '2024-05-23', 19, NULL),
(5, NULL, 'PG-Test332', '139.323.34.32', '2024-05-22', 3, NULL),
(6, NULL, 'PG-Test1', '139.234.34.32', '2024-05-21', 2, 9),
(7, 12, 'PG-Test2', '139.102.34.45', '2024-05-21', 1, 9);

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE `status` (
  `id_status` int NOT NULL,
  `status_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `status`
--

INSERT INTO `status` (`id_status`, `status_name`) VALUES
(1, 'Открыта'),
(2, 'Закрыта'),
(3, 'В процессе');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `ID` int NOT NULL,
  `login` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `middle_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `session_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`ID`, `login`, `first_name`, `last_name`, `middle_name`, `email`, `password`, `session_id`, `deleted`, `creation_time`, `last_login_time`) VALUES
(36, 'Ivan', '', '', '', '', '$2y$10$ZctuqY4nBrQ7F61GHZ2/xOP8JIsBmZUNtXBM7qjM1me6VTX5FemYG', '02c8e451689159d60efc7320ddd013f7', 0, '2024-03-05 20:00:31', '2024-05-21 19:20:08'),
(43, 'Roma', NULL, NULL, NULL, NULL, '$2y$10$.wWNfkWBuTUPqf.uXIVjIunsm7NVEeqJYCueTwLhsjJBNuHDbOG1a', '', 0, '2024-03-09 13:31:47', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `fk_issue_comment` (`id_issue`);

--
-- Индексы таблицы `config&equip`
--
ALTER TABLE `config&equip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `configuration_id` (`configuration_id`,`equipments_id`),
  ADD KEY `config&equip_ibfk_1` (`equipments_id`);

--
-- Индексы таблицы `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id_issue`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `fk_issues_status` (`id_status`),
  ADD KEY `fk_issues_marks` (`id_mark`),
  ADD KEY `fk_user_issue` (`id_user`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`id_mark`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_messages`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Индексы таблицы `model&config`
--
ALTER TABLE `model&config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_id` (`model_id`,`configuration_id`),
  ADD KEY `configuration_id` (`configuration_id`);

--
-- Индексы таблицы `model&equip`
--
ALTER TABLE `model&equip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_id` (`model_id`,`equipment_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Индексы таблицы `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `configuration_id` (`configuration_id`),
  ADD KEY `model_id` (`model_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `config&equip`
--
ALTER TABLE `config&equip`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `issues`
--
ALTER TABLE `issues`
  MODIFY `id_issue` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `marks`
--
ALTER TABLE `marks`
  MODIFY `id_mark` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id_messages` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `model&config`
--
ALTER TABLE `model&config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `model&equip`
--
ALTER TABLE `model&equip`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `models`
--
ALTER TABLE `models`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `status`
--
ALTER TABLE `status`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_issue_comment` FOREIGN KEY (`id_issue`) REFERENCES `issues` (`id_issue`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `config&equip`
--
ALTER TABLE `config&equip`
  ADD CONSTRAINT `config&equip_ibfk_1` FOREIGN KEY (`equipments_id`) REFERENCES `equipments` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `config&equip_ibfk_2` FOREIGN KEY (`configuration_id`) REFERENCES `configurations` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `fk_issues_marks` FOREIGN KEY (`id_mark`) REFERENCES `marks` (`id_mark`),
  ADD CONSTRAINT `fk_issues_status` FOREIGN KEY (`id_status`) REFERENCES `status` (`id_status`),
  ADD CONSTRAINT `fk_user_issue` FOREIGN KEY (`id_user`) REFERENCES `users` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Ограничения внешнего ключа таблицы `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`ID`);

--
-- Ограничения внешнего ключа таблицы `model&config`
--
ALTER TABLE `model&config`
  ADD CONSTRAINT `model&config_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `model&config_ibfk_2` FOREIGN KEY (`configuration_id`) REFERENCES `configurations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `model&equip`
--
ALTER TABLE `model&equip`
  ADD CONSTRAINT `model&equip_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `model&equip_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id_client`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `products_ibfk_4` FOREIGN KEY (`configuration_id`) REFERENCES `configurations` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
