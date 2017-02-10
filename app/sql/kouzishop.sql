-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Фев 09 2017 г., 12:20
-- Версия сервера: 10.1.13-MariaDB
-- Версия PHP: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kouzishop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `abouts`
--

CREATE TABLE `abouts` (
  `id` int(8) NOT NULL,
  `about` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `abouts`
--

INSERT INTO `abouts` (`id`, `about`) VALUES
(1, '<h2>Описание</h2><p>КОУЗИ 250Вт - прогреет площадь 5м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 8 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>'),
(2, '<h2>Описание</h2><p>КОУЗИ 320Вт - прогреет площадь 7м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 8 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>'),
(3, '<h2>Описание</h2><p>КОУЗИ 450Вт - прогреет площадь 10м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 8 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>'),
(4, '<h2>Описание</h2><p>КОУЗИ 750Вт - прогреет площадь 15м2</p><ol><li>Модели: М1,М2,М3</li><li>Масса: 14 кг</li><li>КПД прибора: 99,7-99,9%</li><li>Габариты(ШхВхГ):<br>    М1 - 700х580х30мм<br>    М2 - 750х500х30мм<br>    М3 - 950х350х33мм</li></ol>'),
(5, '<h2>Описание</h2><p>контролирует заданную температуру в помещении</p>'),
(6, '<h2>Описание</h2><p>Подставки для напольного размещения</p>');

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE `articles` (
  `id` int(8) NOT NULL,
  `articul` int(8) NOT NULL DEFAULT '0',
  `count` int(8) NOT NULL DEFAULT '0',
  `comment` varchar(150) NOT NULL,
  `id_order` int(8) NOT NULL DEFAULT '0',
  `status` int(8) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `articles`
--


-- --------------------------------------------------------

--
-- Структура таблицы `city`
--

CREATE TABLE `city` (
  `id` int(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(8) NOT NULL DEFAULT '0',
  `curier` int(8) NOT NULL DEFAULT '0',
  `time` varchar(50) NOT NULL DEFAULT '7-10',
  `address` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `city`
--

INSERT INTO `city` (`id`, `name`, `price`, `curier`, `time`, `address`) VALUES
(1, 'Челябинск', 0, 0, '1-2 ', 'ул. Энгельса, д. 43, оф. 514'),
(2, 'Москва', 1000, 500, '4-7', ''),
(5, 'Екатеринбург', 800, 300, '1-3', '');

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` int(8) NOT NULL,
  `id_cart` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients`
--


-- --------------------------------------------------------

--
-- Структура таблицы `ordercrm`
--

CREATE TABLE `ordercrm` (
  `id` int(8) NOT NULL,
  `order_id` int(11) NOT NULL,
  `crm_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ordercrm`
--


-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(8) NOT NULL,
  `id_client` int(8) NOT NULL DEFAULT '0',
  `id_info` int(8) NOT NULL DEFAULT '0',
  `status` int(8) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Структура таблицы `ordersinfo`
--

CREATE TABLE `ordersinfo` (
  `id` int(8) NOT NULL,
  `type` int(8) NOT NULL DEFAULT '0',
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `pname` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cname` varchar(250) NOT NULL,
  `inn` varchar(50) NOT NULL,
  `companyname` varchar(250) NOT NULL,
  `cphone` varchar(30) NOT NULL,
  `cemail` varchar(50) NOT NULL,
  `city` varchar(150) NOT NULL,
  `address` varchar(500) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `logistic` int(8) NOT NULL DEFAULT '0',
  `payment` int(8) NOT NULL DEFAULT '0',
  `date` datetime 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `img` varchar(50) NOT NULL,
  `info` varchar(500) NOT NULL,
  `id_about` int(8) NOT NULL DEFAULT '0',
  `price` int(8) NOT NULL DEFAULT '0',
  `articul` int(16) NOT NULL DEFAULT '0',
  `type` varchar(5) NOT NULL DEFAULT 'i',
  `model` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `img`, `info`, `id_about`, `price`, `articul`, `type`, `model`) VALUES
(1, 'КОУЗИ 250Вт', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5200, 10000, 'd', '1, 3, 4'),
(2, 'КОУЗИ М1 250Вт Белый', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5200, 10111, 'i', ''),
(3, 'КОУЗИ М1 250Вт ', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5500, 10112, 'i', ''),
(4, 'КОУЗИ М1 250ВтK Белый', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5400, 10121, 'i', ''),
(5, 'КОУЗИ М1 250ВтK ', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5700, 10122, 'i', ''),
(6, 'КОУЗИ М2 250Вт Белый', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5200, 10211, 'i', ''),
(7, 'КОУЗИ М2 250Вт ', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5500, 10212, 'i', ''),
(8, 'КОУЗИ М2 250ВтK Белый', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5400, 10221, 'i', ''),
(9, 'КОУЗИ М2 250ВтK ', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5700, 10222, 'i', ''),
(10, 'КОУЗИ М3 250Вт Белый', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5200, 10311, 'i', ''),
(11, 'КОУЗИ М3 250Вт ', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5500, 10312, 'i', ''),
(12, 'КОУЗИ М3 250ВтK Белый', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5400, 10321, 'i', ''),
(13, 'КОУЗИ М3 250ВтK ', 'k250.jpg', 'КОУЗИ 250Вт - прогреет площадь 5м2', 1, 5700, 10322, 'i', ''),
(15, 'КОУЗИ 320Вт', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5200, 20000, 'd', '1, 3, 4'),
(16, 'КОУЗИ М1 320Вт Белый', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5200, 20111, 'i', ''),
(17, 'КОУЗИ М1 320Вт ', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5500, 20112, 'i', ''),
(18, 'КОУЗИ М1 320ВтK Белый', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5400, 20121, 'i', ''),
(19, 'КОУЗИ М1 320ВтK ', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5700, 20122, 'i', ''),
(20, 'КОУЗИ М2 320Вт Белый', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5200, 20211, 'i', ''),
(21, 'КОУЗИ М2 320Вт ', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5500, 20212, 'i', ''),
(22, 'КОУЗИ М2 320ВтK Белый', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5400, 20221, 'i', ''),
(23, 'КОУЗИ М2 320ВтK ', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5700, 20222, 'i', ''),
(24, 'КОУЗИ М3 320Вт Белый', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5200, 20311, 'i', ''),
(25, 'КОУЗИ М3 320Вт ', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5500, 20312, 'i', ''),
(26, 'КОУЗИ М3 320ВтK Белый', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5400, 20321, 'i', ''),
(27, 'КОУЗИ М3 320ВтK ', 'k320.jpg', 'КОУЗИ 320Вт - прогреет площадь 7м2', 2, 5700, 20322, 'i', ''),
(28, 'КОУЗИ 450Вт', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5200, 30000, 'd', '1, 3, 4'),
(29, 'КОУЗИ М1 450Вт Белый', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5200, 30111, 'i', ''),
(30, 'КОУЗИ М1 450Вт ', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5500, 30112, 'i', ''),
(31, 'КОУЗИ М1 450ВтK Белый', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5400, 30121, 'i', ''),
(32, 'КОУЗИ М1 450ВтK ', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5700, 30122, 'i', ''),
(33, 'КОУЗИ М2 450Вт Белый', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5200, 30211, 'i', ''),
(34, 'КОУЗИ М2 450Вт ', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5500, 30212, 'i', ''),
(35, 'КОУЗИ М2 450ВтK Белый', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5400, 30221, 'i', ''),
(36, 'КОУЗИ М2 450ВтK ', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5700, 30222, 'i', ''),
(37, 'КОУЗИ М3 450Вт Белый', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5200, 30311, 'i', ''),
(38, 'КОУЗИ М3 450Вт ', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5500, 30312, 'i', ''),
(39, 'КОУЗИ М3 450ВтK Белый', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5400, 30321, 'i', ''),
(40, 'КОУЗИ М3 450ВтK ', 'k450.jpg', 'КОУЗИ 450Вт - прогреет площадь 10м2', 3, 5700, 30322, 'i', ''),
(41, 'КОУЗИ 750Вт', 'k750.jpg', 'КОУЗИ 750Вт - прогреет площадь 15м2', 4, 6900, 40000, 'd', '2, 3, 4'),
(42, 'КОУЗИ Н2 750Вт Белый', 'k750.jpg', 'КОУЗИ 750Вт - прогреет площадь 15м2', 4, 6900, 40411, 'i', ''),
(43, 'КОУЗИ Н2 750Вт ', 'k750.jpg', 'КОУЗИ 750Вт - прогреет площадь 15м2', 4, 7200, 40412, 'i', ''),
(44, 'КОУЗИ Н2 750ВтK Белый', 'k750.jpg', 'КОУЗИ 750Вт - прогреет площадь 15м2', 4, 7100, 40421, 'i', ''),
(45, 'КОУЗИ Н2 750ВтK ', 'k750.jpg', 'КОУЗИ 750Вт - прогреет площадь 15м2', 4, 7400, 40422, 'i', ''),
(46, 'КОУЗИ Н3 750Вт Белый', 'k750.jpg', 'КОУЗИ 750Вт - прогреет площадь 15м2', 4, 6900, 40511, 'i', ''),
(47, 'КОУЗИ Н3 750Вт ', 'k750.jpg', 'КОУЗИ 750Вт - прогреет площадь 15м2', 4, 7200, 40512, 'i', ''),
(48, 'КОУЗИ Н3 750ВтK Белый', 'k750.jpg', 'КОУЗИ 750Вт - прогреет площадь 15м2', 4, 7100, 40521, 'i', ''),
(49, 'КОУЗИ Н3 750ВтK ', 'k750.jpg', 'КОУЗИ 750Вт - прогреет площадь 15м2', 4, 7400, 40522, 'i', ''),
(50, 'Терморегулятор', 'term.jpg', 'контролирует заданную температуру в помещении', 5, 1500, 500, 'v', ''),
(51, 'Ножки М1-2', 'n1.jpg', 'Для КОУЗИ в корпусе М1 и М2', 6, 400, 600, 'd', '4'),
(52, 'Ножки М3', 'n1.jpg', 'Для КОУЗИ в корпусе М3', 6, 400, 700, 'd', '4'),
(53, 'Ножки Н2-3', 'n1.jpg', 'Для КОУЗИ в корпусе Н2-Н3', 6, 400, 800, 'd', '4'),
(54, 'Ножки М1-2 белые', 'n1.jpg', 'Для КОУЗИ в корпусе М1 и М2', 6, 400, 601, 'i', ''),
(55, 'Ножки М1-2 ', 'n1.jpg', 'Для КОУЗИ в корпусе М1 и М2', 6, 400, 602, 'i', ''),
(56, 'Ножки М3 белые', 'n1.jpg', 'Для КОУЗИ в корпусе М3', 6, 400, 701, 'i', ''),
(57, 'Ножки М3 ', 'n1.jpg', 'Для КОУЗИ в корпусе М3', 6, 400, 702, 'i', ''),
(58, 'Ножки Н2-3 белые', 'n1.jpg', 'Для КОУЗИ в корпусе Н2-Н3', 6, 400, 801, 'i', ''),
(59, 'Ножки Н2-3 ', 'n1.jpg', 'Для КОУЗИ в корпусе Н2-Н3', 6, 400, 802, 'i', '');

-- --------------------------------------------------------

--
-- Структура таблицы `taskcreatedeal`
--

CREATE TABLE `taskcreatedeal` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `orderinfo` text NOT NULL,
  `products` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Структура таблицы `variationgroup`
--

CREATE TABLE `variationgroup` (
  `id` int(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'size'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `variationgroup`
--

INSERT INTO `variationgroup` (`id`, `name`, `type`) VALUES
(1, 'Размер корпуса', 'size'),
(2, 'Размер корпуса', 'size'),
(3, 'Модификация', 'key'),
(4, 'Цвет', 'color');

-- --------------------------------------------------------

--
-- Структура таблицы `variations`
--

CREATE TABLE `variations` (
  `id` int(8) NOT NULL,
  `id_group` int(8) NOT NULL DEFAULT '0',
  `text` varchar(150) NOT NULL,
  `artmod` int(8) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `variations`
--

INSERT INTO `variations` (`id`, `id_group`, `text`, `artmod`) VALUES
(1, 1, 'M1 - 700x580x30', 100),
(2, 1, 'M2 - 750x500x30', 200),
(3, 1, 'M3 - 950x350x33', 300),
(4, 2, 'Н2 - 750x500x40', 400),
(5, 2, 'Н3 - 1100x350x40', 500),
(6, 3, 'Под монтаж с терморегулятором', 10),
(7, 3, 'Для включения в разетку(+200 руб.)', 20),
(8, 4, 'Цвет белый', 1),
(9, 4, 'Цветной RAL(+300 руб.)', 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `abouts`
--
ALTER TABLE `abouts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ordercrm`
--
ALTER TABLE `ordercrm`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ordersinfo`
--
ALTER TABLE `ordersinfo`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `taskcreatedeal`
--
ALTER TABLE `taskcreatedeal`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `variationgroup`
--
ALTER TABLE `variationgroup`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `variations`
--
ALTER TABLE `variations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `abouts`
--
ALTER TABLE `abouts`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=466;
--
-- AUTO_INCREMENT для таблицы `city`
--
ALTER TABLE `city`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `ordercrm`
--
ALTER TABLE `ordercrm`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT для таблицы `ordersinfo`
--
ALTER TABLE `ordersinfo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT для таблицы `taskcreatedeal`
--
ALTER TABLE `taskcreatedeal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `variationgroup`
--
ALTER TABLE `variationgroup`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `variations`
--
ALTER TABLE `variations`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
