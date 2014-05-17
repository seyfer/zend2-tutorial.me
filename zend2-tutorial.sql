-- phpMyAdmin SQL Dump
-- version 4.2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 17, 2014 at 03:09 PM
-- Server version: 5.5.37-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zend2-tutorial`
--

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE IF NOT EXISTS `album` (
`id` int(11) NOT NULL,
  `artist` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=157 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE IF NOT EXISTS `chat_messages` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `image_uploads`
--

CREATE TABLE IF NOT EXISTS `image_uploads` (
`id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `myuser`
--

CREATE TABLE IF NOT EXISTS `myuser` (
`id` int(10) unsigned NOT NULL,
  `name` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
`id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `article` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE IF NOT EXISTS `uploads` (
`id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `uploads_sharing`
--

CREATE TABLE IF NOT EXISTS `uploads_sharing` (
`id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_id` int(10) unsigned NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) unsigned DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album`
--
ALTER TABLE `album`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image_uploads`
--
ALTER TABLE `image_uploads`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `filename` (`filename`);

--
-- Indexes for table `myuser`
--
ALTER TABLE `myuser`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `idx_email` (`email`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `filename` (`filename`);

--
-- Indexes for table `uploads_sharing`
--
ALTER TABLE `uploads_sharing`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `upload_id` (`upload_id`,`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=157;
--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `image_uploads`
--
ALTER TABLE `image_uploads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `myuser`
--
ALTER TABLE `myuser`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `uploads_sharing`
--
ALTER TABLE `uploads_sharing`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
