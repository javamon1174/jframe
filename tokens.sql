-- phpMyAdmin SQL Dump
-- version 4.8.0-dev
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.30.23
-- 생성 시간: 17-07-22 09:20
-- 서버 버전: 8.0.0-dmr
-- PHP 버전: 7.0.19-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `menagerie`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `tokens`
--

CREATE TABLE `tokens` (
  `seq` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `password` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `token` int(11) NOT NULL,
  `created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='auth';

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`seq`),
  ADD UNIQUE KEY `identity` (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `tokens`
--
ALTER TABLE `tokens`
  MODIFY `seq` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
