-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Мар 26 2026 г., 00:10
-- Версия сервера: 10.6.25-MariaDB
-- Версия PHP: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tildamuk_Enterprise-database`
--

-- --------------------------------------------------------

--
-- Структура таблицы `assessments`
--

CREATE TABLE `assessments` (
  `id` int(11) NOT NULL,
  `public_id` varchar(120) NOT NULL,
  `child_id` int(11) DEFAULT NULL,
  `overall_score` int(11) NOT NULL DEFAULT 0,
  `diagnosis` varchar(255) NOT NULL DEFAULT '',
  `confidence` int(11) NOT NULL DEFAULT 0,
  `transcription` text DEFAULT NULL,
  `ai_summary` text DEFAULT NULL,
  `strengths_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`strengths_json`)),
  `recommendations_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`recommendations_json`)),
  `sounds_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sounds_json`)),
  `raw_payload_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_payload_json`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `child_id` int(11) DEFAULT NULL,
  `session_id` varchar(120) NOT NULL,
  `role` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `children`
--

CREATE TABLE `children` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `parent_name` varchar(150) DEFAULT NULL,
  `parent_phone` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Активен',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `exercise_progress`
--

CREATE TABLE `exercise_progress` (
  `id` int(11) NOT NULL,
  `child_id` int(11) DEFAULT NULL,
  `exercise_template_id` int(11) NOT NULL,
  `stars_earned` int(11) DEFAULT 0,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `exercise_templates`
--

CREATE TABLE `exercise_templates` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT 10,
  `difficulty` varchar(100) DEFAULT 'Легко',
  `sound` varchar(20) DEFAULT '',
  `stars` int(11) DEFAULT 1,
  `video_url` varchar(500) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `therapists`
--

CREATE TABLE `therapists` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `public_id` (`public_id`),
  ADD KEY `idx_assessments_child_id` (`child_id`),
  ADD KEY `idx_assessments_created_at` (`created_at`);

--
-- Индексы таблицы `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chat_messages_child_id` (`child_id`),
  ADD KEY `idx_chat_messages_session_id` (`session_id`),
  ADD KEY `idx_chat_messages_created_at` (`created_at`);

--
-- Индексы таблицы `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `exercise_progress`
--
ALTER TABLE `exercise_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_exercise_progress_child_id` (`child_id`),
  ADD KEY `idx_exercise_progress_template_id` (`exercise_template_id`);

--
-- Индексы таблицы `exercise_templates`
--
ALTER TABLE `exercise_templates`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `therapists`
--
ALTER TABLE `therapists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `assessments`
--
ALTER TABLE `assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `children`
--
ALTER TABLE `children`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `exercise_progress`
--
ALTER TABLE `exercise_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `exercise_templates`
--
ALTER TABLE `exercise_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `therapists`
--
ALTER TABLE `therapists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `fk_assessments_child` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `fk_chat_child` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `exercise_progress`
--
ALTER TABLE `exercise_progress`
  ADD CONSTRAINT `fk_progress_child` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_progress_exercise` FOREIGN KEY (`exercise_template_id`) REFERENCES `exercise_templates` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
