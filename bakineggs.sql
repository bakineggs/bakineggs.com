-- phpMyAdmin SQL Dump
-- version 2.11.1-rc1
-- http://www.phpmyadmin.net
--
-- Host: db.madbytes.net
-- Generation Time: Apr 20, 2008 at 01:44 AM
-- Server version: 5.1.11
-- PHP Version: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `bakineggs`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `body` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
