-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 26 2025 г., 10:06
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `meme_posters_shop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(120) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 1, 'Вячеслав', 'byacheslav.2005@gmail.com', 'еврврвр', '2025-12-25 22:20:55'),
(2, 1, 'Вячеслав', 'byacheslav.2005@gmail.com', 'еврврвр', '2025-12-25 22:21:00'),
(3, 1, 'Вячеслав', 'byacheslav.2005@gmail.com', 'еврврвр', '2025-12-25 22:23:51'),
(4, 1, 'Вячеслав', 'byacheslav.2005@gmail.com', 'аоено', '2025-12-25 22:23:57'),
(5, 1, 'Вячеслав', 'byacheslav.2005@gmail.com', 'аоено', '2025-12-25 22:24:48'),
(6, NULL, 'Баранов Вячеслав 241-362', 'chel.x.2005@gmail.com', 'jftftyjktkj', '2025-12-26 08:06:54');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(160) NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `short_desc` varchar(255) NOT NULL,
  `full_desc` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specs`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `short_desc`, `full_desc`, `image`, `stock`, `specs`) VALUES
(1, 'Плакат \"This is Fine\"', 1300, 'Классика про спокойствие в хаосе', 'Плакат с мемом \"This is Fine\". Идеально для офиса, учёбы и жизни в дедлайнах.', 'assets/img/this_is_fine.jpg', 10, '{\"Размер\": \"600х420 мм\", \"Бумага\": \"Матовая\"}'),
(3, 'Плакат \"Cat Typing\"', 1100, 'Когда делаешь проект в 3 ночи', 'Мем с котом за ноутбуком. Подходит для программистов и студентов.', 'assets/img/cat.jpg', 15, '{\"Размер\": \"360х360\", \"Бумага\": \"Матовая\"}'),
(4, 'Плакат \"Doge\"', 1200, 'Легендарный Doge', 'Классический мем с Doge. Поднимает настроение в любой комнате.', 'assets/img/doge.jpg', 12, '{\"Размер\": \"525х350 мм\", \"Бумага\": \"Матовая\"}'),
(5, 'Плакат \"Pepe\"', 1300, 'Pepe в разных состояниях', 'Pepe — мем на все случаи жизни.', 'assets/img/pepe.jpg', 10, '{\"Размер\": \"640х360 мм\", \"Бумага\": \"Матовая\"}'),
(6, 'Плакат \"Stonks\"', 1500, 'Когда инвестиции пошли вверх', 'Мем про финансы, успех и абсурд.', 'assets/img/stonks.jpg', 8, '{\"Размер\": \"640х360 мм\", \"Бумага\": \"Глянцевая\"}'),
(9, 'Плакат \"Grumpy Cat\"', 1150, 'Недовольство по умолчанию', 'Для тех, кто не любит понедельники.', 'assets/img/grumpy_cat.jpg', 7, '{\"Размер\": \"480х340 мм\", \"Бумага\": \"Матовая\"}'),
(11, 'Плакат \"Galaxy Brain\"', 1500, 'Уровни мышления', 'Чем выше — тем страннее.', 'assets/img/galaxy_brain.jpg', 10, '{\"Размер\": \"640х360 мм\", \"Бумага\": \"Глянцевая\"}'),
(15, 'Плакат \"Surprised Pikachu\"', 1400, 'Неожиданно', 'Когда результат был очевиден.', 'assets/img/surprised_pikachu.jpg', 11, '{\"Размер\": \"720х524 мм\", \"Бумага\": \"Матовая\"}'),
(16, 'Плакат \"Disaster Girl\"', 1300, 'Хаос под контролем', 'Когда ты знаешь, что будет плохо.', 'assets/img/disaster_girl.jpg', 6, '{\"Размер\": \"640х360 мм\", \"Бумага\": \"Матовая\"}'),
(17, 'Плакат \"Yes Chad\"', 1600, 'Уверенность 100%', 'Для альфа-настроения.', 'assets/img/yes_chad.jpg', 5, '{\"Размер\": \"680x709 мм\", \"Бумага\": \"Глянцевая\"}');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `pass_hash` varchar(255) NOT NULL,
  `name` varchar(80) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `pass_hash`, `name`, `created_at`) VALUES
(1, 'byacheslav.2005@gmail.com', '$2y$10$pcUOGbMjjWCICPIvJ.Yvx.oYFQ0yTrUuwYri2V/VnkDaxEA74icAC', 'Вячеслав', '2025-12-23 17:45:49'),
(2, 'byacheslav.200@gmail.com', '$2y$10$zWyrZNLmtR/LQ8K0a5toEuJBfW0xH6Pj0oqfvBY9IcJ3N/IPJmFn2', 'Вячеслав Баранов', '2025-12-25 21:55:58');

-- --------------------------------------------------------

--
-- Структура таблицы `wishlist`
--

CREATE TABLE `wishlist` (
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
