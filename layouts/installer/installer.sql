-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2024 at 09:46 PM
-- Server version: 8.0.28
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grupo`
--

-- --------------------------------------------------------

--
-- Table structure for table `gr_audio_player`
--

CREATE TABLE `gr_audio_player` (
  `audio_content_id` bigint NOT NULL,
  `audio_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `audio_description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `audio_type` int NOT NULL DEFAULT '1' COMMENT '1 = Radio 2 = Playlist',
  `radio_stream_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `now_playing_info_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `streaming_server` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `disabled` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_badges`
--

CREATE TABLE `gr_badges` (
  `badge_id` bigint NOT NULL,
  `string_constant` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `badge_category` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'profile',
  `disabled` int NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_badges_assigned`
--

CREATE TABLE `gr_badges_assigned` (
  `badge_assigned_id` bigint NOT NULL,
  `badge_id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `group_id` bigint DEFAULT NULL,
  `assigned_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_bank_transfer_receipts`
--

CREATE TABLE `gr_bank_transfer_receipts` (
  `bank_transfer_receipt_id` bigint NOT NULL,
  `membership_order_id` bigint NOT NULL,
  `receipt_file_name` varchar(199) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_status` int NOT NULL DEFAULT '0' COMMENT '0 = Pending, 1 = Accepted, 2 = Rejected',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_billing_address`
--

CREATE TABLE `gr_billing_address` (
  `billing_address_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `billed_to` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street_address` varchar(199) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_complaints`
--

CREATE TABLE `gr_complaints` (
  `complaint_id` bigint NOT NULL,
  `reason` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `group_message_id` bigint DEFAULT NULL,
  `private_chat_message_id` bigint DEFAULT NULL,
  `comments_by_complainant` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `complainant_user_id` bigint DEFAULT NULL,
  `complaint_status` int NOT NULL DEFAULT '0' COMMENT '0 = Under Review\r\n1 = Action Taken\r\n2 = Rejected',
  `reviewer_user_id` bigint DEFAULT NULL,
  `comments_by_reviewer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_cron_jobs`
--

CREATE TABLE `gr_cron_jobs` (
  `cron_job_id` bigint NOT NULL,
  `cron_job` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cron_job_access_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cron_job_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `last_run_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_css_variables`
--

CREATE TABLE `gr_css_variables` (
  `css_variable_id` bigint NOT NULL,
  `css_variable` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `css_variable_value` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_scheme` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light_mode',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_css_variables`
--

INSERT INTO `gr_css_variables` (`css_variable_id`, `css_variable`, `css_variable_value`, `color_scheme`, `updated_on`) VALUES
(1, 'landing-page-primary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(2, 'landing-page-secondary-bg-color', '#F1F5F9', 'light_mode', '2024-05-04 14:36:33'),
(3, 'landing-page-tertiary-bg-color', '#CED6DF', 'light_mode', '2024-05-04 14:36:33'),
(4, 'landing-page-quaternary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(5, 'landing-page-quinary-bg-color', '#FFF497', 'light_mode', '2024-05-04 14:36:33'),
(6, 'landing-page-senary-bg-color', '#000000', 'light_mode', '2024-05-04 14:36:33'),
(7, 'landing-page-septenary-bg-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(8, 'landing-page-primary-text-color', '#1B1E60', 'light_mode', '2024-05-04 14:36:33'),
(9, 'landing-page-secondary-text-color', '#7F8097', 'light_mode', '2024-05-04 14:36:33'),
(10, 'landing-page-tertiary-text-color', '#CFCFCF', 'light_mode', '2024-05-04 14:36:33'),
(11, 'landing-page-quaternary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(12, 'landing-page-quinary-text-color', '#FFEB3B', 'light_mode', '2024-05-04 14:36:33'),
(13, 'landing-page-senary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(14, 'landing-page-septenary-text-color', '#3F3F3F', 'light_mode', '2024-05-04 14:36:33'),
(15, 'landing-page-octonary-text-color', '#797F85', 'light_mode', '2024-05-04 14:36:33'),
(16, 'landing-page-primary-border-color', '#B9BFC6', 'light_mode', '2024-05-04 14:36:33'),
(17, 'landing-page-secondary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(18, 'landing-page-primary-font-size', '26px', 'light_mode', '2024-05-04 14:36:33'),
(19, 'landing-page-secondary-font-size', '18px', 'light_mode', '2024-05-04 14:36:33'),
(20, 'landing-page-tertiary-font-size', '17px', 'light_mode', '2024-05-04 14:36:33'),
(21, 'landing-page-quaternary-font-size', '16px', 'light_mode', '2024-05-04 14:36:33'),
(22, 'landing-page-quinary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(23, 'landing-page-senary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(24, 'chat-page-primary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(25, 'chat-page-secondary-bg-color', '#F06292', 'light_mode', '2024-05-04 14:36:33'),
(26, 'chat-page-tertiary-bg-color', '#DBE1E7', 'light_mode', '2024-05-04 14:36:33'),
(27, 'chat-page-quaternary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(28, 'chat-page-quinary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(29, 'chat-page-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(30, 'chat-page-primary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(31, 'chat-page-secondary-font-size', '18px', 'light_mode', '2024-05-04 14:36:33'),
(32, 'chat-page-tertiary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(33, 'chat-page-primary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(34, 'chat-page-secondary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(35, 'chat-page-tertiary-text-color', '#808080', 'light_mode', '2024-05-04 14:36:33'),
(36, 'side-navigation-primary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(37, 'side-navigation-secondary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(38, 'side-navigation-tertiary-bg-color', '#EFF1F3', 'light_mode', '2024-05-04 14:36:33'),
(39, 'side-navigation-quaternary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(40, 'side-navigation-primary-text-color', '#878787', 'light_mode', '2024-05-04 14:36:33'),
(41, 'side-navigation-secondary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(42, 'side-navigation-tertiary-text-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(43, 'side-navigation-quaternary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(44, 'side-navigation-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(45, 'side-navigation-primary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(46, 'side-navigation-secondary-font-size', '13px', 'light_mode', '2024-05-04 14:36:33'),
(47, 'side-navigation-tertiary-font-size', '20px', 'light_mode', '2024-05-04 14:36:33'),
(48, 'left-side-content-primary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(49, 'left-side-content-secondary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(50, 'left-side-content-tertiary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(51, 'left-side-content-quaternary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(52, 'left-side-content-quinary-bg-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(53, 'left-side-content-senary-bg-color', '#F8D7DA', 'light_mode', '2024-05-04 14:36:33'),
(54, 'left-side-content-septenary-bg-color', '#FFC107', 'light_mode', '2024-05-04 14:36:33'),
(55, 'left-side-content-octonary-bg-color', '#FF5722', 'light_mode', '2024-05-04 14:36:33'),
(56, 'left-side-content-nonary-bg-color', '#F48FB1', 'light_mode', '2024-05-04 14:36:33'),
(57, 'left-side-content-denary-bg-color', '#F06292', 'light_mode', '2024-05-04 14:36:33'),
(58, 'left-side-content-primary-text-color', '#696767', 'light_mode', '2024-05-04 14:36:33'),
(59, 'left-side-content-secondary-text-color', '#828588', 'light_mode', '2024-05-04 14:36:33'),
(60, 'left-side-content-tertiary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(61, 'left-side-content-quaternary-text-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(62, 'left-side-content-quinary-text-color', '#721C24', 'light_mode', '2024-05-04 14:36:33'),
(63, 'left-side-content-senary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(64, 'left-side-content-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(65, 'left-side-content-secondary-border-color', '#E88A93', 'light_mode', '2024-05-04 14:36:33'),
(66, 'left-side-content-tertiary-border-color', '#EC407A', 'light_mode', '2024-05-04 14:36:33'),
(67, 'left-side-content-quaternary-border-color', '#D7DDE3', 'light_mode', '2024-05-04 14:36:33'),
(68, 'left-side-content-primary-font-size', '13px', 'light_mode', '2024-05-04 14:36:33'),
(69, 'left-side-content-secondary-font-size', '12px', 'light_mode', '2024-05-04 14:36:33'),
(70, 'left-side-content-tertiary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(71, 'left-side-content-quaternary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(72, 'left-side-content-quinary-font-size', '11px', 'light_mode', '2024-05-04 14:36:33'),
(73, 'left-side-content-senary-font-size', '25px', 'light_mode', '2024-05-04 14:36:33'),
(74, 'left-side-content-septenary-font-size', '20px', 'light_mode', '2024-05-04 14:36:33'),
(75, 'audio-player-primary-bg-color', '#050517', 'light_mode', '2024-05-04 14:36:33'),
(76, 'audio-player-secondary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(77, 'audio-player-tertiary-bg-color', '#FF4E8A', 'light_mode', '2024-05-04 14:36:33'),
(78, 'audio-player-quaternary-bg-color', '#202030', 'light_mode', '2024-05-04 14:36:33'),
(79, 'audio-player-quinary-bg-color', '#EFF1F3', 'light_mode', '2024-05-04 14:36:33'),
(80, 'audio-player-senary-bg-color', '#E45E8C', 'light_mode', '2024-05-04 14:36:33'),
(81, 'audio-player-primary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(82, 'audio-player-secondary-text-color', '#696969', 'light_mode', '2024-05-04 14:36:33'),
(83, 'audio-player-tertiary-text-color', '#97A1A9', 'light_mode', '2024-05-04 14:36:33'),
(84, 'audio-player-quaternary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(85, 'audio-player-quinary-text-color', '#959595', 'light_mode', '2024-05-04 14:36:33'),
(86, 'audio-player-senary-text-color', '#FF6D9F', 'light_mode', '2024-05-04 14:36:33'),
(87, 'audio-player-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(88, 'audio-player-primary-font-size', '20px', 'light_mode', '2024-05-04 14:36:33'),
(89, 'audio-player-secondary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(90, 'audio-player-tertiary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(91, 'audio-player-quaternary-font-size', '13px', 'light_mode', '2024-05-04 14:36:33'),
(92, 'audio-player-quinary-font-size', '27px', 'light_mode', '2024-05-04 14:36:33'),
(93, 'audio-player-senary-font-size', '11px', 'light_mode', '2024-05-04 14:36:33'),
(94, 'form-primary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(95, 'form-secondary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(96, 'form-tertiary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(97, 'form-quaternary-bg-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(98, 'form-quinary-bg-color', '#000000', 'light_mode', '2024-05-04 14:36:33'),
(99, 'form-senary-bg-color', '#F8D7DA', 'light_mode', '2024-05-04 14:36:33'),
(100, 'form-septenary-bg-color', '#E8EBEF', 'light_mode', '2024-05-04 14:36:33'),
(101, 'form-octonary-bg-color', '#F06292', 'light_mode', '2024-05-04 14:36:33'),
(102, 'form-primary-text-color', '#808080', 'light_mode', '2024-05-04 14:36:33'),
(103, 'form-secondary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(104, 'form-tertiary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(105, 'form-quaternary-text-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(106, 'form-quinary-text-color', '#721C24', 'light_mode', '2024-05-04 14:36:33'),
(107, 'form-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(108, 'form-secondary-border-color', '#E88A93', 'light_mode', '2024-05-04 14:36:33'),
(109, 'form-tertiary-border-color', '#EC407A', 'light_mode', '2024-05-04 14:36:33'),
(110, 'form-primary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(111, 'form-secondary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(112, 'form-tertiary-font-size', '18px', 'light_mode', '2024-05-04 14:36:33'),
(113, 'info-panel-primary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(114, 'info-panel-secondary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(115, 'info-panel-tertiary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(116, 'info-panel-quaternary-bg-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(117, 'info-panel-quinary-bg-color', '#FDFEFE', 'light_mode', '2024-05-04 14:36:33'),
(118, 'info-panel-senary-bg-color', '#BFBDBD', 'light_mode', '2024-05-04 14:36:33'),
(119, 'info-panel-primary-text-color', '#565656', 'light_mode', '2024-05-04 14:36:33'),
(120, 'info-panel-secondary-text-color', '#878688', 'light_mode', '2024-05-04 14:36:33'),
(121, 'info-panel-tertiary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(122, 'info-panel-quaternary-text-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(123, 'info-panel-quinary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(124, 'info-panel-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(125, 'info-panel-secondary-border-color', '#E88A93', 'light_mode', '2024-05-04 14:36:33'),
(126, 'info-panel-tertiary-border-color', '#EC407A', 'light_mode', '2024-05-04 14:36:33'),
(127, 'info-panel-primary-font-size', '16px', 'light_mode', '2024-05-04 14:36:33'),
(128, 'info-panel-secondary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(129, 'info-panel-tertiary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(130, 'info-panel-quaternary-font-size', '13px', 'light_mode', '2024-05-04 14:36:33'),
(131, 'welcome-screen-primary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(132, 'welcome-screen-primary-text-color', '#696767', 'light_mode', '2024-05-04 14:36:33'),
(133, 'welcome-screen-secondary-text-color', '#828588', 'light_mode', '2024-05-04 14:36:33'),
(134, 'welcome-screen-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(135, 'welcome-screen-primary-font-size', '19px', 'light_mode', '2024-05-04 14:36:33'),
(136, 'welcome-screen-secondary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(137, 'welcome-screen-tertiary-font-size', '13px', 'light_mode', '2024-05-04 14:36:33'),
(138, 'custom-page-primary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(139, 'custom-page-secondary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(140, 'custom-page-primary-text-color', '#8F8F8F', 'light_mode', '2024-05-04 14:36:33'),
(141, 'custom-page-secondary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(142, 'custom-page-tertiary-text-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(143, 'custom-page-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(144, 'custom-page-primary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(145, 'custom-page-secondary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(146, 'statistics-primary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(147, 'statistics-secondary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(148, 'statistics-tertiary-bg-color', '#050517', 'light_mode', '2024-05-04 14:36:33'),
(149, 'statistics-quaternary-bg-color', '#262630', 'light_mode', '2024-05-04 14:36:33'),
(150, 'statistics-quinary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(151, 'statistics-senary-bg-color', '#A5A5A5', 'light_mode', '2024-05-04 14:36:33'),
(152, 'statistics-septenary-bg-color', '#FFEB3B', 'light_mode', '2024-05-04 14:36:33'),
(153, 'statistics-primary-text-color', '#4A4A49', 'light_mode', '2024-05-04 14:36:33'),
(154, 'statistics-secondary-text-color', '#808080', 'light_mode', '2024-05-04 14:36:33'),
(155, 'statistics-tertiary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(156, 'statistics-quaternary-text-color', '#C7C7C7', 'light_mode', '2024-05-04 14:36:33'),
(157, 'statistics-quinary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(158, 'statistics-senary-text-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(159, 'statistics-septenary-text-color', '#000000', 'light_mode', '2024-05-04 14:36:33'),
(160, 'statistics-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(161, 'statistics-primary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(162, 'statistics-secondary-font-size', '17px', 'light_mode', '2024-05-04 14:36:33'),
(163, 'statistics-tertiary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(164, 'statistics-quaternary-font-size', '19px', 'light_mode', '2024-05-04 14:36:33'),
(165, 'membership_info-primary-bg-color', '#F4F4F4', 'light_mode', '2024-05-04 14:36:33'),
(166, 'membership_info-secondary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(167, 'membership_info-tertiary-bg-color', '#F3F3F4', 'light_mode', '2024-05-04 14:36:33'),
(168, 'membership_info-quaternary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(169, 'membership_info-quinary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(170, 'membership_info-senary-bg-color', '#EBEFF3', 'light_mode', '2024-05-04 14:36:33'),
(171, 'membership_info-septenary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(172, 'membership_info-octonary-bg-color', '#FFC107', 'light_mode', '2024-05-04 14:36:33'),
(173, 'membership_info-nonary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(174, 'membership_info-denary-bg-color', '#1B1B1B', 'light_mode', '2024-05-04 14:36:33'),
(175, 'membership_info-primary-text-color', '#202020', 'light_mode', '2024-05-04 14:36:33'),
(176, 'membership_info-secondary-text-color', '#000000', 'light_mode', '2024-05-04 14:36:33'),
(177, 'membership_info-tertiary-text-color', '#727272', 'light_mode', '2024-05-04 14:36:33'),
(178, 'membership_info-quaternary-text-color', '#787878', 'light_mode', '2024-05-04 14:36:33'),
(179, 'membership_info-quinary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(180, 'membership_info-senary-text-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(181, 'membership_info-septenary-text-color', '#000000', 'light_mode', '2024-05-04 14:36:33'),
(182, 'membership_info-octonary-text-color', '#FFC107', 'light_mode', '2024-05-04 14:36:33'),
(183, 'membership_info-nonary-text-color', '#000000', 'light_mode', '2024-05-04 14:36:33'),
(184, 'membership_info-denary-text-color', '#000000', 'light_mode', '2024-05-04 14:36:33'),
(185, 'membership_info-primary-border-color', '#D6D6D6', 'light_mode', '2024-05-04 14:36:33'),
(186, 'membership_info-primary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(187, 'membership_info-secondary-font-size', '17px', 'light_mode', '2024-05-04 14:36:33'),
(188, 'membership_info-tertiary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(189, 'membership_info-quaternary-font-size', '19px', 'light_mode', '2024-05-04 14:36:33'),
(190, 'membership_info-quinary-font-size', '27px', 'light_mode', '2024-05-04 14:36:33'),
(191, 'membership_info-senary-font-size', '16px', 'light_mode', '2024-05-04 14:36:33'),
(192, 'chat-window-primary-bg-color', '#FAFBFC', 'light_mode', '2024-05-04 14:36:33'),
(193, 'chat-window-secondary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(194, 'chat-window-tertiary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(195, 'chat-window-quaternary-bg-color', '#F06292', 'light_mode', '2024-05-04 14:36:33'),
(196, 'chat-window-quinary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(197, 'chat-window-senary-bg-color', '#FFF3CD', 'light_mode', '2024-05-04 14:36:33'),
(198, 'chat-window-septenary-bg-color', '#ECEFF1', 'light_mode', '2024-05-04 14:36:33'),
(199, 'chat-window-octonary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(200, 'chat-window-nonary-bg-color', '#F06292', 'light_mode', '2024-05-04 14:36:33'),
(201, 'chat-window-denary-bg-color', '#9E9E9E', 'light_mode', '2024-05-04 14:36:33'),
(202, 'chat-window-primary-text-color', '#808080', 'light_mode', '2024-05-04 14:36:33'),
(203, 'chat-window-secondary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(204, 'chat-window-tertiary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(205, 'chat-window-quaternary-text-color', '#808080', 'light_mode', '2024-05-04 14:36:33'),
(206, 'chat-window-quinary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(207, 'chat-window-senary-text-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(208, 'chat-window-septenary-text-color', '#856404', 'light_mode', '2024-05-04 14:36:33'),
(209, 'chat-window-octonary-text-color', '#565656', 'light_mode', '2024-05-04 14:36:33'),
(210, 'chat-window-nonary-text-color', '#E45E8C', 'light_mode', '2024-05-04 14:36:33'),
(211, 'chat-window-denary-text-color', '#B1B1B1', 'light_mode', '2024-05-04 14:36:33'),
(212, 'chat-window-undenary-text-color', '#FF9800', 'light_mode', '2024-05-04 14:36:33'),
(213, 'chat-window-duodenary-text-color', '#FFEB3B', 'light_mode', '2024-05-04 14:36:33'),
(214, 'chat-window-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(215, 'chat-window-secondary-border-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(216, 'chat-window-tertiary-border-color', '#F7E2A0', 'light_mode', '2024-05-04 14:36:33'),
(217, 'chat-window-quaternary-border-color', '#607D8B', 'light_mode', '2024-05-04 14:36:33'),
(218, 'chat-window-quinary-border-color', '#EC407A', 'light_mode', '2024-05-04 14:36:33'),
(219, 'chat-window-senary-border-color', '#FFC107', 'light_mode', '2024-05-04 14:36:33'),
(220, 'chat-window-septenary-border-color', '#D7DDE3', 'light_mode', '2024-05-04 14:36:33'),
(221, 'chat-window-primary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(222, 'chat-window-secondary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(223, 'chat-window-tertiary-font-size', '13px', 'light_mode', '2024-05-04 14:36:33'),
(224, 'chat-window-quaternary-font-size', '12px', 'light_mode', '2024-05-04 14:36:33'),
(225, 'chat-window-quinary-font-size', '19px', 'light_mode', '2024-05-04 14:36:33'),
(226, 'chat-window-senary-font-size', '27px', 'light_mode', '2024-05-04 14:36:33'),
(227, 'chat-window-septenary-font-size', '30px', 'light_mode', '2024-05-04 14:36:33'),
(228, 'chat-window-octonary-font-size', '34px', 'light_mode', '2024-05-04 14:36:33'),
(229, 'chat-window-nonary-font-size', '22px', 'light_mode', '2024-05-04 14:36:33'),
(230, 'entry-page-primary-bg-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(231, 'entry-page-secondary-bg-color', '#F7F9FB', 'light_mode', '2024-05-04 14:36:33'),
(232, 'entry-page-tertiary-bg-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(233, 'entry-page-quaternary-bg-color', '#9C27B0', 'light_mode', '2024-05-04 14:36:33'),
(234, 'entry-page-quinary-bg-color', '#9E9E9E', 'light_mode', '2024-05-04 14:36:33'),
(235, 'entry-page-senary-bg-color', '#F06292', 'light_mode', '2024-05-04 14:36:33'),
(236, 'entry-page-primary-text-color', '#808080', 'light_mode', '2024-05-04 14:36:33'),
(237, 'entry-page-secondary-text-color', '#919191', 'light_mode', '2024-05-04 14:36:33'),
(238, 'entry-page-tertiary-text-color', '#23576A', 'light_mode', '2024-05-04 14:36:33'),
(239, 'entry-page-quaternary-text-color', '#FFFFFF', 'light_mode', '2024-05-04 14:36:33'),
(240, 'entry-page-quinary-text-color', '#E91E63', 'light_mode', '2024-05-04 14:36:33'),
(241, 'entry-page-senary-text-color', '#333333', 'light_mode', '2024-05-04 14:36:33'),
(242, 'entry-page-primary-border-color', '#DFE7EF', 'light_mode', '2024-05-04 14:36:33'),
(243, 'entry-page-secondary-border-color', '#A9A9A9', 'light_mode', '2024-05-04 14:36:33'),
(244, 'entry-page-tertiary-border-color', '#DC1F6F', 'light_mode', '2024-05-04 14:36:33'),
(245, 'entry-page-quaternary-border-color', '#D7DDE3', 'light_mode', '2024-05-04 14:36:33'),
(246, 'entry-page-primary-font-size', '14px', 'light_mode', '2024-05-04 14:36:33'),
(247, 'entry-page-secondary-font-size', '15px', 'light_mode', '2024-05-04 14:36:33'),
(248, 'entry-page-tertiary-font-size', '18px', 'light_mode', '2024-05-04 14:36:33'),
(249, 'entry-page-quaternary-font-size', '20px', 'light_mode', '2024-05-04 14:36:33'),
(250, 'entry-page-quinary-font-size', '16px', 'light_mode', '2024-05-04 14:36:33'),
(251, 'entry-page-senary-font-size', '13px', 'light_mode', '2024-05-04 14:36:33'),
(252, 'landing-page-primary-bg-color', '#1D1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(253, 'landing-page-secondary-bg-color', '#131313', 'dark_mode', '2024-05-04 14:36:33'),
(254, 'landing-page-tertiary-bg-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(255, 'landing-page-quaternary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(256, 'landing-page-quinary-bg-color', '#FFF497', 'dark_mode', '2024-05-04 14:36:33'),
(257, 'landing-page-senary-bg-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(258, 'landing-page-septenary-bg-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(259, 'landing-page-primary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(260, 'landing-page-secondary-text-color', '#AFAFAF', 'dark_mode', '2024-05-04 14:36:33'),
(261, 'landing-page-tertiary-text-color', '#CFCFCF', 'dark_mode', '2024-05-04 14:36:33'),
(262, 'landing-page-quaternary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(263, 'landing-page-quinary-text-color', '#FFEB3B', 'dark_mode', '2024-05-04 14:36:33'),
(264, 'landing-page-senary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(265, 'landing-page-septenary-text-color', '#3F3F3F', 'dark_mode', '2024-05-04 14:36:33'),
(266, 'landing-page-octonary-text-color', '#727272', 'dark_mode', '2024-05-04 14:36:33'),
(267, 'landing-page-primary-border-color', '#222222', 'dark_mode', '2024-05-04 14:36:33'),
(268, 'landing-page-secondary-border-color', '#101010', 'dark_mode', '2024-05-04 14:36:33'),
(269, 'landing-page-primary-font-size', '25px', 'dark_mode', '2024-05-04 14:36:33'),
(270, 'landing-page-secondary-font-size', '18px', 'dark_mode', '2024-05-04 14:36:33'),
(271, 'landing-page-tertiary-font-size', '17px', 'dark_mode', '2024-05-04 14:36:33'),
(272, 'landing-page-quaternary-font-size', '16px', 'dark_mode', '2024-05-04 14:36:33'),
(273, 'landing-page-quinary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(274, 'landing-page-senary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(275, 'chat-page-primary-bg-color', '#0F0F0F', 'dark_mode', '2024-05-04 14:36:33'),
(276, 'chat-page-secondary-bg-color', '#F06292', 'dark_mode', '2024-05-04 14:36:33'),
(277, 'chat-page-tertiary-bg-color', '#292929', 'dark_mode', '2024-05-04 14:36:33'),
(278, 'chat-page-quaternary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(279, 'chat-page-quinary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(280, 'chat-page-primary-border-color', '#101010', 'dark_mode', '2024-05-04 14:36:33'),
(281, 'chat-page-primary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(282, 'chat-page-secondary-font-size', '18px', 'dark_mode', '2024-05-04 14:36:33'),
(283, 'chat-page-tertiary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(284, 'chat-page-primary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(285, 'chat-page-secondary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(286, 'chat-page-tertiary-text-color', '#858585', 'dark_mode', '2024-05-04 14:36:33'),
(287, 'side-navigation-primary-bg-color', '#292929', 'dark_mode', '2024-05-04 14:36:33'),
(288, 'side-navigation-secondary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(289, 'side-navigation-tertiary-bg-color', '#1D1C1C', 'dark_mode', '2024-05-04 14:36:33'),
(290, 'side-navigation-quaternary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(291, 'side-navigation-primary-text-color', '#9D9D9D', 'dark_mode', '2024-05-04 14:36:33'),
(292, 'side-navigation-secondary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(293, 'side-navigation-tertiary-text-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(294, 'side-navigation-quaternary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(295, 'side-navigation-primary-border-color', '#060606', 'dark_mode', '2024-05-04 14:36:33'),
(296, 'side-navigation-primary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(297, 'side-navigation-secondary-font-size', '13px', 'dark_mode', '2024-05-04 14:36:33'),
(298, 'side-navigation-tertiary-font-size', '20px', 'dark_mode', '2024-05-04 14:36:33'),
(299, 'left-side-content-primary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(300, 'left-side-content-secondary-bg-color', '#292929', 'dark_mode', '2024-05-04 14:36:33'),
(301, 'left-side-content-tertiary-bg-color', '#292929', 'dark_mode', '2024-05-04 14:36:33'),
(302, 'left-side-content-quaternary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(303, 'left-side-content-quinary-bg-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(304, 'left-side-content-senary-bg-color', '#F8D7DA', 'dark_mode', '2024-05-04 14:36:33'),
(305, 'left-side-content-septenary-bg-color', '#FFC107', 'dark_mode', '2024-05-04 14:36:33'),
(306, 'left-side-content-octonary-bg-color', '#FF5722', 'dark_mode', '2024-05-04 14:36:33'),
(307, 'left-side-content-nonary-bg-color', '#F48FB1', 'dark_mode', '2024-05-04 14:36:33'),
(308, 'left-side-content-denary-bg-color', '#F06292', 'dark_mode', '2024-05-04 14:36:33'),
(309, 'left-side-content-primary-text-color', '#B6B6B6', 'dark_mode', '2024-05-04 14:36:33'),
(310, 'left-side-content-secondary-text-color', '#797979', 'dark_mode', '2024-05-04 14:36:33'),
(311, 'left-side-content-tertiary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(312, 'left-side-content-quaternary-text-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(313, 'left-side-content-quinary-text-color', '#721C24', 'dark_mode', '2024-05-04 14:36:33'),
(314, 'left-side-content-senary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(315, 'left-side-content-primary-border-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(316, 'left-side-content-secondary-border-color', '#E88A93', 'dark_mode', '2024-05-04 14:36:33'),
(317, 'left-side-content-tertiary-border-color', '#EC407A', 'dark_mode', '2024-05-04 14:36:33'),
(318, 'left-side-content-quaternary-border-color', '#474747', 'dark_mode', '2024-05-04 14:36:33'),
(319, 'left-side-content-primary-font-size', '13px', 'dark_mode', '2024-05-04 14:36:33'),
(320, 'left-side-content-secondary-font-size', '12px', 'dark_mode', '2024-05-04 14:36:33'),
(321, 'left-side-content-tertiary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(322, 'left-side-content-quaternary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(323, 'left-side-content-quinary-font-size', '11px', 'dark_mode', '2024-05-04 14:36:33'),
(324, 'left-side-content-senary-font-size', '25px', 'dark_mode', '2024-05-04 14:36:33'),
(325, 'left-side-content-septenary-font-size', '20px', 'dark_mode', '2024-05-04 14:36:33'),
(326, 'audio-player-primary-bg-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(327, 'audio-player-secondary-bg-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(328, 'audio-player-tertiary-bg-color', '#FF4E8A', 'dark_mode', '2024-05-04 14:36:33'),
(329, 'audio-player-quaternary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(330, 'audio-player-quinary-bg-color', '#EFF1F3', 'dark_mode', '2024-05-04 14:36:33'),
(331, 'audio-player-senary-bg-color', '#E45E8C', 'dark_mode', '2024-05-04 14:36:33'),
(332, 'audio-player-primary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(333, 'audio-player-secondary-text-color', '#AAA7A7', 'dark_mode', '2024-05-04 14:36:33'),
(334, 'audio-player-tertiary-text-color', '#818589', 'dark_mode', '2024-05-04 14:36:33'),
(335, 'audio-player-quaternary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(336, 'audio-player-quinary-text-color', '#666666', 'dark_mode', '2024-05-04 14:36:33'),
(337, 'audio-player-senary-text-color', '#FF6D9F', 'dark_mode', '2024-05-04 14:36:33'),
(338, 'audio-player-primary-border-color', '#383838', 'dark_mode', '2024-05-04 14:36:33'),
(339, 'audio-player-primary-font-size', '20px', 'dark_mode', '2024-05-04 14:36:33'),
(340, 'audio-player-secondary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(341, 'audio-player-tertiary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(342, 'audio-player-quaternary-font-size', '13px', 'dark_mode', '2024-05-04 14:36:33'),
(343, 'audio-player-quinary-font-size', '27px', 'dark_mode', '2024-05-04 14:36:33'),
(344, 'audio-player-senary-font-size', '11px', 'dark_mode', '2024-05-04 14:36:33'),
(345, 'form-primary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(346, 'form-secondary-bg-color', '#040404', 'dark_mode', '2024-05-04 14:36:33'),
(347, 'form-tertiary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(348, 'form-quaternary-bg-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(349, 'form-quinary-bg-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(350, 'form-senary-bg-color', '#F8D7DA', 'dark_mode', '2024-05-04 14:36:33'),
(351, 'form-septenary-bg-color', '#030303', 'dark_mode', '2024-05-04 14:36:33'),
(352, 'form-octonary-bg-color', '#F06292', 'dark_mode', '2024-05-04 14:36:33'),
(353, 'form-primary-text-color', '#A1A1A1', 'dark_mode', '2024-05-04 14:36:33'),
(354, 'form-secondary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(355, 'form-tertiary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(356, 'form-quaternary-text-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(357, 'form-quinary-text-color', '#721C24', 'dark_mode', '2024-05-04 14:36:33'),
(358, 'form-primary-border-color', '#383838', 'dark_mode', '2024-05-04 14:36:33'),
(359, 'form-secondary-border-color', '#E88A93', 'dark_mode', '2024-05-04 14:36:33'),
(360, 'form-tertiary-border-color', '#EC407A', 'dark_mode', '2024-05-04 14:36:33'),
(361, 'form-primary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(362, 'form-secondary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(363, 'form-tertiary-font-size', '18px', 'dark_mode', '2024-05-04 14:36:33'),
(364, 'info-panel-primary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(365, 'info-panel-secondary-bg-color', '#2E2E2E', 'dark_mode', '2024-05-04 14:36:33'),
(366, 'info-panel-tertiary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(367, 'info-panel-quaternary-bg-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(368, 'info-panel-quinary-bg-color', '#242424', 'dark_mode', '2024-05-04 14:36:33'),
(369, 'info-panel-senary-bg-color', '#454545', 'dark_mode', '2024-05-04 14:36:33'),
(370, 'info-panel-primary-text-color', '#B2B1B1', 'dark_mode', '2024-05-04 14:36:33'),
(371, 'info-panel-secondary-text-color', '#959595', 'dark_mode', '2024-05-04 14:36:33'),
(372, 'info-panel-tertiary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(373, 'info-panel-quaternary-text-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(374, 'info-panel-quinary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(375, 'info-panel-primary-border-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(376, 'info-panel-secondary-border-color', '#E88A93', 'dark_mode', '2024-05-04 14:36:33'),
(377, 'info-panel-tertiary-border-color', '#EC407A', 'dark_mode', '2024-05-04 14:36:33'),
(378, 'info-panel-primary-font-size', '16px', 'dark_mode', '2024-05-04 14:36:33'),
(379, 'info-panel-secondary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(380, 'info-panel-tertiary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(381, 'info-panel-quaternary-font-size', '13px', 'dark_mode', '2024-05-04 14:36:33'),
(382, 'welcome-screen-primary-bg-color', '#2E2E2E', 'dark_mode', '2024-05-04 14:36:33'),
(383, 'welcome-screen-primary-text-color', '#A4A4A4', 'dark_mode', '2024-05-04 14:36:33'),
(384, 'welcome-screen-secondary-text-color', '#7E7F81', 'dark_mode', '2024-05-04 14:36:33'),
(385, 'welcome-screen-primary-border-color', '#383838', 'dark_mode', '2024-05-04 14:36:33'),
(386, 'welcome-screen-primary-font-size', '19px', 'dark_mode', '2024-05-04 14:36:33'),
(387, 'welcome-screen-secondary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(388, 'welcome-screen-tertiary-font-size', '13px', 'dark_mode', '2024-05-04 14:36:33'),
(389, 'custom-page-primary-bg-color', '#2E2E2E', 'dark_mode', '2024-05-04 14:36:33'),
(390, 'custom-page-secondary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(391, 'custom-page-primary-text-color', '#8A8A8A', 'dark_mode', '2024-05-04 14:36:33'),
(392, 'custom-page-secondary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(393, 'custom-page-tertiary-text-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(394, 'custom-page-primary-border-color', '#383838', 'dark_mode', '2024-05-04 14:36:33'),
(395, 'custom-page-primary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(396, 'custom-page-secondary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(397, 'statistics-primary-bg-color', '#2E2E2E', 'dark_mode', '2024-05-04 14:36:33'),
(398, 'statistics-secondary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(399, 'statistics-tertiary-bg-color', '#232323', 'dark_mode', '2024-05-04 14:36:33'),
(400, 'statistics-quaternary-bg-color', '#191919', 'dark_mode', '2024-05-04 14:36:33'),
(401, 'statistics-quinary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(402, 'statistics-senary-bg-color', '#1B1B1B', 'dark_mode', '2024-05-04 14:36:33'),
(403, 'statistics-septenary-bg-color', '#FFEB3B', 'dark_mode', '2024-05-04 14:36:33'),
(404, 'statistics-primary-text-color', '#B4B4B4', 'dark_mode', '2024-05-04 14:36:33'),
(405, 'statistics-secondary-text-color', '#818181', 'dark_mode', '2024-05-04 14:36:33'),
(406, 'statistics-tertiary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(407, 'statistics-quaternary-text-color', '#8B8B8B', 'dark_mode', '2024-05-04 14:36:33'),
(408, 'statistics-quinary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(409, 'statistics-senary-text-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(410, 'statistics-septenary-text-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(411, 'statistics-primary-border-color', '#060606', 'dark_mode', '2024-05-04 14:36:33'),
(412, 'statistics-primary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(413, 'statistics-secondary-font-size', '17px', 'dark_mode', '2024-05-04 14:36:33'),
(414, 'statistics-tertiary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(415, 'statistics-quaternary-font-size', '19px', 'dark_mode', '2024-05-04 14:36:33'),
(416, 'membership_info-primary-bg-color', '#2E2E2E', 'dark_mode', '2024-05-04 14:36:33'),
(417, 'membership_info-secondary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(418, 'membership_info-tertiary-bg-color', '#222222', 'dark_mode', '2024-05-04 14:36:33'),
(419, 'membership_info-quaternary-bg-color', '#161616', 'dark_mode', '2024-05-04 14:36:33'),
(420, 'membership_info-quinary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(421, 'membership_info-senary-bg-color', '#1B1B1B', 'dark_mode', '2024-05-04 14:36:33'),
(422, 'membership_info-septenary-bg-color', '#2F2E2E', 'dark_mode', '2024-05-04 14:36:33'),
(423, 'membership_info-octonary-bg-color', '#FFC107', 'dark_mode', '2024-05-04 14:36:33'),
(424, 'membership_info-nonary-bg-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(425, 'membership_info-denary-bg-color', '#1B1B1B', 'dark_mode', '2024-05-04 14:36:33'),
(426, 'membership_info-primary-text-color', '#B4B4B4', 'dark_mode', '2024-05-04 14:36:33'),
(427, 'membership_info-secondary-text-color', '#BFBFBF', 'dark_mode', '2024-05-04 14:36:33'),
(428, 'membership_info-tertiary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(429, 'membership_info-quaternary-text-color', '#A1A1A1', 'dark_mode', '2024-05-04 14:36:33'),
(430, 'membership_info-quinary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(431, 'membership_info-senary-text-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(432, 'membership_info-septenary-text-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(433, 'membership_info-octonary-text-color', '#FFEB3B', 'dark_mode', '2024-05-04 14:36:33'),
(434, 'membership_info-nonary-text-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(435, 'membership_info-denary-text-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(436, 'membership_info-primary-border-color', '#060606', 'dark_mode', '2024-05-04 14:36:33'),
(437, 'membership_info-primary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(438, 'membership_info-secondary-font-size', '17px', 'dark_mode', '2024-05-04 14:36:33'),
(439, 'membership_info-tertiary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(440, 'membership_info-quaternary-font-size', '19px', 'dark_mode', '2024-05-04 14:36:33'),
(441, 'membership_info-quinary-font-size', '27px', 'dark_mode', '2024-05-04 14:36:33'),
(442, 'membership_info-senary-font-size', '16px', 'dark_mode', '2024-05-04 14:36:33'),
(443, 'chat-window-primary-bg-color', '#070707', 'dark_mode', '2024-05-04 14:36:33'),
(444, 'chat-window-secondary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(445, 'chat-window-tertiary-bg-color', '#040404', 'dark_mode', '2024-05-04 14:36:33'),
(446, 'chat-window-quaternary-bg-color', '#F06292', 'dark_mode', '2024-05-04 14:36:33'),
(447, 'chat-window-quinary-bg-color', '#292929', 'dark_mode', '2024-05-04 14:36:33'),
(448, 'chat-window-senary-bg-color', '#1F1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(449, 'chat-window-septenary-bg-color', '#292929', 'dark_mode', '2024-05-04 14:36:33'),
(450, 'chat-window-octonary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(451, 'chat-window-nonary-bg-color', '#F06292', 'dark_mode', '2024-05-04 14:36:33'),
(452, 'chat-window-denary-bg-color', '#312F2F', 'dark_mode', '2024-05-04 14:36:33'),
(453, 'chat-window-primary-text-color', '#8D8D8D', 'dark_mode', '2024-05-04 14:36:33'),
(454, 'chat-window-secondary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(455, 'chat-window-tertiary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(456, 'chat-window-quaternary-text-color', '#989898', 'dark_mode', '2024-05-04 14:36:33'),
(457, 'chat-window-quinary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(458, 'chat-window-senary-text-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(459, 'chat-window-septenary-text-color', '#666664', 'dark_mode', '2024-05-04 14:36:33'),
(460, 'chat-window-octonary-text-color', '#565656', 'dark_mode', '2024-05-04 14:36:33'),
(461, 'chat-window-nonary-text-color', '#E45E8C', 'dark_mode', '2024-05-04 14:36:33'),
(462, 'chat-window-denary-text-color', '#B1B1B1', 'dark_mode', '2024-05-04 14:36:33'),
(463, 'chat-window-undenary-text-color', '#FF9800', 'dark_mode', '2024-05-04 14:36:33'),
(464, 'chat-window-duodenary-text-color', '#FFEB3B', 'dark_mode', '2024-05-04 14:36:33'),
(465, 'chat-window-primary-border-color', '#000000', 'dark_mode', '2024-05-04 14:36:33'),
(466, 'chat-window-secondary-border-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(467, 'chat-window-tertiary-border-color', '#0A0A0A', 'dark_mode', '2024-05-04 14:36:33'),
(468, 'chat-window-quaternary-border-color', '#607D8B', 'dark_mode', '2024-05-04 14:36:33'),
(469, 'chat-window-quinary-border-color', '#EC407A', 'dark_mode', '2024-05-04 14:36:33'),
(470, 'chat-window-senary-border-color', '#FFC107', 'dark_mode', '2024-05-04 14:36:33'),
(471, 'chat-window-septenary-border-color', '#474747', 'dark_mode', '2024-05-04 14:36:33'),
(472, 'chat-window-primary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(473, 'chat-window-secondary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(474, 'chat-window-tertiary-font-size', '13px', 'dark_mode', '2024-05-04 14:36:33'),
(475, 'chat-window-quaternary-font-size', '12px', 'dark_mode', '2024-05-04 14:36:33'),
(476, 'chat-window-quinary-font-size', '19px', 'dark_mode', '2024-05-04 14:36:33'),
(477, 'chat-window-senary-font-size', '27px', 'dark_mode', '2024-05-04 14:36:33'),
(478, 'chat-window-septenary-font-size', '30px', 'dark_mode', '2024-05-04 14:36:33'),
(479, 'chat-window-octonary-font-size', '34px', 'dark_mode', '2024-05-04 14:36:33'),
(480, 'chat-window-nonary-font-size', '22px', 'dark_mode', '2024-05-04 14:36:33'),
(481, 'entry-page-primary-bg-color', '#1E1D1D', 'dark_mode', '2024-05-04 14:36:33'),
(482, 'entry-page-secondary-bg-color', '#292929', 'dark_mode', '2024-05-04 14:36:33'),
(483, 'entry-page-tertiary-bg-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(484, 'entry-page-quaternary-bg-color', '#9C27B0', 'dark_mode', '2024-05-04 14:36:33'),
(485, 'entry-page-quinary-bg-color', '#353333', 'dark_mode', '2024-05-04 14:36:33'),
(486, 'entry-page-senary-bg-color', '#F06292', 'dark_mode', '2024-05-04 14:36:33'),
(487, 'entry-page-primary-text-color', '#858585', 'dark_mode', '2024-05-04 14:36:33'),
(488, 'entry-page-secondary-text-color', '#929292', 'dark_mode', '2024-05-04 14:36:33'),
(489, 'entry-page-tertiary-text-color', '#CDCDCD', 'dark_mode', '2024-05-04 14:36:33'),
(490, 'entry-page-quaternary-text-color', '#FFFFFF', 'dark_mode', '2024-05-04 14:36:33'),
(491, 'entry-page-quinary-text-color', '#E91E63', 'dark_mode', '2024-05-04 14:36:33'),
(492, 'entry-page-senary-text-color', '#808080', 'dark_mode', '2024-05-04 14:36:33'),
(493, 'entry-page-primary-border-color', '#101010', 'dark_mode', '2024-05-04 14:36:33'),
(494, 'entry-page-secondary-border-color', '#A9A9A9', 'dark_mode', '2024-05-04 14:36:33'),
(495, 'entry-page-tertiary-border-color', '#DC1F6F', 'dark_mode', '2024-05-04 14:36:33'),
(496, 'entry-page-quaternary-border-color', '#474747', 'dark_mode', '2024-05-04 14:36:33'),
(497, 'entry-page-primary-font-size', '14px', 'dark_mode', '2024-05-04 14:36:33'),
(498, 'entry-page-secondary-font-size', '15px', 'dark_mode', '2024-05-04 14:36:33'),
(499, 'entry-page-tertiary-font-size', '18px', 'dark_mode', '2024-05-04 14:36:33'),
(500, 'entry-page-quaternary-font-size', '20px', 'dark_mode', '2024-05-04 14:36:33'),
(501, 'entry-page-quinary-font-size', '16px', 'dark_mode', '2024-05-04 14:36:33'),
(502, 'entry-page-senary-font-size', '13px', 'dark_mode', '2024-05-04 14:36:33'),
(503, 'landing-page-octonary-bg-color', '#EBEFF3', 'light_mode', '2024-05-04 14:36:33'),
(504, 'landing-page-octonary-bg-color', '#0B0B0B', 'dark_mode', '2024-05-04 14:36:33');

-- --------------------------------------------------------

--
-- Table structure for table `gr_custom_fields`
--

CREATE TABLE `gr_custom_fields` (
  `field_id` bigint NOT NULL,
  `string_constant` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_category` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'profile',
  `field_type` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_on_signup` int NOT NULL DEFAULT '0',
  `show_on_guest_login` int NOT NULL DEFAULT '0',
  `required` int NOT NULL DEFAULT '0',
  `minimum_length` int NOT NULL DEFAULT '0',
  `maximum_length` int NOT NULL DEFAULT '250',
  `show_on_info_page` int NOT NULL DEFAULT '1',
  `editable_only_once` int NOT NULL DEFAULT '0',
  `searchable_field` int NOT NULL DEFAULT '0',
  `disabled` int NOT NULL DEFAULT '0',
  `order_by` bigint NOT NULL DEFAULT '0',
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_custom_fields`
--

INSERT INTO `gr_custom_fields` (`field_id`, `string_constant`, `field_category`, `field_type`, `show_on_signup`, `show_on_guest_login`, `required`, `minimum_length`, `maximum_length`, `show_on_info_page`, `editable_only_once`, `searchable_field`, `disabled`, `order_by`, `updated_on`) VALUES
(1, 'custom_field_1', 'profile', 'long_text', 1, 0, 1, 0, 250, 1, 0, 0, 0, 0, '2022-09-29 18:19:00'),
(2, 'custom_field_2', 'profile', 'date', 1, 0, 0, 0, 0, 1, 0, 1, 0, 0, '2024-02-14 23:46:22'),
(3, 'custom_field_3', 'profile', 'dropdown', 1, 0, 1, 0, 0, 1, 0, 1, 0, 0, '2023-09-21 12:38:55'),
(4, 'custom_field_4', 'profile', 'link', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, '2023-04-16 17:02:10'),
(5, 'custom_field_5', 'profile', 'short_text', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, '2023-09-21 12:58:38'),
(6, 'custom_field_6', 'profile', 'dropdown', 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, '2023-04-16 20:24:19');

-- --------------------------------------------------------

--
-- Table structure for table `gr_custom_fields_values`
--

CREATE TABLE `gr_custom_fields_values` (
  `field_value_id` bigint NOT NULL,
  `field_id` bigint NOT NULL,
  `group_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `field_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_custom_menu_items`
--

CREATE TABLE `gr_custom_menu_items` (
  `menu_item_id` bigint NOT NULL,
  `string_constant` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_icon_class` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_id` bigint DEFAULT NULL,
  `web_address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_target` int NOT NULL DEFAULT '0' COMMENT '0 = Open URL in same window\r\n1 = Open URL in New tab',
  `show_on_landing_page_header` int NOT NULL DEFAULT '0',
  `show_on_landing_page_footer` int NOT NULL DEFAULT '0',
  `show_on_entry_page` int NOT NULL DEFAULT '0',
  `show_on_chat_page` int NOT NULL DEFAULT '0',
  `show_on_chat_page_top` int NOT NULL DEFAULT '0',
  `menu_item_order` int NOT NULL DEFAULT '0',
  `menu_item_visibility` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `disabled` int NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_custom_menu_items`
--

INSERT INTO `gr_custom_menu_items` (`menu_item_id`, `string_constant`, `menu_icon_class`, `page_id`, `web_address`, `link_target`, `show_on_landing_page_header`, `show_on_landing_page_footer`, `show_on_entry_page`, `show_on_chat_page`, `show_on_chat_page_top`, `menu_item_order`, `menu_item_visibility`, `disabled`, `created_on`, `updated_on`) VALUES
(1, 'custom_menu_item_1', 'bi-card-text', 2, '#', 0, 1, 1, 1, 0, 0, 3, '[\"1\",\"2\",\"3\",\"5\"]', 0, '2022-01-20 19:10:31', '2023-04-23 13:58:45'),
(2, 'custom_menu_item_2', 'bi-card-text', 3, '#', 0, 1, 1, 1, 0, 0, 2, 'all', 0, '2022-01-20 19:10:48', '2022-03-19 22:14:00'),
(3, 'custom_menu_item_3', 'bi-card-text', 1, '#', 0, 1, 0, 0, 0, 0, 1, '[\"1\",\"2\",\"3\",\"5\"]', 0, '2022-03-19 22:13:42', '2022-10-21 02:41:02'),
(4, 'custom_menu_item_4', 'bi-card-text', NULL, 'sitemap/', 0, 0, 1, 0, 0, 0, 4, '[\"1\",\"2\",\"3\",\"5\"]', 0, '2022-03-19 22:14:47', '2022-10-21 02:41:08');

-- --------------------------------------------------------

--
-- Table structure for table `gr_custom_pages`
--

CREATE TABLE `gr_custom_pages` (
  `page_id` bigint NOT NULL,
  `string_constant` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disabled` int NOT NULL DEFAULT '0',
  `who_all_can_view_page` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'all',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_custom_pages`
--

INSERT INTO `gr_custom_pages` (`page_id`, `string_constant`, `slug`, `meta_title`, `meta_description`, `disabled`, `who_all_can_view_page`, `created_on`, `updated_on`) VALUES
(1, 'custom_page_1', 'about', '', '', 0, '[\"1\",\"2\",\"3\",\"5\"]', '2021-09-26 18:44:30', '2024-05-07 22:18:34'),
(2, 'custom_page_2', 'terms', '', '', 0, '[\"1\",\"2\",\"3\",\"5\"]', '2021-09-26 18:44:03', '2022-06-14 10:06:37'),
(3, 'custom_page_3', 'privacy', '', '', 0, '[\"1\",\"2\",\"3\",\"5\"]', '2021-12-08 22:05:49', '2022-06-14 10:24:05');

-- --------------------------------------------------------

--
-- Table structure for table `gr_friends`
--

CREATE TABLE `gr_friends` (
  `friendship_id` bigint NOT NULL,
  `from_user_id` bigint NOT NULL,
  `to_user_id` bigint NOT NULL,
  `relation_status` int NOT NULL DEFAULT '0' COMMENT '0 = Pending 1 = Friends',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_groups`
--

CREATE TABLE `gr_groups` (
  `group_id` bigint NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group_category_id` bigint NOT NULL DEFAULT '1',
  `meta_title` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secret_group` int NOT NULL DEFAULT '0',
  `group_picture` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_cover_pic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_bg_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secret_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unleavable` int NOT NULL DEFAULT '0',
  `who_all_can_send_messages` varchar(199) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin_group` int NOT NULL DEFAULT '0',
  `auto_join_group` int NOT NULL DEFAULT '0',
  `enable_video_chat` int NOT NULL DEFAULT '0',
  `enable_audio_chat` int NOT NULL DEFAULT '0',
  `default_group_role` bigint DEFAULT NULL,
  `total_members` bigint NOT NULL DEFAULT '0',
  `suspended` int NOT NULL DEFAULT '0',
  `group_header_status` int NOT NULL DEFAULT '0' COMMENT '0 = Disabled\r\n1 = Enabled',
  `created_by` bigint NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_group_categories`
--

CREATE TABLE `gr_group_categories` (
  `group_category_id` bigint NOT NULL,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint NOT NULL DEFAULT '0',
  `category_order` int NOT NULL DEFAULT '1',
  `group_category_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disabled` int NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_group_categories`
--

INSERT INTO `gr_group_categories` (`group_category_id`, `category_name`, `created_by`, `category_order`, `group_category_image`, `disabled`, `created_on`, `updated_on`) VALUES
(1, 'Uncategorized', 1, 1, 'assets/files/group_categories/default.png', 0, '2024-04-26 23:59:31', '2024-05-15 00:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `gr_group_invitations`
--

CREATE TABLE `gr_group_invitations` (
  `group_invitation_id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `group_id` bigint DEFAULT NULL,
  `referrer_user_id` bigint DEFAULT NULL,
  `related_email_address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invitation_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired` int NOT NULL DEFAULT '0' COMMENT '0 = No\r\n1 = Yes',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_group_members`
--

CREATE TABLE `gr_group_members` (
  `group_member_id` bigint NOT NULL,
  `group_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `group_role_id` bigint NOT NULL DEFAULT '4',
  `referrer_user_id` bigint DEFAULT '0',
  `last_read_message_id` bigint DEFAULT '0',
  `load_message_id_from` bigint DEFAULT NULL,
  `currently_browsing` int NOT NULL DEFAULT '0',
  `previous_group_role_id` bigint NOT NULL DEFAULT '4',
  `banned_till` datetime DEFAULT NULL,
  `joined_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_group_messages`
--

CREATE TABLE `gr_group_messages` (
  `group_message_id` bigint NOT NULL,
  `group_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `original_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `filtered_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `system_message` int NOT NULL DEFAULT '0',
  `parent_message_id` bigint DEFAULT NULL,
  `attachment_type` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_preview` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_reactions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_group_messages_reactions`
--

CREATE TABLE `gr_group_messages_reactions` (
  `group_message_reaction_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `group_message_id` bigint NOT NULL,
  `reaction_id` int NOT NULL COMMENT '1 = Like\r\n2 = Love\r\n3 = Haha\r\n4 = Wow\r\n5 = Sad\r\n6 = Angry',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_group_roles`
--

CREATE TABLE `gr_group_roles` (
  `group_role_id` bigint NOT NULL,
  `string_constant` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_role_attribute` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'group_role',
  `role_hierarchy` int NOT NULL DEFAULT '1',
  `disabled` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_group_roles`
--

INSERT INTO `gr_group_roles` (`group_role_id`, `string_constant`, `permissions`, `group_role_attribute`, `role_hierarchy`, `disabled`, `updated_on`) VALUES
(1, 'group_role_1', '{\"update\":\"group_roles\",\"show_label\":\"\",\"label_background_color\":\"#7B0909\",\"label_text_color\":\"#FF0A0A\",\"attribute\":\"banned_users\",\"disabled\":\"no\"}', 'banned_users', 1, 0, '2024-05-15 00:37:38'),
(2, 'group_role_2', '{\"update\":\"group_roles\",\"show_label\":\"no\",\"label_background_color\":\"#FF61BB\",\"label_text_color\":\"#FFFFFF\",\"attribute\":\"administrators\",\"disabled\":\"\",\"group\":[\"edit_group\",\"view_shared_files\",\"view_shared_links\",\"delete_group\",\"video_chat\",\"audio_chat\"],\"group_members\":[\"view_group_members\",\"view_currently_online\",\"ban_users_from_group\",\"unban_users_from_group\",\"manage_user_roles\",\"remove_group_members\"],\"messages\":[\"send_message\",\"send_audio_message\",\"attach_files\",\"attach_from_storage\",\"attach_gifs\",\"search_share_videos\",\"attach_stickers\",\"check_read_receipts\",\"share_screenshot\",\"preview_pdf_files\",\"share_location\",\"allow_sharing_links\",\"allow_sharing_email_addresses\",\"generate_link_preview\",\"download_attachments\",\"edit_own_message\",\"edit_messages\",\"delete_own_message\",\"delete_messages\",\"view_reactions\",\"react_messages\",\"reply_messages\",\"forward_messages\",\"mention_users\",\"mention_everyone\"]}', 'administrators', 4, 0, '2024-05-15 00:37:58'),
(3, 'group_role_3', '{\"update\":\"group_roles\",\"show_label\":\"no\",\"label_background_color\":\"#9EFF00\",\"label_text_color\":\"#FFFFFF\",\"attribute\":\"moderators\",\"disabled\":\"\",\"group\":[\"view_shared_files\",\"view_shared_links\",\"video_chat\",\"audio_chat\"],\"group_members\":[\"view_group_members\",\"view_currently_online\",\"ban_users_from_group\",\"unban_users_from_group\",\"manage_user_roles\",\"remove_group_members\"],\"messages\":[\"send_message\",\"send_audio_message\",\"attach_files\",\"attach_from_storage\",\"attach_gifs\",\"search_share_videos\",\"attach_stickers\",\"check_read_receipts\",\"share_screenshot\",\"preview_pdf_files\",\"share_location\",\"allow_sharing_links\",\"allow_sharing_email_addresses\",\"generate_link_preview\",\"download_attachments\",\"edit_own_message\",\"edit_messages\",\"delete_own_message\",\"delete_messages\",\"view_reactions\",\"react_messages\",\"reply_messages\",\"forward_messages\",\"mention_users\"]}', 'moderators', 3, 0, '2024-05-15 00:38:07'),
(4, 'group_role_4', '{\"update\":\"group_roles\",\"show_label\":\"no\",\"label_background_color\":\"#FF4D55\",\"label_text_color\":\"#FFFFFF\",\"attribute\":\"default_group_role\",\"disabled\":\"\",\"group\":[\"view_shared_files\",\"view_shared_links\",\"video_chat\",\"audio_chat\"],\"group_members\":[\"view_group_members\",\"view_currently_online\"],\"messages\":[\"send_message\",\"send_audio_message\",\"attach_files\",\"attach_from_storage\",\"attach_gifs\",\"search_share_videos\",\"attach_stickers\",\"check_read_receipts\",\"share_screenshot\",\"preview_pdf_files\",\"share_location\",\"allow_sharing_links\",\"allow_sharing_email_addresses\",\"generate_link_preview\",\"download_attachments\",\"edit_own_message\",\"delete_own_message\",\"view_reactions\",\"react_messages\",\"reply_messages\",\"forward_messages\",\"mention_users\"]}', 'default_group_role', 2, 0, '2024-05-15 00:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `gr_languages`
--

CREATE TABLE `gr_languages` (
  `language_id` bigint NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `text_direction` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ltr',
  `disabled` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_languages`
--

INSERT INTO `gr_languages` (`language_id`, `name`, `iso_code`, `text_direction`, `disabled`, `updated_on`) VALUES
(1, 'English', 'en', 'ltr', 0, '2024-05-14 13:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `gr_language_strings`
--

CREATE TABLE `gr_language_strings` (
  `string_id` bigint NOT NULL,
  `string_constant` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `string_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `string_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'one-line',
  `skip_update` int NOT NULL DEFAULT '0',
  `skip_cache` int NOT NULL DEFAULT '0',
  `language_id` bigint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_language_strings`
--

INSERT INTO `gr_language_strings` (`string_id`, `string_constant`, `string_value`, `string_type`, `skip_update`, `skip_cache`, `language_id`) VALUES
(1, 'control_storage', 'Full Control Access to User&#039;s Storage', 'one-line', 0, 0, 1),
(2, 'reported', 'Reported', 'one-line', 0, 0, 1),
(3, 'idle', 'Idle', 'one-line', 0, 0, 1),
(4, 'view_currently_online', 'View Currently Online', 'one-line', 0, 0, 1),
(5, 'on_private_message', 'Someone send a Private Message', 'one-line', 0, 0, 1),
(6, 'playlist', 'Playlist', 'one-line', 0, 0, 1),
(7, 'allow_sharing_links', 'Allow Sharing Links', 'one-line', 0, 0, 1),
(8, 'ban_from_group', 'Ban From Group', 'one-line', 0, 0, 1),
(9, 'title', 'Title', 'one-line', 0, 0, 1),
(10, 'send_audio_message', 'Send Audio Message', 'one-line', 0, 0, 1),
(11, 'exceeded_max_msg_length', 'Exceeded Maximum Message Length', 'one-line', 0, 0, 1),
(12, 'mentioned_group_chat', 'Mentioned you in group chat', 'one-line', 0, 0, 1),
(13, 'site_name', 'Site Name', 'one-line', 0, 0, 1),
(14, 'not_found_page_description', 'The resource you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'multi-line', 0, 0, 1),
(15, 'reply', 'Reply', 'one-line', 0, 0, 1),
(16, 'role_name', 'Role Name', 'one-line', 0, 0, 1),
(17, 'send_mail', 'Send Mail', 'one-line', 0, 0, 1),
(18, 'onesignal_safari_web_id', 'OneSignal Safari Web ID', 'one-line', 0, 0, 1),
(19, 'updated_group_info', 'Updated Group Info', 'one-line', 0, 0, 1),
(20, 'create_unleavable_group', 'Create Unleavable Group', 'one-line', 0, 0, 1),
(21, 'reset_password_email_subject', 'Recover your Account', 'one-line', 0, 0, 1),
(22, 'invited', 'Invited', 'one-line', 0, 0, 1),
(23, 'delete', 'Delete', 'one-line', 0, 0, 1),
(24, 'custom_page_3_content', '<p>This Privacy Policy governs the manner in which we collects, uses, maintains and discloses information collected from users (each, a \"User\") of our website (\"Site\"). This privacy policy applies to the Site and all products and services offered by us.</p><p><b>1) Personal identification information</b></p><p>We may collect personal identification information from Users in a variety of ways, including, but not limited to, when Users visit our site, subscribe to the newsletter, fill out a form, and in connection with other activities, services, features or resources we make available on our Site.. Users may visit our Site anonymously. We will collect personal identification information from Users only if they voluntarily submit such information to us. Users can always refuse to supply personally identification information, except that it may prevent them from engaging in certain Site related activities.</p><p><b>2) Non-personal identification information</b></p><p>We may collect non-personal identification information about Users whenever they interact with our Site. Non-personal identification information may include the browser name, the type of computer and technical information about Users means of connection to our Site, such as the operating system and the Internet service providers utilized and other similar information.</p><p><b>3) Chat Room Privacy</b></p><p>Administrators and Moderators of the Chat Rooms can see the I.P. of the Users who are chatting. We do not save I.P. addresses of the Users, neither do we share I.P addresses with others. As long as you do not provide (not recommended) your real name, address, Email ID and any other personal information in the chat room, you are completely anonymous to other users.</p><p><b>4) How we use collected information</b></p><p>We may collect and use Users personal information for the following purposes:</p><p>– To improve customer service Information you provide helps us respond to your customer service requests and support needs more efficiently.</p><p>– To improve our Site We may use feedback you provide to improve our products and services.</p><p>– To run a promotion, contest, survey or other Site feature. To send Users information they agreed to receive about topics we think will be of interest to them.</p><p>– To send periodic emails We may use the email address to respond to their inquiries, questions, and/or other requests.</p><p><b>5) How we protect your information</b></p><p>We adopt appropriate data collection, storage and processing practices and security measures to protect against unauthorized access, alteration, disclosure or destruction of your personal information, username, password, transaction information and data stored on our Site.</p><p><b>6) Sharing your personal information</b></p><p>We do not sell, trade, or rent Users personal identification information to others. We may share generic aggregated demographic information not linked to any personal identification information regarding visitors and users with our business partners, trusted affiliates and advertisers for the purposes outlined above.</p><p><b>7) Third party websites</b></p><p>Users may find advertising or other content on our Site that link to the sites and services of our partners, suppliers, advertisers, sponsors, licensors and other third parties. We do not control the content or links that appear on these sites and are not responsible for the practices employed by websites linked to or from our Site. In addition, these sites or services, including their content and links, may be constantly changing. These sites and services may have their own privacy policies and customer service policies. Browsing and interaction on any other website, including websites which have a link to our Site, is subject to that website’s own terms and policies.</p><p><b>8) Advertising</b></p><p>Ads appearing on our site may be delivered to Users by advertising partners, who may set cookies. These cookies allow the ad server to recognize your computer each time they send you an online advertisement to compile non personal identification information about you or others who use your computer. This information allows ad networks to, among other things, deliver targeted advertisements that they believe will be of most interest to you. This privacy policy does not cover the use of cookies by any advertisers.</p><p><b>9) Log Files</b></p><p>As with most other websites, we collect and use the data contained in log files. The information in the log files include your IP (internet protocol) address, your ISP (internet service provider, such as AOL or Shaw Cable), the browser you used to visit our site (such as Internet Explorer or Firefox), the time you visited our site and which pages you visited throughout our site.</p><p><b>10) Cookies and Web Beacons</b></p><p>We do use cookies to store information, such as your personal preferences when you visit our site. This could include only showing you a popup once in your visit, or the ability to login to some of our features, such as forums.</p><p>We also use third party advertisements to support our site. Some of these advertisers may use technology such as cookies and web beacons when they advertise on our site, which will also send these advertisers (such as Google through the Google AdSense program) information including your IP address, your ISP , the browser you used to visit our site, and in some cases, whether you have Flash installed. This is generally used for geotargeting purposes (showing New York real estate ads to someone in New York, for example) or showing certain ads based on specific sites visited (such as showing cooking ads to someone who frequents cooking sites).</p><p><b>11) Compliance with children’s online privacy protection act</b></p><p>Protecting the privacy of the very young is especially important. For that reason, we never collect or maintain information at our Site from those we actually know are under 13, and no part of our website is structured to attract anyone under 13.</p><p><b>12) Changes to this privacy policy</b></p><p>We has the discretion to update this privacy policy at any time. When we do, we will revise the updated date at the bottom of this page. We encourage Users to frequently check this page for any changes to stay informed about how we are helping to protect the personal information we collect. You acknowledge and agree that it is your responsibility to review this privacy policy periodically and become aware of modifications.</p><p><b>13) Your acceptance of these terms</b></p><p>By using this Site, you signify your acceptance of this policy and terms of service. If you do not agree to this policy, please do not use our Site. Your continued use of the Site following the posting of changes to this policy will be deemed your acceptance of those changes.</p>', 'multi_line', 1, 1, 1),
(25, 'guest_users', 'Guest Users', 'one-line', 0, 0, 1),
(26, 'banned', 'Banned', 'one-line', 0, 0, 1),
(27, 'progressive_web_application', 'Progressive Web Application', 'one-line', 0, 0, 1),
(28, 'guest_login_text', 'Create a Guest User account', 'one-line', 0, 0, 1),
(29, 'favicon', 'Favicon', 'one-line', 0, 0, 1),
(30, 'pin_group', 'Pin Group', 'one-line', 0, 0, 1),
(31, 'blacklist', 'Blacklist', 'one-line', 0, 0, 1),
(32, 'reset_password_email_button_label', 'Reset Password', 'one-line', 0, 0, 1),
(33, 'advert_placement', 'Ad Placement', 'one-line', 0, 0, 1),
(34, 'report', 'Report', 'one-line', 0, 0, 1),
(35, 'primary_font_size', 'Primary Font Size', 'one-line', 0, 0, 1),
(36, 'access_time', 'Access Time', 'one-line', 0, 0, 1),
(37, 'edit_profile', 'Edit Profile', 'one-line', 0, 0, 1),
(38, 'long_text_field', 'Long Text', 'one-line', 0, 0, 1),
(39, 'image', 'Image', 'one-line', 0, 0, 1),
(40, 'unban_from_site_confirmation', 'Are you sure you want to allow this user from accessing site ?', 'one-line', 0, 0, 1),
(41, 'public_group', 'Public Group', 'one-line', 0, 0, 1),
(42, 'protected_group', 'Protected Group', 'one-line', 0, 0, 1),
(43, 'edit_role', 'Edit Role', 'one-line', 0, 0, 1),
(44, 'dropdown_field', 'Dropdown', 'one-line', 0, 0, 1),
(45, 'delete_own_group', 'Delete their own Group', 'one-line', 0, 0, 1),
(46, 'minutes', 'Minutes', 'one-line', 0, 0, 1),
(47, 'default_skin_mode', 'Default Color Scheme', 'one-line', 0, 0, 1),
(48, 'image_files', 'Image Files', 'one-line', 0, 0, 1),
(49, 'view_group_members', 'View Group Members', 'one-line', 0, 0, 1),
(50, 'minimal-ui', 'Minimal UI', 'one-line', 0, 0, 1),
(51, 'general_settings', 'General Settings', 'one-line', 0, 0, 1),
(52, 'someone', 'Someone', 'one-line', 0, 0, 1),
(53, 'login', 'Login', 'one-line', 0, 0, 1),
(54, 'secondary_font_size', 'Secondary Font Size', 'one-line', 0, 0, 1),
(55, 'roles', 'Site Roles', 'one-line', 0, 0, 1),
(56, 'unban_ip_addresses', 'Unban IP addresses', 'one-line', 0, 0, 1),
(57, 'create_site_role', 'Create Site Role', 'one-line', 0, 0, 1),
(58, 'ban', 'Ban', 'one-line', 0, 0, 1),
(59, 'unban_from_group', 'Unban from Group', 'one-line', 0, 0, 1),
(60, 'link_target', 'Link Target', 'one-line', 0, 0, 1),
(61, 'disagree', 'Disagree', 'one-line', 0, 0, 1),
(62, 'view_groups_without_login', 'View Public Groups without Login', 'one-line', 0, 0, 1),
(63, 'delete_access_log_confirmation', 'Are you sure you want to remove this Access Log ?', 'one-line', 0, 0, 1),
(64, 'temporarily_banned_from_group', 'Temporarily Banned from Group', 'one-line', 0, 0, 1),
(65, 'push_notification_icon', 'Push Notification Icon', 'one-line', 0, 0, 1),
(66, 'ignored', 'Ignored', 'one-line', 0, 0, 1),
(67, 'core_settings', 'Core Settings', 'one-line', 0, 0, 1),
(68, 'minimum_username_length', 'Minimum Username Length', 'one-line', 0, 0, 1),
(69, 'quinary_border_color', 'Quinary Border Color', 'one-line', 0, 0, 1),
(70, 'senary_text_color', 'Senary Text Color', 'one-line', 0, 0, 1),
(71, 'preview_attachments', 'Preview Attachments', 'one-line', 0, 0, 1),
(72, 'show_on_chat_page', 'Show on Chat Page', 'one-line', 0, 0, 1),
(73, 'role', 'Role', 'one-line', 0, 0, 1),
(74, 'group_url', 'Group URL', 'one-line', 0, 0, 1),
(75, 'minimum_message_length', 'Minimum Message Length', 'one-line', 0, 0, 1),
(76, 'manage_user_access_logs', 'Manage User Access Logs', 'one-line', 0, 0, 1),
(77, 'strict_mode', 'Strict Mode', 'one-line', 0, 0, 1),
(78, 'advert_name', 'Advert Name', 'one-line', 0, 0, 1),
(79, 'heading', 'Heading', 'one-line', 0, 0, 1),
(80, 'on_user_mention_group_chat', 'Someone mentions user in Group Chat', 'one-line', 0, 0, 1),
(81, 'reset_password_email_heading', 'Trouble signing in?', 'one-line', 0, 0, 1),
(82, 'signup_text', 'Create a new Account', 'one-line', 0, 0, 1),
(83, 'email_verified', 'You&#039;ve successfully verified your email', 'one-line', 0, 0, 1),
(84, 'joined', 'Joined', 'one-line', 0, 0, 1),
(85, 'select_a_page', 'Select a Page', 'one-line', 0, 0, 1),
(86, 'create_sticker_pack', 'Create Sticker Pack', 'one-line', 0, 0, 1),
(87, 'datetime', 'Date &amp; Time', 'one-line', 0, 0, 1),
(88, 'add_to_group', 'Add to Group', 'one-line', 0, 0, 1),
(89, 'group_join_limit', 'Maximum Number of Groups a User can Join', 'one-line', 0, 0, 1),
(90, 'infotip_footer_tag', 'This will be printed before the &lt;/body&gt; closing tag', 'one-line', 0, 0, 1),
(91, 'unjoined', 'Unjoined', 'one-line', 0, 0, 1),
(92, 'blocked', 'Blocked', 'one-line', 0, 0, 1),
(93, 'quaternary_border_color', 'Quaternary Border Color', 'one-line', 0, 0, 1),
(94, 'search_here', 'Search here', 'one-line', 0, 0, 1),
(95, 'unblock_user_confirmation', 'Are you sure you want to unblock this user ?', 'one-line', 0, 0, 1),
(96, 'add_audio_files', 'Add Audio Files', 'one-line', 0, 0, 1),
(97, 'view', 'View', 'one-line', 0, 0, 1),
(98, 'none', 'None', 'one-line', 0, 0, 1),
(99, 'confirm_export', 'Do You Want to Export?', 'one-line', 0, 0, 1),
(100, 'other', 'Other', 'one-line', 0, 0, 1),
(101, 'confirm_delete_all_messages', 'Are you sure you want to delete all chat messages?', 'one-line', 0, 0, 1),
(102, 'smtp_port', 'SMTP Port', 'one-line', 0, 0, 1),
(103, 'unignore_user_confirmation', 'Are you sure you want to remove this user from Ignore list ?', 'one-line', 0, 0, 1),
(104, 'custom_page', 'Custom Page', 'one-line', 0, 0, 1),
(105, 'pwa_description', 'Description', 'one-line', 0, 0, 1),
(106, 'sending', 'Sending', 'one-line', 0, 0, 1),
(107, 'attribute', 'Attribute', 'one-line', 0, 0, 1),
(108, 'view_joined_groups', 'View Joined Groups', 'one-line', 0, 0, 1),
(109, 'agree', 'Agree', 'one-line', 0, 0, 1),
(110, 'custom_field_1', 'About Me', 'one-line', 1, 0, 1),
(111, 'go_back', 'Go Back', 'one-line', 0, 0, 1),
(112, 'message', 'Message', 'one-line', 0, 0, 1),
(113, 'edit', 'Edit', 'one-line', 0, 0, 1),
(114, 'instagram_url', 'Instagram URL', 'one-line', 0, 0, 1),
(115, 'appearance', 'Appearance', 'one-line', 0, 0, 1),
(116, 'radio_stations', 'Radio Stations', 'one-line', 0, 0, 1),
(117, 'super_privileges', 'Super Privileges', 'one-line', 0, 0, 1),
(118, 'date_field', 'Date', 'one-line', 0, 0, 1),
(119, 'spam', 'Spam', 'one-line', 0, 0, 1),
(120, 'ban_from_site_confirmation', 'Are you sure you want to ban this user from accessing site ?', 'one-line', 0, 0, 1),
(121, 'empty_profile', 'Empty Profile', 'one-line', 0, 0, 1),
(122, 'upload', 'Upload', 'one-line', 0, 0, 1),
(123, 'verification_email_button_label', 'Verify your email', 'one-line', 0, 0, 1),
(124, 'images', 'Images', 'one-line', 0, 0, 1),
(125, 'gif_search_engine', 'GIF Search Engine', 'one-line', 0, 0, 1),
(126, 'password_protect', 'Password Protect', 'one-line', 0, 0, 1),
(127, 'not_found_page_expression', 'Oops!', 'one-line', 0, 0, 1),
(128, 'moderator', 'Moderator', 'one-line', 0, 0, 1),
(129, 'pwa_short_name', 'Application Short Name', 'one-line', 0, 0, 1),
(130, 'not_a_group_member_message', 'You are not a member of this Group. Click here to Join.', 'one-line', 0, 0, 1),
(131, 'ffmpeg_binaries_path', 'FFmpeg Binaries Path', 'one-line', 0, 0, 1),
(132, 'description', 'Description', 'one-line', 0, 0, 1),
(133, 'login_settings', 'Login Settings', 'one-line', 0, 0, 1),
(134, 'ip_address', 'IP address', 'one-line', 0, 0, 1),
(135, 'send_on_behalf', 'Send Messages on Behalf', 'one-line', 0, 0, 1),
(136, 'group_role', 'Group Role', 'one-line', 0, 0, 1),
(137, 'edit_badge', 'Edit Badge', 'one-line', 0, 0, 1),
(138, 'login_as_user', 'Login as User', 'one-line', 0, 0, 1),
(139, 'site_roles', 'Site Roles', 'one-line', 0, 0, 1),
(140, 'full_name', 'Full Name', 'one-line', 0, 0, 1),
(141, 'block_user', 'Block User', 'one-line', 0, 0, 1),
(142, 'disable_private_messages', 'Disable Private Messages', 'one-line', 0, 0, 1),
(143, 'enable', 'Enable', 'one-line', 0, 0, 1),
(144, 'mention', '@Mention', 'one-line', 0, 0, 1),
(145, 'ban_users_from_site', 'Ban Users from Site', 'one-line', 0, 0, 1),
(146, 'string_constant', 'String Constant', 'one-line', 0, 0, 1),
(147, 'pwa_display', 'Display Mode', 'one-line', 0, 0, 1),
(148, 'deactivated', 'Deactivated', 'one-line', 0, 0, 1),
(149, 'online', 'Online', 'one-line', 0, 0, 1),
(150, 'messages_per_call', 'Messages per Call', 'one-line', 0, 0, 1),
(151, 'giphy', 'GIPHY', 'one-line', 0, 0, 1),
(152, 'unblocked', 'Unblocked', 'one-line', 0, 0, 1),
(153, 'users', 'Users', 'one-line', 0, 0, 1),
(154, 'manage_group_roles', 'Manage Group Roles', 'one-line', 0, 0, 1),
(155, 'storage_limit_exceeded', 'Storage Limit Exceeded', 'one-line', 0, 0, 1),
(156, 'ban_ip_addresses', 'Ban IP addresses', 'one-line', 0, 0, 1),
(157, 'shared_file', 'Shared a File', 'one-line', 0, 0, 1),
(158, 'rejected', 'Rejected', 'one-line', 0, 0, 1),
(159, 'leave_group', 'Leave Group', 'one-line', 0, 0, 1),
(160, 'chat', 'Chat', 'one-line', 0, 0, 1),
(161, 'custom_site_role', 'Custom Site Role', 'one-line', 0, 0, 1),
(162, 'complaints', 'Complaints', 'one-line', 0, 0, 1),
(163, 'secondary_border_color', 'Secondary Border Color', 'one-line', 0, 0, 1),
(164, 'attach', 'Share Files', 'one-line', 0, 0, 1),
(165, 'upload_files', 'Upload Files', 'one-line', 0, 0, 1),
(166, 'unbanned_from_group', 'Unbanned From Group', 'one-line', 0, 0, 1),
(167, 'radio_station', 'Radio Station', 'one-line', 0, 0, 1),
(168, 'reply_messages', 'Reply Messages', 'one-line', 0, 0, 1),
(169, 'options', 'Options', 'one-line', 0, 0, 1),
(170, 'on_new_site_badges', 'User awarded with new badge', 'one-line', 0, 0, 1),
(171, 'custom_field_5', 'Location', 'one-line', 1, 0, 1),
(172, 'access_token_expired', 'Access Token is not valid or has expired', 'one-line', 0, 0, 1),
(173, 'manage_user_roles', 'Manage User Roles', 'one-line', 0, 0, 1),
(174, 'edit_sticker_pack', 'Edit Sticker Pack', 'one-line', 0, 0, 1),
(175, 'download', 'Download', 'one-line', 0, 0, 1),
(176, 'guest_login', 'Guest Login', 'one-line', 0, 0, 1),
(177, 'remove_from_group', 'Remove from Group', 'one-line', 0, 0, 1),
(178, 'tenor', 'Tenor', 'one-line', 0, 0, 1),
(179, 'manage_site_roles', 'Manage Site Roles', 'one-line', 0, 0, 1),
(180, 'reactions', 'Reactions', 'one-line', 0, 0, 1),
(181, 'timezone', 'TimeZone', 'one-line', 0, 0, 1),
(182, 'attach_from_storage', 'Attach from Storage', 'one-line', 0, 0, 1),
(183, 'short_text_field', 'Short Text', 'one-line', 0, 0, 1),
(184, 'identity_provider', 'Identity Provider', 'one-line', 0, 0, 1),
(185, 'went_wrong', 'Something Went Wrong', 'one-line', 0, 0, 1),
(186, 'language', 'Language', 'one-line', 0, 0, 1),
(187, 'system_default', 'Default', 'one-line', 0, 0, 1),
(188, 'welcome_screen_heading', 'Hello, and a warm welcome to you', 'one-line', 0, 0, 1),
(189, 'requires_minimum_username_length', 'Requires Minimum Username Length', 'one-line', 0, 0, 1),
(190, 'video_files', 'Video Files', 'one-line', 0, 0, 1),
(191, 'file_expired', 'File Expired or Doesn&#039;t exist', 'one-line', 0, 0, 1),
(192, 'onesignal_app_id', 'OneSignal APP ID', 'one-line', 0, 0, 1),
(193, 'login_as_guest', 'Login as Guest', 'one-line', 0, 0, 1),
(194, 'user_email_verification', 'User Email Verification', 'one-line', 0, 0, 1),
(195, 'info', 'Info', 'one-line', 0, 0, 1),
(196, 'deactivate_account', 'Deactivate Account', 'one-line', 0, 0, 1),
(197, 'system_variables', 'System Variables', 'one-line', 0, 0, 1),
(198, 'pwa_icon', 'PWA icon', 'one-line', 0, 0, 1),
(199, 'shortcodes', 'Shortcodes', 'one-line', 0, 0, 1),
(200, 'back_to_login', 'Back to Login', 'one-line', 0, 0, 1),
(201, 'default_font', 'Default Font', 'one-line', 0, 0, 1),
(202, 'login_text', 'Sign In to Your Account', 'one-line', 0, 0, 1),
(203, 'confirm_delete', 'Are you sure you want to delete the selected item(s) ?', 'one-line', 0, 0, 1),
(204, 'load_more', 'Load More', 'one-line', 0, 0, 1),
(205, 'play_music', 'Play Music', 'one-line', 0, 0, 1),
(206, 'ffprobe_binaries_path', 'FFProbe Binaries Path', 'one-line', 0, 0, 1),
(207, 'invite_users', 'Invite Users', 'one-line', 0, 0, 1),
(208, 'sender', 'Sender', 'one-line', 0, 0, 1),
(209, 'confirm_join', 'Are you sure you would like to be part of this group?', 'one-line', 0, 0, 1),
(210, 'cron_job', 'Cron Job', 'one-line', 0, 0, 1),
(211, 'send_as_user', 'Send as another User', 'one-line', 0, 0, 1),
(212, 'unban', 'Unban', 'one-line', 0, 0, 1),
(213, 'change_to_idle_status_after', 'Change to Idle Status After (Minutes)', 'one-line', 0, 0, 1),
(214, 'tertiary_border_color', 'Tertiary Border Color', 'one-line', 0, 0, 1),
(215, 'audio_player', 'Audio Player', 'one-line', 0, 0, 1),
(216, 'smtp_authentication', 'SMTP Authentication', 'one-line', 0, 0, 1),
(217, 'yesterday', 'Yesterday', 'one-line', 0, 0, 1),
(218, 'screenshot', 'Screenshot', 'one-line', 0, 0, 1),
(219, 'file_name', 'File Name', 'one-line', 0, 0, 1),
(220, 'delete_own_message', 'Delete their own Message', 'one-line', 0, 0, 1),
(221, 'group_info', 'Group Info', 'one-line', 0, 0, 1),
(222, 'verification_code_expired', 'Verification code is not valid or has expired', 'one-line', 0, 0, 1),
(223, 'offline_page_expression', 'Oops!', 'one-line', 0, 0, 1),
(224, 'unleavable', 'Unleavable', 'one-line', 0, 0, 1),
(225, 'member', 'Member', 'one-line', 0, 0, 1),
(226, 'new', 'New', 'one-line', 0, 0, 1),
(227, 'whitelist', 'Whitelist', 'one-line', 0, 0, 1),
(228, 'confirm', 'Confirm', 'one-line', 0, 0, 1),
(229, 'open', 'Open', 'one-line', 0, 0, 1),
(230, 'banned_till', 'Banned Till', 'one-line', 0, 0, 1),
(231, 'onesignal_rest_api_key', 'OneSignal REST API Key', 'one-line', 0, 0, 1),
(232, 'offline_page_description', 'Sorry, it looks like you have lost your internet connection or the server is not responding at the moment. Please refresh the page or try again later.', 'one-line', 0, 0, 1),
(233, 'logout', 'Logout', 'one-line', 0, 0, 1),
(234, 'ignore_user_confirmation', 'Are you sure you want to ignore this user ?', 'one-line', 0, 0, 1),
(235, 'verification_email_subject', 'Confirm your email address', 'one-line', 0, 0, 1),
(236, 'created_group', 'Created Group', 'one-line', 0, 0, 1),
(237, 'sender_name', 'Sender Name', 'one-line', 0, 0, 1),
(238, 'group_icon', 'Group Icon', 'one-line', 0, 0, 1),
(239, 'add_audio', 'Add Audio', 'one-line', 0, 0, 1),
(240, 'account_not_found', 'Account Does Not Exist', 'one-line', 0, 0, 1),
(241, 'on_reply_group_messages', 'Someone replies to the user messages (Group Chat)', 'one-line', 0, 0, 1),
(242, 'visit', 'Visit', 'one-line', 0, 0, 1),
(243, 'send_message', 'Send Message', 'one-line', 0, 0, 1),
(244, 'exceeded_max_file_upload_size', 'Exceeded Maximum File Upload Size Limit', 'one-line', 0, 0, 1),
(245, 'unignore_user', 'Unignore User', 'one-line', 0, 0, 1),
(246, 'audio_files', 'Audio Files', 'one-line', 0, 0, 1),
(247, 'manage_avatars', 'Manage Avatars', 'one-line', 0, 0, 1),
(248, 'max_file_upload_size', 'Max File Upload Size (MB)', 'one-line', 0, 0, 1),
(249, 'other_features', 'Other Features', 'one-line', 0, 0, 1),
(250, 'who_all_can_send_messages', 'Who all can Send Messages', 'one-line', 0, 0, 1),
(251, 'newest', 'Newest', 'one-line', 0, 0, 1),
(252, 'social_login_providers', 'Social Login Providers', 'one-line', 0, 0, 1),
(253, 'providers', 'Providers', 'one-line', 0, 0, 1),
(254, 'minimum_score_required_wad_content', 'Minimum Score Required [Weapons, Alcohol &amp; Drugs] %', 'one-line', 0, 0, 1),
(255, 'you', 'You', 'one-line', 0, 0, 1),
(256, 'default_notification_tone', 'Default Notification Tone', 'one-line', 0, 0, 1),
(257, 'quaternary_font_size', 'Quaternary Font Size', 'one-line', 0, 0, 1),
(258, 'manage_custom_pages', 'Manage Custom Pages', 'one-line', 0, 0, 1),
(259, 'site_role', 'Site Role', 'one-line', 0, 0, 1),
(260, 'last_login', 'Last Login', 'one-line', 0, 0, 1),
(261, 'delete_shared_files', 'Delete Shared Files', 'one-line', 0, 0, 1),
(262, 'header', 'Header', 'one-line', 0, 0, 1),
(263, 'other_files', 'Other Files', 'one-line', 0, 0, 1),
(264, 'user_registration', 'User Registration', 'one-line', 0, 0, 1),
(265, 'callback_url', 'Callback URL', 'one-line', 0, 0, 1),
(266, 'invalid_email_address', 'Invalid Email Address', 'one-line', 0, 0, 1),
(267, 'block_user_confirmation', 'Are you sure you want to block this user ?', 'one-line', 0, 0, 1),
(268, 'allowed_file_formats', 'Allowed File Formats', 'one-line', 0, 0, 1),
(269, 'cover_pic', 'Cover Pic', 'one-line', 0, 0, 1),
(270, 'deleted', 'Deleted', 'one-line', 0, 0, 1),
(271, 'remove_user', 'Remove User', 'one-line', 0, 0, 1),
(272, 'web_push_mentioned_user_message', 'Mentioned you in Group Chat', 'one-line', 0, 0, 1),
(273, 'new_password', 'New Password', 'one-line', 0, 0, 1),
(274, 'stickers', 'Stickers', 'one-line', 0, 0, 1),
(275, 'firewall', 'Firewall', 'one-line', 0, 0, 1),
(276, 'ignore_user', 'Ignore User', 'one-line', 0, 0, 1),
(277, 'create_secret_group', 'Create Secret Group', 'one-line', 0, 0, 1),
(278, 'view_message', 'View Message', 'one-line', 0, 0, 1),
(279, 'appid', 'APP/Client ID', 'one-line', 0, 0, 1),
(280, 'all', 'All', 'one-line', 0, 0, 1),
(281, 'current_role', 'Current Role', 'one-line', 0, 0, 1),
(282, 'landing_page_footer_block_two_heading', 'Contact', 'one-line', 0, 0, 1),
(283, 'group', 'Group', 'one-line', 0, 0, 1),
(284, 'pwa_name', 'Application Name', 'one-line', 0, 0, 1),
(285, 'left_group', 'Left the Group Chat', 'one-line', 0, 0, 1),
(286, 'standalone', 'Standalone', 'one-line', 0, 0, 1),
(287, 'upload_custom_avatar', 'Upload Custom Avatar', 'one-line', 0, 0, 1),
(288, 'signup', 'Signup', 'one-line', 0, 0, 1),
(289, 'custom_field_6_options', '{\"DZ\":\"Algeria\",\"AO\":\"Angola\",\"BJ\":\"Benin\",\"BW\":\"Botswana\",\"BF\":\"Burkina Faso\",\"BI\":\"Burundi\",\"CM\":\"Cameroon\",\"CV\":\"Cape Verde\",\"CF\":\"Central African Republic\",\"TD\":\"Chad\",\"KM\":\"Comoros\",\"CD\":\"Congo [DRC]\",\"CG\":\"Congo [Republic]\",\"DJ\":\"Djibouti\",\"EG\":\"Egypt\",\"GQ\":\"Equatorial Guinea\",\"ER\":\"Eritrea\",\"ET\":\"Ethiopia\",\"GA\":\"Gabon\",\"GM\":\"Gambia\",\"GH\":\"Ghana\",\"GN\":\"Guinea\",\"GW\":\"Guinea-Bissau\",\"CI\":\"Ivory Coast\",\"KE\":\"Kenya\",\"LS\":\"Lesotho\",\"LR\":\"Liberia\",\"LY\":\"Libya\",\"MG\":\"Madagascar\",\"MW\":\"Malawi\",\"ML\":\"Mali\",\"MR\":\"Mauritania\",\"MU\":\"Mauritius\",\"YT\":\"Mayotte\",\"MA\":\"Morocco\",\"MZ\":\"Mozambique\",\"NA\":\"Namibia\",\"NE\":\"Niger\",\"NG\":\"Nigeria\",\"RW\":\"Rwanda\",\"RE\":\"R\\u00e9union\",\"SH\":\"Saint Helena\",\"SN\":\"Senegal\",\"SC\":\"Seychelles\",\"SL\":\"Sierra Leone\",\"SO\":\"Somalia\",\"ZA\":\"South Africa\",\"SD\":\"Sudan\",\"SZ\":\"Swaziland\",\"ST\":\"S\\u00e3o Tom\\u00e9 and Pr\\u00edncipe\",\"TZ\":\"Tanzania\",\"TG\":\"Togo\",\"TN\":\"Tunisia\",\"UG\":\"Uganda\",\"EH\":\"Western Sahara\",\"ZM\":\"Zambia\",\"ZW\":\"Zimbabwe\",\"AQ\":\"Antarctica\",\"BV\":\"Bouvet Island\",\"TF\":\"French Southern Territories\",\"HM\":\"Heard Island and McDonald Island\",\"GS\":\"South Georgia and the South Sandwich Islands\",\"AF\":\"Afghanistan\",\"AM\":\"Armenia\",\"AZ\":\"Azerbaijan\",\"BH\":\"Bahrain\",\"BD\":\"Bangladesh\",\"BT\":\"Bhutan\",\"IO\":\"British Indian Ocean Territory\",\"BN\":\"Brunei\",\"KH\":\"Cambodia\",\"CN\":\"China\",\"CX\":\"Christmas Island\",\"CC\":\"Cocos [Keeling] Islands\",\"GE\":\"Georgia\",\"HK\":\"Hong Kong\",\"IN\":\"India\",\"ID\":\"Indonesia\",\"IR\":\"Iran\",\"IQ\":\"Iraq\",\"IL\":\"Israel\",\"JP\":\"Japan\",\"JO\":\"Jordan\",\"KZ\":\"Kazakhstan\",\"KW\":\"Kuwait\",\"KG\":\"Kyrgyzstan\",\"LA\":\"Laos\",\"LB\":\"Lebanon\",\"MO\":\"Macau\",\"MY\":\"Malaysia\",\"MV\":\"Maldives\",\"MN\":\"Mongolia\",\"MM\":\"Myanmar [Burma]\",\"NP\":\"Nepal\",\"KP\":\"North Korea\",\"OM\":\"Oman\",\"PK\":\"Pakistan\",\"PS\":\"Palestinian Territories\",\"PH\":\"Philippines\",\"QA\":\"Qatar\",\"SA\":\"Saudi Arabia\",\"SG\":\"Singapore\",\"KR\":\"South Korea\",\"LK\":\"Sri Lanka\",\"SY\":\"Syria\",\"TW\":\"Taiwan\",\"TJ\":\"Tajikistan\",\"TH\":\"Thailand\",\"TR\":\"Turkey\",\"TM\":\"Turkmenistan\",\"AE\":\"United Arab Emirates\",\"UZ\":\"Uzbekistan\",\"VN\":\"Vietnam\",\"YE\":\"Yemen\",\"AL\":\"Albania\",\"AD\":\"Andorra\",\"AT\":\"Austria\",\"BY\":\"Belarus\",\"BE\":\"Belgium\",\"BA\":\"Bosnia and Herzegovina\",\"BG\":\"Bulgaria\",\"HR\":\"Croatia\",\"CY\":\"Cyprus\",\"CZ\":\"Czech Republic\",\"DK\":\"Denmark\",\"EE\":\"Estonia\",\"FO\":\"Faroe Islands\",\"FI\":\"Finland\",\"FR\":\"France\",\"DE\":\"Germany\",\"GI\":\"Gibraltar\",\"GR\":\"Greece\",\"GG\":\"Guernsey\",\"HU\":\"Hungary\",\"IS\":\"Iceland\",\"IE\":\"Ireland\",\"IM\":\"Isle of Man\",\"IT\":\"Italy\",\"JE\":\"Jersey\",\"XK\":\"Kosovo\",\"LV\":\"Latvia\",\"LI\":\"Liechtenstein\",\"LT\":\"Lithuania\",\"LU\":\"Luxembourg\",\"MK\":\"Macedonia\",\"MT\":\"Malta\",\"MD\":\"Moldova\",\"MC\":\"Monaco\",\"ME\":\"Montenegro\",\"NL\":\"Netherlands\",\"NO\":\"Norway\",\"PL\":\"Poland\",\"PT\":\"Portugal\",\"RO\":\"Romania\",\"RU\":\"Russia\",\"SM\":\"San Marino\",\"RS\":\"Serbia\",\"CS\":\"Serbia and Montenegro\",\"SK\":\"Slovakia\",\"SI\":\"Slovenia\",\"ES\":\"Spain\",\"SJ\":\"Svalbard and Jan Mayen\",\"SE\":\"Sweden\",\"CH\":\"Switzerland\",\"UA\":\"Ukraine\",\"GB\":\"United Kingdom\",\"VA\":\"Vatican City\",\"AX\":\"\\u00c5land Islands\",\"AI\":\"Anguilla\",\"AG\":\"Antigua and Barbuda\",\"AW\":\"Aruba\",\"BS\":\"Bahamas\",\"BB\":\"Barbados\",\"BZ\":\"Belize\",\"BM\":\"Bermuda\",\"BQ\":\"Bonaire\",\"VG\":\"British Virgin Islands\",\"CA\":\"Canada\",\"KY\":\"Cayman Islands\",\"CR\":\"Costa Rica\",\"CU\":\"Cuba\",\"CW\":\"Curacao\",\"DM\":\"Dominica\",\"DO\":\"Dominican Republic\",\"SV\":\"El Salvador\",\"GL\":\"Greenland\",\"GD\":\"Grenada\",\"GP\":\"Guadeloupe\",\"GT\":\"Guatemala\",\"HT\":\"Haiti\",\"HN\":\"Honduras\",\"JM\":\"Jamaica\",\"MQ\":\"Martinique\",\"MX\":\"Mexico\",\"MS\":\"Montserrat\",\"AN\":\"Netherlands Antilles\",\"NI\":\"Nicaragua\",\"PA\":\"Panama\",\"PR\":\"Puerto Rico\",\"BL\":\"Saint Barth\\u00e9lemy\",\"KN\":\"Saint Kitts and Nevis\",\"LC\":\"Saint Lucia\",\"MF\":\"Saint Martin\",\"PM\":\"Saint Pierre and Miquelon\",\"VC\":\"Saint Vincent and the Grenadines\",\"SX\":\"Sint Maarten\",\"TT\":\"Trinidad and Tobago\",\"TC\":\"Turks and Caicos Islands\",\"VI\":\"U.S. Virgin Islands\",\"US\":\"United States\",\"AR\":\"Argentina\",\"BO\":\"Bolivia\",\"BR\":\"Brazil\",\"CL\":\"Chile\",\"CO\":\"Colombia\",\"EC\":\"Ecuador\",\"FK\":\"Falkland Islands\",\"GF\":\"French Guiana\",\"GY\":\"Guyana\",\"PY\":\"Paraguay\",\"PE\":\"Peru\",\"SR\":\"Suriname\",\"UY\":\"Uruguay\",\"VE\":\"Venezuela\",\"AS\":\"American Samoa\",\"AU\":\"Australia\",\"CK\":\"Cook Islands\",\"TL\":\"East Timor\",\"FJ\":\"Fiji\",\"PF\":\"French Polynesia\",\"GU\":\"Guam\",\"KI\":\"Kiribati\",\"MH\":\"Marshall Islands\",\"FM\":\"Micronesia\",\"NR\":\"Nauru\",\"NC\":\"New Caledonia\",\"NZ\":\"New Zealand\",\"NU\":\"Niue\",\"NF\":\"Norfolk Island\",\"MP\":\"Northern Mariana Islands\",\"PW\":\"Palau\",\"PG\":\"Papua New Guinea\",\"PN\":\"Pitcairn Islands\",\"WS\":\"Samoa\",\"SB\":\"Solomon Islands\",\"TK\":\"Tokelau\",\"TO\":\"Tonga\",\"TV\":\"Tuvalu\",\"UM\":\"U.S. Minor Outlying Islands\",\"VU\":\"Vanuatu\",\"WF\":\"Wallis and Futuna\"}', 'multi_line', 1, 0, 1),
(290, 'share_screenshot', 'Share Screenshot', 'one-line', 0, 0, 1),
(291, 'import_json', 'Select JSON File', 'one-line', 0, 0, 1),
(292, 'site_description', 'Site Description', 'one-line', 0, 0, 1),
(293, 'remove_custom_bg', 'Remove Custom BG', 'one-line', 0, 0, 1),
(294, 'logo', 'Logo', 'one-line', 0, 0, 1),
(295, 'view_profile', 'View Profile', 'one-line', 0, 0, 1),
(296, 'add_site_members', 'Add Site Members', 'one-line', 0, 0, 1),
(297, 'disable', 'Disable', 'one-line', 0, 0, 1),
(298, 'pwa_background_color', 'Background Color', 'one-line', 0, 0, 1),
(299, 'email_settings', 'Email Settings', 'one-line', 0, 0, 1),
(300, 'create_group', 'Create Group', 'one-line', 0, 0, 1),
(301, 'label_background_color', 'Label Background Color', 'one-line', 0, 0, 1),
(302, 'all_file_formats', 'All File Formats', 'one-line', 0, 0, 1),
(303, 'link_type', 'Link Type', 'one-line', 0, 0, 1),
(304, 'today', 'Today', 'one-line', 0, 0, 1),
(305, 'gravatar', 'Gravatar', 'one-line', 0, 0, 1),
(306, 'invite', 'Invite', 'one-line', 0, 0, 1),
(307, 'create_role', 'Create Role', 'one-line', 0, 0, 1),
(308, 'block_users', 'Block Users', 'one-line', 0, 0, 1),
(309, 'custom_menu_item_1', 'Terms', 'one-line', 0, 0, 1),
(310, 'tertiary_font_size', 'Tertiary Font Size', 'one-line', 0, 0, 1),
(311, 'online_users', 'Online Users', 'one-line', 0, 0, 1),
(312, 'custom_login_url', 'Custom Login URL', 'one-line', 0, 0, 1),
(313, 'create_user', 'Create User', 'one-line', 0, 0, 1),
(314, 'show_only_on', 'Show Only On', 'one-line', 0, 0, 1),
(315, 'senary_border_color', 'Senary Border Color', 'one-line', 0, 0, 1),
(316, 'identifier', 'Identifier', 'one-line', 0, 0, 1),
(317, 'react_messages', 'React to Messages', 'one-line', 0, 0, 1),
(318, 'update', 'Update', 'one-line', 0, 0, 1),
(319, 'default_site_role', 'Default Site Role', 'one-line', 0, 0, 1),
(320, 'advert_min_height', 'Minimum Height (px)', 'one-line', 0, 0, 1),
(321, 'site_records', 'Site Records', 'one-line', 0, 0, 1),
(322, 'slug_already_exists', 'Slug Already Exists', 'one-line', 0, 0, 1),
(323, 'verification_email_heading', 'Email Confirmation', 'one-line', 0, 0, 1),
(324, 'avatars', 'Avatars', 'one-line', 0, 0, 1),
(325, 'smtp_password', 'SMTP Password', 'one-line', 0, 0, 1),
(326, 'forgot_password_text', 'We will send you password recovery instruction to the email address associated with your account.', 'one-line', 0, 0, 1),
(327, 'open_in_new_tab', 'Open in New Tab', 'one-line', 0, 0, 1),
(328, 'settings', 'Settings', 'one-line', 0, 0, 1),
(329, 'username_already_exists', 'Username Already Exists', 'one-line', 0, 0, 1),
(330, 'edit_provider', 'Edit Provider', 'one-line', 0, 0, 1),
(331, 'show', 'Show', 'one-line', 0, 0, 1),
(332, 'smtp_username', 'SMTP Username', 'one-line', 0, 0, 1),
(333, 'reset_password_success_message', 'We have sent you an e-mail containing your password reset link. Click the link in the email to create a new password. ', 'one-line', 0, 0, 1),
(334, 'temporarily_banned', 'Temporarily Banned', 'one-line', 0, 0, 1),
(335, 'generate_link_preview', 'Generate Link Preview', 'one-line', 0, 0, 1),
(336, 'google_analytics_id', 'Google Analytics ID', 'one-line', 0, 0, 1),
(337, 'device_blocked', 'Your Device had been Blocked. Maximum Login Attempts Exceeded. Try again in one hour.', 'one-line', 0, 0, 1),
(338, 'edit_group', 'Edit Group', 'one-line', 0, 0, 1),
(339, 'uploading', 'Uploading', 'one-line', 0, 0, 1),
(340, 'inbox', 'Inbox', 'one-line', 0, 0, 1),
(341, 'pwa_theme_color', 'Theme Color', 'one-line', 0, 0, 1),
(342, 'login_link_email_heading', 'Welcome To The Community', 'one-line', 0, 0, 1),
(343, 'onesignal', 'OneSignal', 'one-line', 0, 0, 1),
(344, 'create_protected_group', 'Create Protected Group', 'one-line', 0, 0, 1),
(345, 'minimum_score_required_graphic_violence_gore', 'Minimum Score Required [Graphic Violence &amp; Gore] %', 'one-line', 0, 0, 1),
(346, 'documents', 'Documents', 'one-line', 0, 0, 1),
(347, 'files', 'Files', 'one-line', 0, 0, 1),
(348, 'gifs_per_load', 'GIFs Per Load', 'one-line', 0, 0, 1),
(349, 'upload_avatar', 'Upload Avatar', 'one-line', 0, 0, 1),
(350, 'wait_for_profile_approval', 'Your Account is currently pending approval. Once your profile has been approved you can login.', 'one-line', 0, 0, 1),
(351, 'upload_file', 'Upload File', 'one-line', 0, 0, 1),
(352, 'unignore', 'Unignore', 'one-line', 0, 0, 1),
(353, 'inappropriate', 'Inappropriate', 'one-line', 0, 0, 1),
(354, 'custom_url_on_logout', 'Custom URL on Logout', 'one-line', 0, 0, 1),
(355, 'search_messages', 'Search messages', 'one-line', 0, 0, 1),
(356, 'non_latin_usernames', 'Non Latin Usernames', 'one-line', 0, 0, 1),
(357, 'static_image', 'Static Image', 'one-line', 0, 0, 1),
(358, 'tertiary_text_color', 'Tertiary Text Color', 'one-line', 0, 0, 1),
(359, 'chat_message', 'Chat Message', 'one-line', 0, 0, 1),
(360, 'report_message', 'Report Message', 'one-line', 0, 0, 1),
(361, 'change_full_name', 'Change Full Name', 'one-line', 0, 0, 1),
(362, 'hero_section_animation', 'Hero Section [Animation]', 'one-line', 0, 0, 1),
(363, 'admin', 'Admin', 'one-line', 0, 0, 1),
(364, 'id', 'ID', 'one-line', 0, 0, 1),
(365, 'reload', 'Reload', 'one-line', 0, 0, 1),
(366, 'infotip_select_multiple_files', 'To select multiple files, hold the Ctrl (PC) or Command (Mac) key and using your trackpad or external mouse, click on all the other files you wish to select one by one.', 'one-line', 0, 0, 1),
(367, 'field_options', 'Field Options', 'one-line', 0, 0, 1),
(368, 'track_status', 'Track Status', 'one-line', 0, 0, 1),
(369, 'left_panel_content_on_page_load', 'Content to Show on Page Load [Left Panel]', 'one-line', 0, 0, 1),
(370, 'group_members', 'Group Members', 'one-line', 0, 0, 1),
(371, 'nickname', 'Nickname', 'one-line', 0, 0, 1),
(372, 'view_friends', 'View Friends', 'one-line', 0, 0, 1),
(373, 'notification_tone', 'Notification Tone', 'one-line', 0, 0, 1),
(374, 'create_user_if_not_exists', 'Create User if not exists', 'one-line', 0, 0, 1),
(375, 'hide_group_member_list_from_non_members', 'Hide Group Member list from Non members', 'one-line', 0, 0, 1),
(376, 'on_group_invitation', 'Someone send an invitation to join a group', 'one-line', 0, 0, 1),
(377, 'download_file', 'Download File', 'one-line', 0, 0, 1),
(378, 'side_navigation', 'Side Navigation', 'one-line', 0, 0, 1),
(379, 'smtp_host', 'SMTP Host', 'one-line', 0, 0, 1),
(380, 'edit_own_group', 'Edit own Group', 'one-line', 0, 0, 1),
(381, 'add', 'Add', 'one-line', 0, 0, 1),
(382, 'on_group_unread_count_change', 'On Group Unread Count Change', 'one-line', 0, 0, 1),
(383, 'set_as_default', 'Set as Default', 'one-line', 0, 0, 1),
(384, 'not_found_page_button', 'Go To Homepage', 'one-line', 0, 0, 1),
(385, 'image_moderation', 'Image Moderation', 'one-line', 0, 0, 1),
(386, 'go_online', 'Go Online', 'one-line', 0, 0, 1),
(387, 'add_members', 'Add Members', 'one-line', 0, 0, 1),
(388, 'display_full_file_name_of_attachments', 'Display full file name of Attachment(s)', 'one-line', 0, 0, 1),
(389, 'exceeded_group_join_limit', 'Exceeded Maximum Number of Groups You can Join', 'one-line', 0, 0, 1),
(390, 'created_on', 'Created On', 'one-line', 0, 0, 1),
(391, 'profile', 'Profile', 'one-line', 0, 0, 1),
(392, 'confirm_email_address', 'You are required to verify your email address. We have sent an email with a confirmation link to your email address.', 'one-line', 0, 0, 1),
(393, 'ban_ip_addresses_confirmation', 'Are you sure you want to block user IP addresses ?', 'one-line', 0, 0, 1),
(394, 'landing_page_groups_section_heading', 'Trending Groups', 'one-line', 0, 0, 1),
(395, 'invalid_login', 'Invalid Login Credentials', 'one-line', 0, 0, 1),
(396, 'or_login_using', 'or login using', 'one-line', 0, 0, 1),
(397, 'reset_password_email_content', 'Resetting your password is easy. Just press the button below and you will be auto logged in to your account. If you did not make this request then please ignore this email.', 'one-line', 0, 0, 1),
(398, 'user_agent', 'User Agent', 'one-line', 0, 0, 1),
(399, 'monitor', 'Monitor', 'one-line', 0, 0, 1),
(400, 'show_on_entry_page', 'Show on Entry Page', 'one-line', 0, 0, 1),
(401, 'attach_gifs', 'Attach GIFs', 'one-line', 0, 0, 1),
(402, 'assign', 'Assign', 'one-line', 0, 0, 1),
(403, 'landing_page_hero_section_heading', 'Much more than just another chat site', 'one-line', 0, 0, 1),
(404, 'auto_join_group', 'Auto Add Users on Signup', 'one-line', 0, 0, 1),
(405, 'offline_page_title', 'You are Offline', 'one-line', 0, 0, 1),
(406, 'action_taken', 'Action Taken', 'one-line', 0, 0, 1),
(407, 'unban_ip_addresses_confirmation', 'Are you sure you want to allow user IP addresses ?', 'one-line', 0, 0, 1),
(408, 'not_logged_in_message', 'You are not logged in. Click here to log in.', 'one-line', 0, 0, 1),
(409, 'link_field', 'Link', 'one-line', 0, 0, 1),
(410, 'open_in_same_window', 'Open in Same Window', 'one-line', 0, 0, 1),
(411, 'your_friend_since', 'Your Friend Since', 'one-line', 0, 0, 1),
(412, 'access_denied_message', '403 Forbidden You don&#039;t have permission to access.', 'one-line', 0, 0, 1),
(413, 'exceeds_username_length', 'Exceeds Maximum Username Length', 'one-line', 0, 0, 1),
(414, 'initiate_private_chat', 'Initiate Private Chat', 'one-line', 0, 0, 1),
(415, 'septenary_text_color', 'Septenary Text Color', 'one-line', 0, 0, 1),
(416, 'updated', 'Updated', 'one-line', 0, 0, 1),
(417, 'left_side_content', 'Left Side Content', 'one-line', 0, 0, 1),
(418, 'mail_login_info', 'Mail Login Info', 'one-line', 0, 0, 1),
(419, 'view_online_users', 'View Online Users', 'one-line', 0, 0, 1),
(420, 'smtp_protocol', 'SMTP (SSL/TLS)', 'one-line', 0, 0, 1),
(421, 'members', 'Members', 'one-line', 0, 0, 1),
(422, 'enabled', 'Enabled', 'one-line', 0, 0, 1),
(423, 'delete_messages', 'Delete Msgs', 'one-line', 0, 0, 1),
(424, 'email_address', 'Email Address', 'one-line', 0, 0, 1),
(425, 'set_custom_background', 'Set Custom background', 'one-line', 0, 0, 1),
(426, 'pwa_settings', 'PWA Settings', 'one-line', 0, 0, 1),
(427, 'bottom', 'Bottom', 'one-line', 0, 0, 1),
(428, 'replied_group_message', 'Posted a response to your group chat message', 'one-line', 0, 0, 1),
(429, 'mobile_page_transition', 'Mobile Page Transition', 'one-line', 0, 0, 1),
(430, 'sightengine_api_user', 'Sightengine API user', 'one-line', 0, 0, 1),
(431, 'wad_content', 'Weapons, Alcohol &amp; Drugs', 'one-line', 0, 0, 1),
(432, 'social_login', 'Social Login', 'one-line', 0, 0, 1),
(433, 'verification_email_content', 'Before you get started, we need to validate your email address. Please click on the button below to verify your email address. If you did not make this request then please ignore this email.', 'one-line', 0, 0, 1),
(434, 'create_advert', 'Create Advert', 'one-line', 0, 0, 1),
(435, 'badge_title', 'Badge Title', 'one-line', 0, 0, 1),
(436, 'switch', 'Switch', 'one-line', 0, 0, 1),
(437, 'moderation', 'Moderation', 'one-line', 0, 0, 1),
(438, 'footer_logo', 'Footer Logo', 'one-line', 0, 0, 1),
(439, 'choose_avatar', 'Choose an Avatar', 'one-line', 0, 0, 1),
(440, 'req_min_msg_length', 'Required Minimum number of characters', 'one-line', 0, 0, 1),
(441, 'undenary_text_color', 'Undenary Text Color', 'one-line', 0, 0, 1),
(442, 'timestamp', 'Timestamp', 'one-line', 0, 0, 1),
(443, 'banned_page_description', 'Your have been banned from using this website. If you think your account was banned by mistake, please email us and we&#039;ll look into your case.', 'one-line', 0, 0, 1),
(444, 'import', 'Import', 'one-line', 0, 0, 1),
(445, 'quaternary_bg_color', 'Quaternary Background Color', 'one-line', 0, 0, 1),
(446, 'audio', 'Audio', 'one-line', 0, 0, 1),
(447, 'check_read_receipts', 'Check Read Receipts', 'one-line', 0, 0, 1),
(448, 'system_messages_groups', 'System Messages (Groups)', 'one-line', 0, 0, 1),
(449, 'click_to_view_info', 'Click here to view info', 'one-line', 0, 0, 1),
(450, 'comments_by_reviewer', 'Comments by Reviewer', 'one-line', 0, 0, 1),
(451, 'group_name', 'Group Name', 'one-line', 0, 0, 1),
(452, 'add_custom_field', 'Add Field', 'one-line', 0, 0, 1),
(453, 'daily_send_limit_group_messages', 'Daily Send Limit [Group Messages]', 'one-line', 0, 0, 1),
(454, 'cancel_request', 'Cancel Request', 'one-line', 0, 0, 1),
(455, 'iso_language_code', 'ISO Language Code', 'one-line', 0, 0, 1),
(456, 'default_group_role', 'Default Group Role', 'one-line', 0, 0, 1),
(457, 'add_menu_item', 'Add Menu Item', 'one-line', 0, 0, 1),
(458, 'custom_page_2', 'Terms of Service', 'multi_line', 1, 1, 1),
(459, 'denary_border_color', 'Denary Border Color', 'one-line', 0, 0, 1),
(460, 'posted_by', 'Posted by', 'one-line', 0, 0, 1),
(461, 'download_attachments', 'Download Attachments', 'one-line', 0, 0, 1),
(462, 'rebuild', 'Rebuild', 'one-line', 0, 0, 1),
(463, 'unverified_users', 'Unverified Users', 'one-line', 0, 0, 1),
(464, 'language_text_direction', 'Text direction', 'one-line', 0, 0, 1),
(465, 'chat_page_boxed_layout', 'Boxed Layout [Chat Page]', 'one-line', 0, 0, 1),
(466, 'supported_image_formats', 'Supported Image Formats', 'one-line', 0, 0, 1),
(467, 'load_group_info_on_group_load', 'Load Group Info on Group Load', 'one-line', 0, 0, 1),
(468, 'minimum_score_required_explicit_nudity', 'Minimum Score Required [Explicit Nudity] %', 'one-line', 0, 0, 1),
(469, 'remove_cover_pic', 'Remove Cover Pic', 'one-line', 0, 0, 1),
(470, 'exporting', 'Exporting', 'one-line', 0, 0, 1),
(471, 'login_as_admin', 'Login as Admin', 'one-line', 0, 0, 1),
(472, 'maximum_sending_rate_exceeded', 'Maximum Sending Rate Exceeded', 'one-line', 0, 0, 1),
(473, 'webpushr_rest_api_key', 'Webpushr REST API Key', 'one-line', 0, 0, 1),
(474, 'yes', 'Yes', 'one-line', 0, 0, 1),
(475, 'remove', 'Remove', 'one-line', 0, 0, 1),
(476, 'loading', 'Loading', 'one-line', 0, 0, 1),
(477, 'people_nearby_feature', 'People Nearby Feature', 'one-line', 0, 0, 1),
(478, 'password', 'Password', 'one-line', 0, 0, 1),
(479, 'total_groups', 'Total Groups', 'one-line', 0, 0, 1),
(480, 'edit_custom_field', 'Edit Custom Field', 'one-line', 0, 0, 1),
(481, 'media', 'Media', 'one-line', 0, 0, 1),
(482, 'senary_font_size', 'Senary Font Size', 'one-line', 0, 0, 1),
(483, 'body', 'Body', 'one-line', 0, 0, 1),
(484, 'read_receipts', 'Read Receipts', 'one-line', 0, 0, 1),
(485, 'send_push_notification', 'Send Push Notification', 'one-line', 0, 0, 1),
(486, 'icon', 'Icon', 'one-line', 0, 0, 1),
(487, 'review_complaints', 'Review Complaints', 'one-line', 0, 0, 1),
(488, 'display_username_group_chats', 'Display Username instead of Full Name in Group Chats', 'one-line', 0, 0, 1),
(489, 'browser', 'Browser', 'one-line', 0, 0, 1),
(490, 'unsuspend', 'Unsuspend', 'one-line', 0, 0, 1),
(491, 'group_headers', 'Group Headers', 'one-line', 0, 0, 1),
(492, 'cancel', 'Cancel', 'one-line', 0, 0, 1),
(493, 'cookie_consent', 'Cookie Consent', 'one-line', 0, 0, 1),
(494, 'cookie_consent_modal_title', 'Cookie Consent', 'one-line', 0, 0, 1),
(495, 'landing_page_faq_question_3', '', 'one-line', 0, 0, 1),
(496, 'chats', 'Chats', 'one-line', 0, 0, 1),
(497, 'secret_key', 'Secret Key', 'one-line', 0, 0, 1),
(498, 'remove_from_group_confirmation', 'Are you sure you want to remove this user from Group ?', 'one-line', 0, 0, 1),
(499, 'style_sheets', 'Style Sheets', 'one-line', 0, 0, 1),
(500, 'info_panel', 'Info Panel', 'one-line', 0, 0, 1),
(501, 'clear_chat', 'Clear Chat', 'one-line', 0, 0, 1),
(502, 'name_color', 'Name Color', 'one-line', 0, 0, 1),
(503, 'new_user_approval', 'New User Approval (Manually Approve New Users)', 'one-line', 0, 0, 1),
(504, 'menu_title', 'Menu Title', 'one-line', 0, 0, 1),
(505, 'files_uploaded', 'Files Uploaded', 'one-line', 0, 0, 1),
(506, 'set_auto_join_groups', 'Set Auto Join Groups', 'one-line', 0, 0, 1),
(507, 'ad_free_account', 'Ad-Free Account', 'one-line', 0, 0, 1),
(508, 'webpushr_authentication_token', 'Webpushr Authentication Token', 'one-line', 0, 0, 1),
(509, 'menu_item_visibility', 'Menu Item Visibility', 'one-line', 0, 0, 1),
(510, 'on_leaving_group_chat', 'Someone leaves Group Chat', 'one-line', 0, 0, 1),
(511, 'ssl', 'SSL', 'one-line', 0, 0, 1),
(512, 'voice_message', 'Voice Message', 'one-line', 0, 0, 1),
(513, 'banned_users', 'Banned Users', 'one-line', 0, 0, 1),
(514, 'gif_content_filtering', 'GIF Content Filtering', 'one-line', 0, 0, 1),
(515, 'stream_url', 'Stream URL', 'one-line', 0, 0, 1),
(516, 'on_load_guest_login_window', 'Open Guest Login Window on Load (Login Page)', 'one-line', 0, 0, 1),
(517, 'username_condition', 'Your username must contain at least one letter', 'one-line', 0, 0, 1),
(518, 'show_group_label', 'Show Group Role Next to Chat Message', 'one-line', 0, 0, 1),
(519, 'octonary_bg_color', 'Octonary Background Color', 'one-line', 0, 0, 1),
(520, 'custom_field_6', 'Country', 'one-line', 1, 0, 1),
(521, 'listen_music', 'Listen Music', 'one-line', 0, 0, 1),
(522, 'entry_page', 'Entry Page', 'one-line', 0, 0, 1),
(523, 'exceeds_full_name_length', 'Full Name character length limit exceeded', 'one-line', 0, 0, 1),
(524, 'error_uploading', 'Error Uploading Files', 'one-line', 0, 0, 1),
(525, 'delete_account', 'Delete Account', 'one-line', 0, 0, 1),
(526, 'share', 'Share', 'one-line', 0, 0, 1),
(527, 'edit_menu_item', 'Edit Menu Item', 'one-line', 0, 0, 1),
(528, 'welcome_screen', 'Welcome Screen', 'one-line', 0, 0, 1),
(529, 'remove_password', 'Remove Password', 'one-line', 0, 0, 1),
(530, 'unban_from_group_confirmation', 'Are you sure you want to unban this user from Group ?', 'one-line', 0, 0, 1),
(531, 'onesignal_prompt_accept_button', 'Allow', 'one-line', 0, 0, 1),
(532, 'embed_code', 'Embed Code', 'one-line', 0, 0, 1),
(533, 'moderators', 'Moderators', 'one-line', 0, 0, 1),
(534, 'customizer', 'Customizer', 'one-line', 0, 0, 1),
(535, 'answer', 'Answer', 'one-line', 0, 0, 1),
(536, 'landing_page_footer_block_two_description', '+44 1632 960811hello@yourdomain.com', 'one-line', 0, 0, 1),
(537, 'css_code', 'CSS Code', 'one-line', 0, 0, 1),
(538, 'delete_all_files_confirmation', 'Are you sure you want to delete all files ?', 'one-line', 0, 0, 1),
(539, 'view_public_groups', 'View Public Groups', 'one-line', 0, 0, 1),
(540, 'infotip_header_tag', 'This will be printed in the &lt;head&gt; section', 'one-line', 0, 0, 1),
(541, 'hero_section_description', 'Hero Section [Description]', 'one-line', 0, 0, 1),
(542, 'message_id', 'Message ID', 'one-line', 0, 0, 1),
(543, 'display_username_private_chats', 'Display Username instead of Full Name in Private Chats', 'one-line', 0, 0, 1),
(544, 'delete_private_messages', 'Delete Private Messages', 'one-line', 0, 0, 1),
(545, 'create_account', 'Create Account', 'one-line', 0, 0, 1),
(546, 'badge_image', 'Badge Image', 'one-line', 0, 0, 1),
(547, 'badges', 'Badges', 'one-line', 0, 0, 1),
(548, 'show_on_landing_page_header', 'Show on Landing Page [Header]', 'one-line', 0, 0, 1),
(549, 'visible', 'Visible', 'one-line', 0, 0, 1),
(550, 'ftp_storage', 'FTP Storage', 'one-line', 0, 0, 1),
(551, 'location', 'Location', 'one-line', 0, 0, 1),
(552, 'edit_group_header', 'Edit Group Header', 'one-line', 0, 0, 1),
(553, 'on_join_group_chat', 'Someone joins Group Chat', 'one-line', 0, 0, 1),
(554, 'show_only_on_specific_language', 'Show only on Specific Language', 'one-line', 0, 0, 1),
(555, 'hide_groups_on_group_url', 'Hide groups when a user visits through the group URL', 'one-line', 0, 0, 1),
(556, 'sticker', 'Sticker', 'one-line', 0, 0, 1),
(557, 'edit_users', 'Edit Users', 'one-line', 0, 0, 1);
INSERT INTO `gr_language_strings` (`string_id`, `string_constant`, `string_value`, `string_type`, `skip_update`, `skip_cache`, `language_id`) VALUES
(558, 'maximum_username_length', 'Maximum Username Length', 'one-line', 0, 0, 1),
(559, 'set_cover_pic', 'Set Cover Pic', 'one-line', 0, 0, 1),
(560, 'forgot_password', 'Forgot Password', 'one-line', 0, 0, 1),
(561, 'default_txt', 'Default', 'one-line', 0, 0, 1),
(562, 'not_assigned', 'Not Assigned', 'one-line', 0, 0, 1),
(563, 'landing_page_faq_question_2_answer', '02 Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ', 'one-line', 0, 0, 1),
(564, 'review', 'Review', 'one-line', 0, 0, 1),
(565, 'rebuild_cache', 'Rebuild Cache', 'one-line', 0, 0, 1),
(566, 'is_typing', 'is typing', 'one-line', 0, 0, 1),
(567, 'landing_page_faq_question_3_answer', '', 'one-line', 0, 0, 1),
(568, 'unbanned', 'Unbanned', 'one-line', 0, 0, 1),
(569, 'zero_equals_unlimited', '(0 = Unlimited)', 'one-line', 0, 0, 1),
(570, 'offensive_signs_gestures', 'Offensive Signs &amp; Gestures', 'one-line', 0, 0, 1),
(571, 'twitter_url', 'Twitter URL', 'one-line', 0, 0, 1),
(572, 'icon_img', 'Icon', 'one-line', 0, 0, 1),
(573, 'view_shared_links', 'View Shared Links', 'one-line', 0, 0, 1),
(574, 'captcha_secret_key', 'Captcha Secret Key', 'one-line', 0, 0, 1),
(575, 'filter', 'Filter', 'one-line', 0, 0, 1),
(576, 'no_results_found', 'No Results Found', 'one-line', 0, 0, 1),
(577, 'ffmpeg', 'FFmpeg', 'one-line', 0, 0, 1),
(578, 'high', 'High', 'one-line', 0, 0, 1),
(579, 'permission_denied', 'Permission Denied', 'one-line', 0, 0, 1),
(580, 'confirm_your_email_address', 'You are required to verify your email address. We have sent an email with a confirmation link to your email address. In order to complete the sign-up process, please click the confirmation link.', 'one-line', 0, 0, 1),
(581, 'group_chats', 'Group Chats', 'one-line', 0, 0, 1),
(582, 'view_group', 'View Group', 'one-line', 0, 0, 1),
(583, 'on_private_conversation_unread_count_change', 'On Private Conversation Unread Count Change', 'one-line', 0, 0, 1),
(584, 'site_role_4', 'Banned', 'one-line', 0, 0, 1),
(585, 'command', 'Command', 'one-line', 0, 0, 1),
(586, 'cloud_storage_api_key', 'Access Key ID', 'one-line', 0, 0, 1),
(587, 'entry_page_form_header', 'Form Header (Entry Page)', 'one-line', 0, 0, 1),
(588, 'sightengine_api_secret', 'Sightengine API Secret', 'one-line', 0, 0, 1),
(589, 'site_role_1', 'Unverified', 'one-line', 0, 0, 1),
(590, 'sitemap', 'Sitemap', 'one-line', 0, 0, 1),
(591, 'group_invitation', 'Invited you to Join the Group', 'one-line', 0, 0, 1),
(592, 'form', 'Form', 'one-line', 0, 0, 1),
(593, 'custom_field_4', 'Website', 'one-line', 1, 0, 1),
(594, 'not_found', 'Not Found', 'one-line', 0, 0, 1),
(595, 'export_chat', 'Export Chat', 'one-line', 0, 0, 1),
(596, 'groups_section_description', 'Groups Section [Description]', 'one-line', 0, 0, 1),
(597, 'infotip_body_tag', 'This will be printed after the &lt;body&gt; opening tag', 'one-line', 0, 0, 1),
(598, 'external_link', 'External Link', 'one-line', 0, 0, 1),
(599, 'compress_video_files', 'Compress Video Files', 'one-line', 0, 0, 1),
(600, 'landing_page_footer_block_one_heading', 'Address', 'one-line', 0, 0, 1),
(601, 'unblock', 'Unblock', 'one-line', 0, 0, 1),
(602, 'landing_page_faq_section', 'FAQ Section (Landing Page)', 'one-line', 0, 0, 1),
(603, 'pending_approval', 'Pending Approval', 'one-line', 0, 0, 1),
(604, 'search', 'Search', 'one-line', 0, 0, 1),
(605, 'resend_email', 'Resend Email', 'one-line', 0, 0, 1),
(606, 'no', 'No', 'one-line', 0, 0, 1),
(607, 'quinary_bg_color', 'Quinary Background Color', 'one-line', 0, 0, 1),
(608, 'link_text', 'Link Text', 'one-line', 0, 0, 1),
(609, 'account_reactivated', 'Account Reactivated. Welcome Back', 'one-line', 0, 0, 1),
(610, 'view_statistics', 'View Statistics', 'one-line', 0, 0, 1),
(611, 'suspend', 'Suspend', 'one-line', 0, 0, 1),
(612, 'cloud_storage_secret_key', 'Secret Key', 'one-line', 0, 0, 1),
(613, 'message_textarea_placeholder', 'Write Here…', 'one-line', 0, 0, 1),
(614, 'group_header', 'Group Header', 'one-line', 0, 0, 1),
(615, 'menu_order', 'Menu Order', 'one-line', 0, 0, 1),
(616, 'group_join_confirmation', 'Group Join Confirmation', 'one-line', 0, 0, 1),
(617, 'cron_jobs', 'Cron Jobs', 'one-line', 0, 0, 1),
(618, 'webpushr', 'Webpushr', 'one-line', 0, 0, 1),
(619, 'groups_section_status', 'Groups Section [Status]', 'one-line', 0, 0, 1),
(620, 'dmy_format', 'Day Month Year', 'one-line', 0, 0, 1),
(621, 'manage_social_login', 'Manage Social Login', 'one-line', 0, 0, 1),
(622, 'view_nearby_users', 'View Nearby Users', 'one-line', 0, 0, 1),
(623, 'on_getting_unbanned_from_group', 'Someone unbanned from Group', 'one-line', 0, 0, 1),
(624, 'site_controls', 'Site Controls', 'one-line', 0, 0, 1),
(625, 'image_removal_criteria', 'Delete Images that contain', 'one-line', 0, 0, 1),
(626, 'statistics', 'Statistics', 'one-line', 0, 0, 1),
(627, 'maximum_login_attempts', 'Maximum Login Attempts', 'one-line', 0, 0, 1),
(628, 'group_chat', 'Group Chat', 'one-line', 0, 0, 1),
(629, 'password_doesnt_match', 'Password doesn&#039;t match the Confirm password', 'one-line', 0, 0, 1),
(630, 'message_settings', 'Message Settings', 'one-line', 0, 0, 1),
(631, 'duodenary_text_color', 'Duodenary Text Color', 'one-line', 0, 0, 1),
(632, 'view_full_name', 'View Full Name', 'one-line', 0, 0, 1),
(633, 'play_notification_sound', 'Play a Notification Sound', 'one-line', 0, 0, 1),
(634, 'clear_realtime_activity_logs', 'Clear Activity Logs', 'one-line', 0, 0, 1),
(635, 'banned_page_expression', 'Ouch!', 'one-line', 0, 0, 1),
(636, 'whats_wrong', 'What&#039;s Wrong with this', 'one-line', 0, 0, 1),
(637, 'required_field', 'Required Field', 'one-line', 0, 0, 1),
(638, 'hero_section_heading', 'Hero Section [Heading]', 'one-line', 0, 0, 1),
(639, 'storage', 'Storage', 'one-line', 0, 0, 1),
(640, 'default_language', 'Default Language', 'one-line', 0, 0, 1),
(641, 'site_notifications', 'Site Notifications', 'one-line', 0, 0, 1),
(642, 'meta_description', 'Meta Description', 'one-line', 0, 0, 1),
(643, 'custom_menu_item_3', 'About', 'one-line', 0, 0, 1),
(644, 'cloud_storage_region', 'Region', 'one-line', 0, 0, 1),
(645, 'records_per_call', 'Site Records per Call', 'one-line', 0, 0, 1),
(646, 'on_removal_from_group', 'Someone removed from Group', 'one-line', 0, 0, 1),
(647, 'compress_image_files', 'Compress Image Files', 'one-line', 0, 0, 1),
(648, 'web_app_manifest', 'Web App Manifest', 'one-line', 0, 0, 1),
(649, 'delete_user_files', 'Delete User Files', 'one-line', 0, 0, 1),
(650, 'chat_window', 'Chat Window', 'one-line', 0, 0, 1),
(651, 'embed', 'Embed', 'one-line', 0, 0, 1),
(652, 'type', 'Type', 'one-line', 0, 0, 1),
(653, 'email_exists', 'Email Already Exists', 'one-line', 0, 0, 1),
(654, 'adverts', 'Adverts', 'one-line', 0, 0, 1),
(655, 'join_group', 'Join Group', 'one-line', 0, 0, 1),
(656, 'access_denied_non_member_message', 'You are not part of this Group. Join Group to View Messages.', 'one-line', 0, 0, 1),
(657, 'banned_from_group', 'Banned from Group', 'one-line', 0, 0, 1),
(658, 'view_site_users', 'View Site Users', 'one-line', 0, 0, 1),
(659, 'group_suspended', 'Sorry, this group has been Suspended.', 'one-line', 0, 0, 1),
(660, 'moderation_settings', 'Moderation', 'one-line', 0, 0, 1),
(661, 'resend_email_on_error', 'Account already verified or account does not exist', 'one-line', 0, 0, 1),
(662, 'social_share_image', 'Default Social Share Image', 'one-line', 0, 0, 1),
(663, 'partial_nudity', 'Partial Nudity', 'one-line', 0, 0, 1),
(664, 'message_non_friends', 'Message Non-Friends', 'one-line', 0, 0, 1),
(665, 'switch_languages', 'Switch Languages', 'one-line', 0, 0, 1),
(666, 'friend_system', 'Friend System', 'one-line', 0, 0, 1),
(667, 'entry_page_form_footer', 'Form Footer (Entry Page)', 'one-line', 0, 0, 1),
(668, 'rtl', 'Right to Left', 'one-line', 0, 0, 1),
(669, 'confirm_password', 'Confirm Password', 'one-line', 0, 0, 1),
(670, 'sort', 'Sort', 'one-line', 0, 0, 1),
(671, 'time_pm', 'PM', 'one-line', 0, 0, 1),
(672, 'light_mode', 'Light Mode', 'one-line', 0, 0, 1),
(673, 'sort_by_default', 'Default', 'one-line', 0, 0, 1),
(674, 'faq_section_heading', 'FAQ Section [Heading]', 'one-line', 0, 0, 1),
(675, 'graphic_violence_gore', 'Graphic Violence &amp; Gore', 'one-line', 0, 0, 1),
(676, 'delete_users', 'Delete Users', 'one-line', 0, 0, 1),
(677, 'groups', 'Groups', 'one-line', 0, 0, 1),
(678, 'login_link_email_button_label', 'Login Now', 'one-line', 0, 0, 1),
(679, 'access_storage', 'Access Storage', 'one-line', 0, 0, 1),
(680, 'cloud_storage_bucket_name', 'Bucket Name', 'one-line', 0, 0, 1),
(681, 'system_info', 'System Info', 'one-line', 0, 0, 1),
(682, 'add_custom_page', 'Add Custom Page', 'one-line', 0, 0, 1),
(683, 'recording', 'Recording', 'one-line', 0, 0, 1),
(684, 'last_visit', 'Last Visit', 'one-line', 0, 0, 1),
(685, 'remember_me', 'Remember Me', 'one-line', 0, 0, 1),
(686, 'custom_menu_item_4', 'Sitemap', 'one-line', 0, 0, 1),
(687, 'primary_bg_color', 'Primary Background Color', 'one-line', 0, 0, 1),
(688, 'time_format', 'Time Format', 'one-line', 0, 0, 1),
(689, 'delete_group_messages', 'Delete Group Messages', 'one-line', 0, 0, 1),
(690, 'unban_from_site', 'Unban from Site', 'one-line', 0, 0, 1),
(691, 'label_text_color', 'Label Text Color', 'one-line', 0, 0, 1),
(692, 'view_private_chats', 'View Private Chats', 'one-line', 0, 0, 1),
(693, 'admins_moderators', 'Admins &amp; Moderators', 'one-line', 0, 0, 1),
(694, 'octonary_text_color', 'Octonary Text Color', 'one-line', 0, 0, 1),
(695, 'something_went_wrong', 'Something Went Wrong', 'one-line', 0, 0, 1),
(696, 'compress_audio_files', 'Compress Audio Files', 'one-line', 0, 0, 1),
(697, 'allow_guest_users_create_accounts', 'Allow Guest Users to Create Accounts', 'one-line', 0, 0, 1),
(698, 'people_nearby_max_distance', 'People Nearby - Maximum Distance (km)', 'one-line', 0, 0, 1),
(699, 'delete_site_users', 'Delete Site Users', 'one-line', 0, 0, 1),
(700, 'disallowed_slugs', 'Disallowed Slugs', 'one-line', 0, 0, 1),
(701, 'open_in_popup', 'Open in Popup', 'one-line', 0, 0, 1),
(702, 'on_group_creation', 'On Group Creation', 'one-line', 0, 0, 1),
(703, 'push_notifications', 'Push Notifications', 'one-line', 0, 0, 1),
(704, 'monitor_group_chats', 'Monitor Group Chats', 'one-line', 0, 0, 1),
(705, 'faq_section_status', 'FAQ Section [Status]', 'one-line', 0, 0, 1),
(706, 'field_type', 'Field Type', 'one-line', 0, 0, 1),
(707, 'entries_per_call', 'Entries per call', 'one-line', 0, 0, 1),
(708, 'email_logo', 'Logo (Email Template)', 'one-line', 0, 0, 1),
(709, 'footer_block_heading', 'Footer Block [Heading]', 'one-line', 0, 0, 1),
(710, 'requires_consent', 'Your consent is required.', 'one-line', 0, 0, 1),
(711, 'unban_users_from_group', 'Unban Users from Group', 'one-line', 0, 0, 1),
(712, 'delete_only_offline_users', 'Delete only Offline Users', 'one-line', 0, 0, 1),
(713, 'links', 'Links', 'one-line', 0, 0, 1),
(714, 'email_successfully_sent', 'Email successfully sent', 'one-line', 0, 0, 1),
(715, 'show_on_info_page', 'Show on Info Page', 'one-line', 0, 0, 1),
(716, 'administrators', 'Administrators', 'one-line', 0, 0, 1),
(717, 'url', 'URL', 'one-line', 0, 0, 1),
(718, 'signup_agreement', 'I agree to the Terms of Service and Privacy Policy', 'one-line', 0, 0, 1),
(719, 'import_users', 'Import Users', 'one-line', 0, 0, 1),
(720, 'landing_page_copyright_notice', '© 2021 Company, Inc. All rights reserved.', 'one-line', 0, 0, 1),
(721, 'medium', 'Medium', 'one-line', 0, 0, 1),
(722, 'hero_section_image', 'Hero Section [Image]', 'one-line', 0, 0, 1),
(723, '12_format', '12-hour clock', 'one-line', 0, 0, 1),
(724, 'emojis', 'Emojis', 'one-line', 0, 0, 1),
(725, 'profile_url', 'Profile URL', 'one-line', 0, 0, 1),
(726, 'maximum_full_name_length', 'Maximum Full Name Length', 'one-line', 0, 0, 1),
(727, 'featured_image', 'Featured Image', 'one-line', 0, 0, 1),
(728, 'group_roles', 'Group Roles', 'one-line', 0, 0, 1),
(729, 'hidden', 'Hidden', 'one-line', 0, 0, 1),
(730, 'unverified_email_address', 'Unverified Email Address', 'one-line', 0, 0, 1),
(731, 'show_on_signup', 'Show on Signup Page', 'one-line', 0, 0, 1),
(732, 'time_am', 'AM', 'one-line', 0, 0, 1),
(733, 'no_conversation_found_subtitle', 'Try changing the filters or search term', 'one-line', 0, 0, 1),
(734, 'meta_title', 'Meta Title', 'one-line', 0, 0, 1),
(735, 'reset', 'Reset', 'one-line', 0, 0, 1),
(736, 'custom_page_3', 'Privacy Policy', 'multi_line', 1, 1, 1),
(737, 'daily_send_limit_private_messages', 'Daily Send Limit [Private Messages]', 'one-line', 0, 0, 1),
(738, 'notifications', 'Notifications', 'one-line', 0, 0, 1),
(739, 'invalid_group_password', 'Invalid Group Password', 'one-line', 0, 0, 1),
(740, 'senary_bg_color', 'Senary Background Color', 'one-line', 0, 0, 1),
(741, 'custom_page_1', 'About', 'multi_line', 1, 1, 1),
(742, 'fake_online_users', 'Fake Online Users', 'one-line', 0, 0, 1),
(743, 'octonary_font_size', 'Octonary Font Size', 'one-line', 0, 0, 1),
(744, 'ip_blacklist', 'IP Blacklist', 'one-line', 0, 0, 1),
(745, 'footer_text', 'Footer Text', 'one-line', 0, 0, 1),
(746, 'force_https', 'Force HTTPS', 'one-line', 0, 0, 1),
(747, 'landing_page_faq_question_10_answer', '', 'one-line', 0, 0, 1),
(748, 'results_found', 'Results Found', 'one-line', 0, 0, 1),
(749, 'google_recaptcha_v2', 'Google reCAPTCHA v2', 'one-line', 0, 0, 1),
(750, 'new_message_notification', 'You have a new message', 'one-line', 0, 0, 1),
(751, 'entry_page_background', 'Entry Page Background', 'one-line', 0, 0, 1),
(752, 'landing_page_faq_question_1_answer', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ', 'one-line', 0, 0, 1),
(753, 'add_users', 'Add Users', 'one-line', 0, 0, 1),
(754, 'add_meta_tags', 'Add Meta Tags', 'one-line', 0, 0, 1),
(755, 'fake_users', 'Fake Users', 'one-line', 0, 0, 1),
(756, 'status', 'Status', 'one-line', 0, 0, 1),
(757, 'dark_mode', 'Dark Mode', 'one-line', 0, 0, 1),
(758, 'add_language', 'Add Language', 'one-line', 0, 0, 1),
(759, 'primary_border_color', 'Primary Border Color', 'one-line', 0, 0, 1),
(760, 'hide_email_address_field_in_registration_page', 'Hide Email Address Field in Registration Page', 'one-line', 0, 0, 1),
(761, 'not_logged_in', 'Not Logged In', 'one-line', 0, 0, 1),
(762, 'site_role_2', 'Web Admin', 'one-line', 0, 0, 1),
(763, 'view_shared_files', 'View Shared Files', 'one-line', 0, 0, 1),
(764, 'rename', 'Rename', 'one-line', 0, 0, 1),
(765, 'email_validator', 'Email Validator', 'one-line', 0, 0, 1),
(766, 'captcha_site_key', 'Captcha Site Key', 'one-line', 0, 0, 1),
(767, 'default', 'Default', 'one-line', 0, 0, 1),
(768, 'api_secret_key', 'API Secret Key', 'one-line', 0, 0, 1),
(769, 'under_review', 'Under Review', 'one-line', 0, 0, 1),
(770, 'export', 'Export', 'one-line', 0, 0, 1),
(771, 'on_new_site_notification', 'On New Site Notification', 'one-line', 0, 0, 1),
(772, 'comments_if_any', 'Comments (If Any)', 'one-line', 0, 0, 1),
(773, 'account_banned', 'Your account has been banned for violating the Terms of Service.', 'one-line', 0, 0, 1),
(774, 'ymd_format', 'Year Month Day', 'one-line', 0, 0, 1),
(775, 'gifs', 'GIFs', 'one-line', 0, 0, 1),
(776, 'approve_users', 'Approve Users', 'one-line', 0, 0, 1),
(777, 'nonary_text_color', 'Nonary Text Color', 'one-line', 0, 0, 1),
(778, 'disapprove', 'Disapprove', 'one-line', 0, 0, 1),
(779, 'no_conversation_found', 'No Conversation Found', 'one-line', 0, 0, 1),
(780, 'explicit_nudity', 'Explicit Nudity', 'one-line', 0, 0, 1),
(781, 'landing_page_footer_block_one_description', '3 Burthong Road, Eremerang, New South Wales, 2877 Australia', 'one-line', 0, 0, 1),
(782, 'landing_page_groups_section_description', 'Where Good conversation become great experiences', 'one-line', 0, 0, 1),
(783, 'nonary_font_size', 'Nonary Font Size', 'one-line', 0, 0, 1),
(784, 'page_content', 'Page Content', 'one-line', 0, 0, 1),
(785, 'send_requests', 'Send Requests', 'one-line', 0, 0, 1),
(786, 'delete_group', 'Delete Group', 'one-line', 0, 0, 1),
(787, 'already_exists', 'Already Exists', 'one-line', 0, 0, 1),
(788, 'switch_user', 'Switch User', 'one-line', 0, 0, 1),
(789, 'requires_minimum_full_name_length', 'Require Minimum Length for Full Name', 'one-line', 0, 0, 1),
(790, 'monitor_private_chats', 'Monitor Private Chats', 'one-line', 0, 0, 1),
(791, 'sticker_packs', 'Sticker Packs', 'one-line', 0, 0, 1),
(792, 'email', 'Email', 'one-line', 0, 0, 1),
(793, 'email_login_link', 'Email Login Link', 'one-line', 0, 0, 1),
(794, 'unfriend', 'Unfriend', 'one-line', 0, 0, 1),
(795, 'custom_css', 'Custom CSS', 'one-line', 0, 0, 1),
(796, 'read_more_criteria', 'Add Read More button if height greater than (px)', 'one-line', 0, 0, 1),
(797, 'primary_text_color', 'Primary Text Color', 'one-line', 0, 0, 1),
(798, 'group_invitation_email_subject', 'You have Got an Invitation', 'one-line', 0, 0, 1),
(799, 'minimum_score_required_offensive', 'Minimum Score Required [Offensive Signs &amp; Gestures] %', 'one-line', 0, 0, 1),
(800, 'secondary_bg_color', 'Secondary Background Color', 'one-line', 0, 0, 1),
(801, 'open_in_new_window', 'Open Link in New Window', 'one-line', 0, 0, 1),
(802, 'select_an_option', 'Select an Option', 'one-line', 0, 0, 1),
(803, 'footer_block_description', 'Footer Block [Description]', 'one-line', 0, 0, 1),
(804, 'deleting', 'Deleting', 'one-line', 0, 0, 1),
(805, 'landing_page_faq_question_2', 'How to Delete an account ?', 'one-line', 0, 0, 1),
(806, 'username', 'Username', 'one-line', 0, 0, 1),
(807, 'denary_text_color', 'Denary Text Color', 'one-line', 0, 0, 1),
(808, 'username_exists', 'Username Already Taken', 'one-line', 0, 0, 1),
(809, 'storage_usage', 'Storage Usage', 'one-line', 0, 0, 1),
(810, 'edit_custom_page', 'Edit Custom Page', 'one-line', 0, 0, 1),
(811, 'top', 'Top', 'one-line', 0, 0, 1),
(812, 'default_timezone', 'Default Timezone', 'one-line', 0, 0, 1),
(813, 'badge', 'Badge', 'one-line', 0, 0, 1),
(814, 'exceeded_maxgroupjoin', 'Exceeded Maximum Number of Groups You can Join', 'one-line', 0, 0, 1),
(815, 'add_friend', 'Add Friend', 'one-line', 0, 0, 1),
(816, 'please_wait', 'Please Wait', 'one-line', 0, 0, 1),
(817, 'home', 'Home', 'one-line', 0, 0, 1),
(818, 'header_content', 'Header Content', 'one-line', 0, 0, 1),
(819, 'septenary_bg_color', 'Septenary Background Color', 'one-line', 0, 0, 1),
(820, 'onesignal_prompt_cancel_button', 'Cancel', 'one-line', 0, 0, 1),
(821, 'show_on_landing_page_footer', 'Show on Landing Page [Footer]', 'one-line', 0, 0, 1),
(822, 'play', 'Play', 'one-line', 0, 0, 1),
(823, 'low', 'Low', 'one-line', 0, 0, 1),
(824, 'app_id', 'APP ID or Client ID', 'one-line', 0, 0, 1),
(825, 'off', 'Off', 'one-line', 0, 0, 1),
(826, 'disabled', 'Disabled', 'one-line', 0, 0, 1),
(827, 'advert_max_height', 'Maximum Height (px)', 'one-line', 0, 0, 1),
(828, 'landing_page_hero_section_description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.', 'one-line', 0, 0, 1),
(829, 'assign_badges', 'Assign Badges', 'one-line', 0, 0, 1),
(830, 'disapprove_user_confirmation', 'Are you sure you want to disapprove this user ?', 'one-line', 0, 0, 1),
(831, 'remove_group_members', 'Remove Group Members', 'one-line', 0, 0, 1),
(832, 'modules', 'Modules', 'one-line', 0, 0, 1),
(833, 'unverified', 'Unverified', 'one-line', 0, 0, 1),
(834, 'minimum_full_name_length', 'Minimum Full Name Length', 'one-line', 0, 0, 1),
(835, 'read_status', 'Read Status', 'one-line', 0, 0, 1),
(836, 'hide_username_field_in_registration_page', 'Hide Username Field In Registration Page', 'one-line', 0, 0, 1),
(837, 'email_username', 'Email/Username', 'one-line', 0, 0, 1),
(838, 'ban_users_from_group', 'Ban Users from Group', 'one-line', 0, 0, 1),
(839, 'global_css', 'Global CSS', 'one-line', 0, 0, 1),
(840, 'sent', 'Sent', 'one-line', 0, 0, 1),
(841, 'login_link_email_content', 'A warm welcome to our website. You&#039;re now part of our website. We&#039;re excited to have you on board. Meet new friends. Share your experiences.', 'one-line', 0, 0, 1),
(842, 'webpushr_public_key', 'Webpushr Public Key', 'one-line', 0, 0, 1),
(843, 'captcha', 'Captcha', 'one-line', 0, 0, 1),
(844, 'nonary_bg_color', 'Nonary Background Color', 'one-line', 0, 0, 1),
(845, 'faq', 'FAQ', 'one-line', 0, 0, 1),
(846, 'on_new_message', 'On New Message', 'one-line', 0, 0, 1),
(847, 'banned_page_button', 'Contact Support', 'one-line', 0, 0, 1),
(848, 'edit_custom_field_value', 'Edit Custom Field Value', 'one-line', 0, 0, 1),
(849, 'suspended', 'Suspended', 'one-line', 0, 0, 1),
(850, 'tertiary_bg_color', 'Tertiary Background Color', 'one-line', 0, 0, 1),
(851, 'default_group_visibility', 'Default Group Visibility', 'one-line', 0, 0, 1),
(852, 'groups_section_heading', 'Groups Section [Heading]', 'one-line', 0, 0, 1),
(853, 'seen_by', 'Seen By', 'one-line', 0, 0, 1),
(854, 'copyright_notice', 'Copyright Notice', 'one-line', 0, 0, 1),
(855, 'landing_page_faq_question_1', 'How to create an account ?', 'one-line', 0, 0, 1),
(856, 'landing_page_footer_text', 'All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.', 'one-line', 0, 0, 1),
(857, 'custom_group_role', 'Custom Group Role', 'one-line', 0, 0, 1),
(858, 'banned_page_title', 'You Are Banned', 'one-line', 0, 0, 1),
(859, 'dashboard', 'Dashboard', 'one-line', 0, 0, 1),
(860, 'complaint_status', 'Complaint Status', 'one-line', 0, 0, 1),
(861, 'rename_audio_file', 'Rename Audio File', 'one-line', 0, 0, 1),
(862, 'uploading_files', 'Uploading File(s)', 'one-line', 0, 0, 1),
(863, 'receive_requests', 'Receive Requests', 'one-line', 0, 0, 1),
(864, 'add_fake_users', 'Add Fake Users', 'one-line', 0, 0, 1),
(865, 'maximum_message_length', 'Maximum Message Length', 'one-line', 0, 0, 1),
(866, 'landing_page_faq_question_4', '', 'one-line', 0, 0, 1),
(867, 'conversation_with', 'Conversation With', 'one-line', 0, 0, 1),
(868, 'nonary_border_color', 'Nonary Border Color', 'one-line', 0, 0, 1),
(869, 'right', 'Right', 'one-line', 0, 0, 1),
(870, 'hcaptcha', 'hCaptcha', 'one-line', 0, 0, 1),
(871, 'create', 'Create', 'one-line', 0, 0, 1),
(872, 'view_public_group_messages_non_member', 'View Public Group Messages without being Group Member [Logged in Users]', 'one-line', 0, 0, 1),
(873, 'edit_language', 'Edit Language', 'one-line', 0, 0, 1),
(874, 'show_on_front_page', 'Show on Frontpage', 'one-line', 0, 0, 1),
(875, 'editable_only_once', 'Editable Only Once', 'one-line', 0, 0, 1),
(876, 'view_secret_groups', 'View Secret Groups', 'one-line', 0, 0, 1),
(877, 'nearby_users', 'Nearby Users', 'one-line', 0, 0, 1),
(878, 'delete_complaints', 'Delete Complaints', 'one-line', 0, 0, 1),
(879, 'mdy_format', 'Month Day Year', 'one-line', 0, 0, 1),
(880, 'custom_js', 'Custom JS', 'one-line', 0, 0, 1),
(881, 'accept_friend', 'Accept Friend', 'one-line', 0, 0, 1),
(882, 'approve_user_confirmation', 'Are you sure you want to approve this user ?', 'one-line', 0, 0, 1),
(883, 'minimum_score_required_partial_nudity', 'Minimum Score Required [Partial Nudity] %', 'one-line', 0, 0, 1),
(884, 'landing_page_groups_section', 'Groups Section (Landing Page)', 'one-line', 0, 0, 1),
(885, 'offline', 'Offline', 'one-line', 0, 0, 1),
(886, 'delete_message_time_limit', 'Time Limit to Delete their own Messages (Minutes)', 'one-line', 0, 0, 1),
(887, 'no_results_found_subtitle', 'Try changing the filters or search term', 'one-line', 0, 0, 1),
(888, 'email_domain_not_allowed', 'Not allowed to use an email address from this domain ', 'one-line', 0, 0, 1),
(889, 'new_badge_awarded', 'New Badge Awarded', 'one-line', 0, 0, 1),
(890, 'assigned', 'Assigned', 'one-line', 0, 0, 1),
(891, 'denary_font_size', 'Denary Font Size', 'one-line', 0, 0, 1),
(892, 'icon_class', 'Icon Class', 'one-line', 0, 0, 1),
(893, 'landing_page_faq_section_heading', 'Frequently Asked Questions', 'one-line', 0, 0, 1),
(894, 'mini_audio_player', 'Mini Audio Player', 'one-line', 0, 0, 1),
(895, 'landing_page', 'Landing Page', 'one-line', 0, 0, 1),
(896, 'login_link_email_subject', 'Your Login Link', 'one-line', 0, 0, 1),
(897, 'view_complaint', 'View Complaint', 'one-line', 0, 0, 1),
(898, 'slideshows', 'Slideshows', 'one-line', 0, 0, 1),
(899, 'embed_group', 'Embed Group', 'one-line', 0, 0, 1),
(900, 'hide_name_field_in_registration_page', 'Hide Name Field in Registration Page', 'one-line', 0, 0, 1),
(901, 'unban_users_from_site', 'Unban Users from Site', 'one-line', 0, 0, 1),
(902, 'approve', 'Approve', 'one-line', 0, 0, 1),
(903, 'forms', 'Forms', 'one-line', 0, 0, 1),
(904, 'manage_custom_fields', 'Manage Custom Fields', 'one-line', 0, 0, 1),
(905, 'comments_by_complainant', 'Comments by Complainant', 'one-line', 0, 0, 1),
(906, 'set_default_language', 'Set as Default Language', 'one-line', 0, 0, 1),
(907, 'data_unavailable', 'Data Unavailable', 'one-line', 0, 0, 1),
(908, 'send_as_another_user', 'Send Messages as Another Site User', 'one-line', 0, 0, 1),
(909, 'refresh_rate', 'Chat Refresh Rate (Milliseconds)', 'one-line', 0, 0, 1),
(910, 'site_adverts', 'Site Adverts', 'one-line', 0, 0, 1),
(911, 'javascript', 'JavaScript', 'one-line', 0, 0, 1),
(912, 'sample_reference_file', 'Sample Reference File', 'one-line', 0, 0, 1),
(913, 'audio_message', 'Audio Message', 'one-line', 0, 0, 1),
(914, 'left_content_block', 'Left Content Block', 'one-line', 0, 0, 1),
(915, 'reply_to', 'Reply To', 'one-line', 0, 0, 1),
(916, 'advert_content', 'Advert Content', 'one-line', 0, 0, 1),
(917, 'join', 'Join', 'one-line', 0, 0, 1),
(918, 'duration', 'Duration', 'one-line', 0, 0, 1),
(919, 'edit_advert', 'Edit Advert', 'one-line', 0, 0, 1),
(920, 'message_scheduler', 'Message Scheduler', 'one-line', 0, 0, 1),
(921, 'edit_site_role', 'Edit Site Role', 'one-line', 0, 0, 1),
(922, 'edit_message', 'Edit Message', 'one-line', 0, 0, 1),
(923, 'lifetime', 'Lifetime', 'one-line', 0, 0, 1),
(924, 'secondary_text_color', 'Secondary Text Color', 'one-line', 0, 0, 1),
(925, 'order_id', 'Order ID', 'one-line', 0, 0, 1),
(926, 'add_images', 'Add Images', 'one-line', 0, 0, 1),
(927, 'started_on', 'Started On', 'one-line', 0, 0, 1),
(928, 'supported_file_formats', 'Supported File Formats', 'one-line', 0, 0, 1),
(929, 'set_participant_settings', 'Set Participant Settings', 'one-line', 0, 0, 1),
(930, 'expiring_on', 'Expiring On', 'one-line', 0, 0, 1),
(931, 'expired_on', 'Expired On', 'one-line', 0, 0, 1),
(932, 'main_panel_content_on_page_load', 'Content to Show on Page Load [Main Panel]', 'one-line', 0, 0, 1),
(933, 'manage_audio_player', 'Manage Audio Player', 'one-line', 0, 0, 1),
(934, 'payment_methods', 'Payment Methods', 'one-line', 0, 0, 1),
(935, 'denary_bg_color', 'Denary Background Color', 'one-line', 0, 0, 1),
(936, 'add_payment_method', 'Add Payment Method', 'one-line', 0, 0, 1),
(937, 'pending', 'Pending', 'one-line', 0, 0, 1),
(938, 'scheduled_message', 'Scheduled Message', 'one-line', 0, 0, 1),
(939, 'manage_payment_gateways', 'Manage Payment Gateways', 'one-line', 0, 0, 1),
(940, 'edit_own_message', 'Edit Own Message', 'one-line', 0, 0, 1),
(941, 'payment_method', 'Payment Method', 'one-line', 0, 0, 1),
(942, 'schedule_message', 'Schedule Message', 'one-line', 0, 0, 1),
(943, 'enroll_membership', 'Enroll Membership', 'one-line', 0, 0, 1),
(944, 'edit_all_messages', 'Edit All Messages', 'one-line', 0, 0, 1),
(945, 'view_personal_transactions', 'View Personal Transactions', 'one-line', 0, 0, 1),
(946, 'who_all_can_view_page', 'Who all can View Page', 'one-line', 0, 0, 1),
(947, 'send_message_on', 'Send Message On', 'one-line', 0, 0, 1),
(948, 'site_role_3', 'Registered', 'one-line', 0, 0, 1),
(949, 'view_site_transactions', 'View Site Transactions', 'one-line', 0, 0, 1),
(950, 'bank_transfer_choose_file', 'Choose a file (PDF, JPG, or PNG):', 'one-line', 0, 0, 1),
(951, 'unknown', 'Unknown', 'one-line', 0, 0, 1),
(952, 'schedule_cronjob_command_message_skip', 'Make sure to schedule the following cronjob command in your hosting server to run the script automatically at your preferred intervals, and if the cronjob is already scheduled, skip this step', 'one-line', 0, 0, 1),
(953, 'ip_intelligence_probability', 'Probability', 'one-line', 0, 0, 1),
(954, 'download_invoice', 'Download Invoice', 'one-line', 0, 0, 1),
(955, 'file_upload_failed', 'File upload failed', 'one-line', 0, 0, 1),
(956, 'mention_users', 'Mention Users', 'one-line', 0, 0, 1),
(957, 'view_membership_info', 'View Membership Info', 'one-line', 0, 0, 1),
(958, 'vc_agora_app_certificate', 'Agora App Certificate', 'one-line', 0, 0, 1),
(959, 'edit_message_time_limit', 'Time Limit to Edit their own Messages (Minutes)', 'one-line', 0, 0, 1),
(960, 'bank_transfer_receipts', 'Bank Transfer Receipts', 'one-line', 0, 0, 1),
(961, 'group_invitation_email_button_label', 'Join Group', 'one-line', 0, 0, 1),
(962, 'successful', 'Successful', 'one-line', 0, 0, 1),
(963, 'ip_intel_validate_on_login', 'Validate on User Login', 'one-line', 0, 0, 1),
(964, 'csv_file', 'CSV File', 'one-line', 0, 0, 1),
(965, 'view_profile_url', 'View Profile URL', 'one-line', 0, 0, 1),
(966, 'create_video_chat_groups', 'Create Groups with Video Chat', 'one-line', 0, 0, 1),
(967, 'failed', 'Failed', 'one-line', 0, 0, 1),
(968, 'ip_intel_validate_on_register', 'Validate on User Signup', 'one-line', 0, 0, 1),
(969, 'time_limit_expired', 'Time Limit has Expired', 'one-line', 0, 0, 1),
(970, 'maximum_guest_nickname_length', 'Maximum Guest Nickname Length', 'one-line', 0, 0, 1),
(971, 'is_calling_text', 'is calling you', 'one-line', 0, 0, 1),
(972, 'invoice', 'Invoice', 'one-line', 0, 0, 1),
(973, 'minimum_guest_nickname_length', 'Minimum Guest Nickname Length', 'one-line', 0, 0, 1),
(974, 'smtp_self_signed_certificate', 'Self-signed Certificate', 'one-line', 0, 0, 1),
(975, 'reject', 'Reject', 'one-line', 0, 0, 1),
(976, 'edit_order', 'Edit Order', 'one-line', 0, 0, 1),
(977, 'requires_minimum_guest_nickname_length', 'Requires Minimum Length for Guest Nickname', 'one-line', 0, 0, 1),
(978, 'gif_search_engine_api', 'GIF Search Engine API', 'one-line', 0, 0, 1),
(979, 'user_busy_message', 'The user is on another call.', 'one-line', 0, 0, 1),
(980, 'bank_receipts', 'Bank Receipts', 'one-line', 0, 0, 1),
(981, 'read_terms', 'Read Terms', 'one-line', 0, 0, 1),
(982, 'user_initiated_video_call', 'The user has initiated a video call', 'one-line', 0, 0, 1),
(983, 'invoice_from', 'Invoice From', 'one-line', 0, 0, 1),
(984, 'accepted', 'Accepted', 'one-line', 0, 0, 1),
(985, 'sending_limit_reached', 'Sending Limit Reached. Please wait : ', 'one-line', 0, 0, 1),
(986, 'group_role_3', 'Group Moderator', 'one-line', 0, 0, 1),
(987, 'exceeds_guest_nickname_length', 'Guest Nickname character length limit exceeded', 'one-line', 0, 0, 1),
(988, 'septenary_border_color', 'Septenary Border Color', 'one-line', 0, 0, 1),
(989, 'repeat_message', 'Repeat Message', 'one-line', 0, 0, 1),
(990, 'on_new_private_video_call', 'Someone initiates a private video call with the user', 'one-line', 0, 0, 1),
(991, 'global_js', 'Global Custom JS', 'one-line', 0, 0, 1),
(992, 'attachments', 'Attachment(s)', 'one-line', 0, 0, 1),
(993, 'fullscreen', 'Fullscreen', 'one-line', 0, 0, 1),
(994, 'uploaded_on', 'Uploaded On', 'one-line', 0, 0, 1),
(995, 'slideshow', 'Slideshow', 'one-line', 0, 0, 1),
(996, 'group_role_2', 'Group Admin', 'one-line', 0, 0, 1),
(997, 'repeat_interval', 'Repeat Interval (Minutes)', 'one-line', 0, 0, 1),
(998, 'no_group_selected', 'Empty Inbox', 'one-line', 0, 0, 1),
(999, 'ad_block_detected_title', 'AdBlock Detected!', 'one-line', 0, 0, 1),
(1000, 'invoice_to', 'Invoice To', 'one-line', 0, 0, 1),
(1001, 'others', 'Others', 'one-line', 0, 0, 1),
(1002, 'bank_receipt', 'Bank Receipt', 'one-line', 0, 0, 1),
(1003, 'appkey', 'APP Key', 'one-line', 0, 0, 1),
(1004, 'welcome_screen_message', 'Share what&#039;s on your mind with other people from all around the world to find new friends', 'one-line', 0, 0, 1),
(1005, 'go_offline', 'Go Offline', 'one-line', 0, 0, 1),
(1006, 'enter_is_send', 'Send message with Enter key', 'one-line', 0, 0, 1),
(1007, 'invoice_id', 'Invoice ID', 'one-line', 0, 0, 1),
(1008, 'chat_page', 'Chat Page', 'one-line', 0, 0, 1),
(1009, 'invite_link', 'Invite Link', 'one-line', 0, 0, 1),
(1010, 'quaternary_text_color', 'Quaternary Text Color', 'one-line', 0, 0, 1),
(1011, 'add_cron_job', 'Add Cron Job', 'one-line', 0, 0, 1),
(1012, 'repetition_rate', 'Repetition rate', 'one-line', 0, 0, 1),
(1013, 'not_found_page_title', '404 - Page not found', 'one-line', 0, 0, 1),
(1014, 'billing_address_not_found', 'Billing address not found. Please update your billing information.', 'one-line', 0, 0, 1),
(1015, 'group_role_1', 'Banned', 'one-line', 0, 0, 1),
(1016, 'site_users', 'Site Users', 'one-line', 0, 0, 1),
(1017, 'reject_request', 'Reject Request', 'one-line', 0, 0, 1),
(1018, 'cloud_storage_public_url', 'Public URL', 'one-line', 0, 0, 1),
(1019, 'non_member', 'Non Member', 'one-line', 0, 0, 1),
(1020, 'web_address', 'Web Address', 'one-line', 0, 0, 1),
(1021, 'seconds', 'Seconds', 'one-line', 0, 0, 1),
(1022, 'change_to_offline_status_after', 'Change to Offline Status After (Minutes)', 'one-line', 0, 0, 1),
(1023, 'role_hierarchy', 'Role Hierarchy', 'one-line', 0, 0, 1),
(1024, 'custom_menu', 'Custom Menu', 'one-line', 0, 0, 1),
(1025, 'login_as_another_user', 'Login as Another User', 'one-line', 0, 0, 1),
(1026, 'billing_info', 'Billing Info', 'one-line', 0, 0, 1),
(1027, 'cloud_storage_endpoint', 'Endpoint', 'one-line', 0, 0, 1),
(1028, 'languages', 'Languages', 'one-line', 0, 0, 1),
(1029, 'memberships', 'Memberships', 'one-line', 0, 0, 1),
(1030, 'custom_avatar', 'Custom Avatar', 'one-line', 0, 0, 1),
(1031, 'custom_pages', 'Custom Pages', 'one-line', 0, 0, 1),
(1032, '24_format', '24-hour clock', 'one-line', 0, 0, 1),
(1033, 'view_receipt', 'View Receipt', 'one-line', 0, 0, 1),
(1034, 'hide_phone_number_field_in_registration_page', 'Hide Phone Number Field in Registration Page', 'one-line', 0, 0, 1),
(1035, 'messages', 'Messages', 'one-line', 0, 0, 1),
(1036, 'live_kit', 'LiveKit', 'one-line', 0, 0, 1),
(1037, 'billed_to', 'Billed To', 'one-line', 0, 0, 1),
(1038, 'load_profile_on_page_load', 'Load Profile on Page Load', 'one-line', 0, 0, 1),
(1039, 'ad_block_detected_description', 'Our website is made possible by displaying online advertisements to our visitors.                    Please consider supporting us by disabling your ad blocker on our website.', 'one-line', 0, 0, 1),
(1040, 'ban_from_site', 'Ban from Site', 'one-line', 0, 0, 1),
(1041, 'custom_menu_item_2', 'Privacy Policy', 'one-line', 0, 0, 1),
(1042, 'street_address', 'Street Address', 'one-line', 0, 0, 1),
(1043, 'live_kit_api_key', 'LiveKit API Key', 'one-line', 0, 0, 1),
(1044, 'landing_page_faq_question_10', '', 'one-line', 0, 0, 1),
(1045, 'show_profile_on_pm_open', 'Show Profile upon opening Private Messages', 'one-line', 0, 0, 1),
(1046, 'slug_condition', 'Slug must contain at least one letter', 'one-line', 0, 0, 1),
(1047, 'city', 'City', 'one-line', 0, 0, 1),
(1048, 'show_side_navigation_on_load', 'Show Side Navigation On Load', 'one-line', 0, 0, 1),
(1049, 'order', 'Order', 'one-line', 0, 0, 1),
(1050, 'add_package', 'Add Package', 'one-line', 0, 0, 1),
(1051, 'phone_number_verification', 'Phone Number Verification', 'one-line', 0, 0, 1),
(1052, 'friends', 'Friends', 'one-line', 0, 0, 1),
(1053, 'own_message_alignment', 'Message Alignment [Own]', 'one-line', 0, 0, 1),
(1054, 'live_kit_url', 'LiveKit URL', 'one-line', 0, 0, 1),
(1055, 'state', 'State', 'one-line', 0, 0, 1),
(1056, 'on_getting_banned_from_group', 'Someone banned from Group', 'one-line', 0, 0, 1),
(1057, 'recently_joined', 'Recently Joined', 'one-line', 0, 0, 1),
(1058, 'ad_block_detected_button', 'I&#039;ve disabled AdBlock', 'one-line', 0, 0, 1),
(1059, 'country', 'Country', 'one-line', 0, 0, 1),
(1060, 'private_chats', 'Private Chats', 'one-line', 0, 0, 1),
(1061, 'live_kit_secret_key', 'LiveKit Secret Key', 'one-line', 0, 0, 1),
(1062, 'hide_language', 'Hide Language', 'one-line', 0, 0, 1),
(1063, 'postal_code', 'Postal Code', 'one-line', 0, 0, 1),
(1064, 'cloud_storage', 'Cloud Storage', 'one-line', 0, 0, 1),
(1065, 'package_name', 'Package Name', 'one-line', 0, 0, 1),
(1066, 'on_getting_temporarily_banned_from_group', 'Someone temporarily banned from Group', 'one-line', 0, 0, 1),
(1067, 'company_address', 'Company Address ', 'one-line', 0, 0, 1),
(1068, 'create_group_role', 'Create Group Role', 'one-line', 0, 0, 1),
(1069, 'firebase', 'Firebase', 'one-line', 0, 0, 1),
(1070, 'landing_page_faq_question_5_answer', '', 'one-line', 0, 0, 1),
(1071, 'invoice_footer', 'Invoice Footer Note', 'one-line', 0, 0, 1),
(1072, 'autoplay_audio_player', 'Autoplay Audio Player', 'one-line', 0, 0, 1),
(1073, 'adblock_detector', 'Adblock Detector', 'one-line', 0, 0, 1),
(1074, 'default_currency', 'Default Currency', 'one-line', 0, 0, 1),
(1075, 'joined_group', 'Joined the Group Chat', 'one-line', 0, 0, 1),
(1076, 'fb_messagingsenderid', 'Messaging Sender ID', 'one-line', 0, 0, 1),
(1077, 'default_currency_symbol', 'Default Currency Symbol', 'one-line', 0, 0, 1),
(1078, 'login_cookie_validity', 'Login Cookie Validity (Days)', 'one-line', 0, 0, 1),
(1079, 'pricing', 'Pricing', 'one-line', 0, 0, 1),
(1080, 'edit_audio', 'Edit Audio', 'one-line', 0, 0, 1),
(1081, 'date_text', 'Date', 'one-line', 0, 0, 1),
(1082, 'gif', 'GIF', 'one-line', 0, 0, 1),
(1083, 'custom_fields', 'Custom Fields', 'one-line', 0, 0, 1),
(1084, 'external_page', 'External Page', 'one-line', 0, 0, 1),
(1085, 'edit_cron_job', 'Edit Cron Job', 'one-line', 0, 0, 1),
(1086, 'invoice_total', 'Invoice Total', 'one-line', 0, 0, 1),
(1087, 'cloud_storage_ftp_host', 'FTP Hostname', 'one-line', 0, 0, 1),
(1088, 'flood_control_error_message', 'You are submitting too quickly. Please wait', 'one-line', 0, 0, 1),
(1089, 'continue_text', 'Continue', 'one-line', 0, 0, 1),
(1090, 'manage_adverts', 'Manage Adverts', 'one-line', 0, 0, 1),
(1091, 'error', 'Error', 'one-line', 0, 0, 1),
(1092, 'edit_site_transactions', 'Edit Site Transactions', 'one-line', 0, 0, 1),
(1093, 'report_group', 'Report Group', 'one-line', 0, 0, 1),
(1094, 'amazon_s3', 'Amazon S3', 'one-line', 0, 0, 1),
(1095, 'view_order', 'View Order', 'one-line', 0, 0, 1),
(1096, 'banned_from_group_message', 'You are Banned from accessing the Group', 'one-line', 0, 0, 1),
(1097, 'landing_page_faq_question_5', '', 'one-line', 0, 0, 1),
(1098, 'membership_package_id', 'Membership Package ID', 'one-line', 0, 0, 1),
(1099, 'mail_footer_text', 'If you have any questions or concerns, \\n please feel free to email us at', 'one-line', 0, 0, 1),
(1100, 'users_banned', 'Users Banned', 'one-line', 0, 0, 1),
(1101, 'placed_by', 'Placed By', 'one-line', 0, 0, 1),
(1102, 'invalid_captcha', 'Invalid Captcha', 'one-line', 0, 0, 1),
(1103, 's3_compatible', 'S3 Compatible Storage', 'one-line', 0, 0, 1),
(1104, 'validate', 'Validate', 'one-line', 0, 0, 1),
(1105, 'typing_indicator', 'Typing Indicator', 'one-line', 0, 0, 1),
(1106, 'all_group_members', 'All Group Members', 'one-line', 0, 0, 1),
(1107, 'delete_all_messages', 'Delete All Messages', 'one-line', 0, 0, 1),
(1108, 'take_action', 'Take Action', 'one-line', 0, 0, 1),
(1109, 'fb_apikey', 'API Key', 'one-line', 0, 0, 1),
(1110, 'onesignal_prompt_message', 'We would like to send you Push Notifications for the latest Updates.', 'one-line', 0, 0, 1),
(1111, 'approve_enroll', 'Approve &amp; Enroll', 'one-line', 0, 0, 1),
(1112, 'billing_interval', 'Billing Interval', 'one-line', 0, 0, 1),
(1113, 'done', 'Done', 'one-line', 0, 0, 1),
(1114, 'disapprove_unenroll', 'Disapprove &amp; Unenroll', 'one-line', 0, 0, 1),
(1115, 'fb_authdomain', 'Auth Domain', 'one-line', 0, 0, 1),
(1116, 'removed_from_group', 'Got removed from Group', 'one-line', 0, 0, 1),
(1117, 'reassign_site_role', 'Reassign Site Role', 'one-line', 0, 0, 1),
(1118, 'one_time', 'One Time', 'one-line', 0, 0, 1),
(1119, 'site_transactions', 'Site Transactions', 'one-line', 0, 0, 1),
(1120, 'invalid_value', 'Invalid Input or Field Empty', 'one-line', 0, 0, 1),
(1121, 'fb_projectid', 'Project ID', 'one-line', 0, 0, 1),
(1122, 'success', 'Success', 'one-line', 0, 0, 1),
(1123, 'cloud_storage_ftp_username', 'FTP Username', 'one-line', 0, 0, 1),
(1124, 'streaming_server', 'Streaming Server', 'one-line', 0, 0, 1),
(1125, 'custom_field_3_options', '{\"male\":\"Male\",\"female\":\"Female\",\"non_binary\":\"Non-binary\"}', 'multi_line', 1, 0, 1),
(1126, 'monthly', 'Monthly', 'one-line', 0, 0, 1),
(1127, 'custom_page_2_content', '<div>1. Terms</div><div>By accessing this website, you are agreeing to be bound by the Terms and Conditions of Use, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this website. The content contained here are protected by applicable copyright and trade mark laws. Please take the time to review our privacy policy.</div><div><br /></div><div>2. Use License</div><div>Permission is granted for the temporary use of the group chat, filemanager on web site for personal, non-commercial use only. This is the grant of the services, not a transfer of title, and under this license you may not: modify or copy the materials; use the materials for any commercial purpose, or for any public display (commercial or non-commercial); attempt to decompile or reverse engineer any software contained on the website; remove any copyright or other proprietary notations from the materials; or transfer the materials to another person or \"mirror\" the materials on any other website or server. This license shall automatically terminate if you violate any of these restrictions and may be terminated by the website at any time.</div><div><br /></div><div>3. Disclaimer</div><div>The content on the website are provided \"as is\". We makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties, including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights. Furthermore, We does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the content on its website or otherwise relating to such content or on any sites linked to this site.</div><div><br /></div><div>4. Limitations</div><div>In no event shall we be liable for any damages (including, without limitation, damages for loss of data or profit, due to business interruption, or criminal charges filed against you) arising out of the use or inability to use the content on the website, even if we or a authorized representative has been notified orally or in writing of the possibility of such damage. This applies to the use of our chat rooms and filemanager. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.</div><div><br /></div><div>5. Revisions and Errata</div><div>The materials appearing on the website could include technical, typos, or image errors. We does not warrant that any of the content on its website are accurate, complete, or current. We may make changes to the content on its website at any time without any noticeWe does not, however, make any commitment to update the content.</div><div><br /></div><div>6. Links</div><div>We has not reviewed all of the sites linked from its website and is not responsible for the contents contained within. The inclusion of any link does not imply endorsement by us. Use of any such linked web site is at the user\'s own risk.</div><div><br /></div><div>7. Age Limitations</div><div>In general, the age minimum for this webs site is 13. This website will not be held responsible for users who do not comply with the given age range as this information is not verifiable.</div><div><br /></div><div>8. Hateful Content</div><div>We does not tolerate any form of hateful or violent content in our chat rooms or on our forums. This includes threats, promotion of violence or direct attacks towards other users based upon ethnicity, race, religion, sexual orientation, religion affiliation, age, disability, serious diseases and gender. Users also are prohibited from using hateful images for their profile pictures/avatars. This includes usernames. All such comment will be removed when noticed or reported to our staff.</div><div><br /></div><div>9. Illegal Content</div><div>We does not tolerate any form of illegal content in our chat rooms or on our forums. Users also are prohibited from using or uploading illegal images including child pornography or other illegal content. This includes, but not limited to, profile pictures/avatars and any image transfers or uploads. This includes usernames. If you do so, you will be subject to being kicked, banned and, in some cases, reported to law enforcement. We will, to its highest ability, remove all illegal content when it is discovered or reported to us. We will not be held responsible for such content unless it is noticed and reported to our staff.</div><div><br /></div><div>10. Terms of Use Changes</div><div>We may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use. If you cannot agree to this, please do not use this website.</div>', 'multi_line', 1, 1, 1),
(1128, 'pin_groups', 'Pin Groups', 'one-line', 0, 0, 1),
(1129, 'shoutcast', 'SHOUTcast', 'one-line', 0, 0, 1),
(1130, 'landing_page_faq_question_6', '', 'one-line', 0, 0, 1),
(1131, 'maximum_storage_space', 'Maximum Storage Space (MB)', 'one-line', 0, 0, 1),
(1132, 'version', 'Version', 'one-line', 0, 0, 1),
(1133, 'yearly', 'Yearly', 'one-line', 0, 0, 1),
(1134, 'site_role_5', 'Guest', 'one-line', 0, 0, 1),
(1135, 'ignore_users', 'Ignore Users', 'one-line', 0, 0, 1),
(1136, 'icecast', 'Icecast', 'one-line', 0, 0, 1),
(1137, 'system_email_address', 'System Email Address', 'one-line', 0, 0, 1),
(1138, 'question', 'Question', 'one-line', 0, 0, 1),
(1139, 'custom', 'Custom', 'one-line', 0, 0, 1),
(1140, 'edit_group_role', 'Edit Group Role', 'one-line', 0, 0, 1),
(1141, 'on_awarding_group_badges', 'Group awarded with new badge', 'one-line', 0, 0, 1),
(1142, 'mention_everyone', 'Mention @everyone', 'one-line', 0, 0, 1),
(1143, 'transaction_successful_message', 'Your transaction was successful.', 'one-line', 0, 0, 1),
(1144, 'cloud_storage_ftp_password', 'FTP Password', 'one-line', 0, 0, 1),
(1145, 'no_of_days', 'How Many Days', 'one-line', 0, 0, 1),
(1146, 'transaction_failed_message', 'Your transaction has failed.', 'one-line', 0, 0, 1),
(1147, 'cookie_consent_modal_content', 'We may use cookies, web beacons, tracking pixels, and other tracking technologies when you visit our website, including any other media form, media channel, mobile website, or mobile application related or connected thereto (collectively, the &quot;Site&quot;) to help customize the Site and improve your experience. We reserve the right to make changes to this Cookie Policy at any time and for any reason. Any changes or modifications will be effective immediately upon posting the updated Cookie Policy on the Site, and you waive the right to receive specific notice of each such change or modification. You are encouraged to periodically review this Cookie Policy to stay informed of updates. You will be deemed to have been made aware of, will be subject to, and will be deemed to have accepted the changes in any revised Cookie Policy by your continued use of the Site after the date such revised Cookie Policy is posted.', 'one-line', 0, 0, 1),
(1148, 'footer_section_status', 'Footer Section [Status]', 'one-line', 0, 0, 1),
(1149, 'license', 'License', 'one-line', 0, 0, 1),
(1150, 'related_site_role', 'Related Site Role', 'one-line', 0, 0, 1),
(1151, 'forward_message', 'Forward Message', 'one-line', 0, 0, 1),
(1152, 'total_orders', 'Total Orders', 'one-line', 0, 0, 1),
(1153, 'fb_appid', 'App ID', 'one-line', 0, 0, 1),
(1154, 'landing_page_faq_question_7', '', 'one-line', 0, 0, 1),
(1155, 'cloud_storage_ftp_port', 'FTP Port', 'one-line', 0, 0, 1),
(1156, 'successful_orders', 'Successful Orders', 'one-line', 0, 0, 1),
(1157, 'search_users', 'Search Users', 'one-line', 0, 0, 1),
(1158, 'confirm_leave', ' Are you sure you want to leave this group?', 'one-line', 0, 0, 1),
(1159, 'custom_field_2', 'Birth Date', 'one-line', 1, 0, 1),
(1160, 'this_month', 'This Month', 'one-line', 0, 0, 1),
(1161, 'firebase_private_key', 'Firebase Private Key', 'one-line', 0, 0, 1),
(1162, 'facebook_url', 'Facebook URL', 'one-line', 0, 0, 1),
(1163, 'headers_footers', 'Headers &amp; Footers', 'one-line', 0, 0, 1),
(1164, 'last_run_time', 'Last Run Time', 'one-line', 0, 0, 1),
(1165, 'site_role_id_on_expire', 'Site role upon expiration', 'one-line', 0, 0, 1),
(1166, 'error_message', 'Something went wrong, try refreshing.', 'one-line', 0, 0, 1),
(1167, 'custom_field_3', 'Gender', 'one-line', 1, 0, 1),
(1168, 'last_month', 'Last Month', 'one-line', 0, 0, 1),
(1169, 'role_attributes', 'Role Attributes', 'one-line', 0, 0, 1),
(1170, 'web_push_sent_reply_message', 'Replied to Your Message', 'one-line', 0, 0, 1),
(1171, 'sms_gateway', 'SMS Gateway', 'one-line', 0, 0, 1),
(1172, 'searchable', 'Searchable', 'one-line', 0, 0, 1),
(1173, 'this_year', 'This Year', 'one-line', 0, 0, 1),
(1174, 'membership_packages', 'Membership Packages', 'one-line', 0, 0, 1),
(1175, 'twilio', 'Twilio', 'one-line', 0, 0, 1),
(1176, 'name_censored_word_detected', 'Name contains a censored word', 'one-line', 0, 0, 1),
(1177, 'recent_transactions', 'Recent Transactions', 'one-line', 0, 0, 1),
(1178, 'benefits', 'Benefits', 'one-line', 0, 0, 1),
(1179, 'attach_stickers', 'Attach Stickers', 'one-line', 0, 0, 1),
(1180, 'phone_number', 'Phone Number', 'one-line', 0, 0, 1),
(1181, 'group_admin_role', 'Group Administrator Role', 'one-line', 0, 0, 1),
(1182, 'membership_info', 'Membership Info', 'one-line', 0, 0, 1),
(1183, 'username_censored_word_detected', 'Username contains a censored word', 'one-line', 0, 0, 1),
(1184, 'profanity_filter', 'Profanity Filter', 'one-line', 0, 0, 1),
(1185, 'invalid_phone_number', 'Invalid Phone Number', 'one-line', 0, 0, 1),
(1186, 'group_moderator_role', 'Group Moderator Role', 'one-line', 0, 0, 1),
(1187, 'boxed', 'Boxed Layout', 'one-line', 0, 0, 1),
(1188, 'site_terms_conditions', 'Site Terms &amp; Conditions', 'one-line', 0, 0, 1),
(1189, 'group_banned_user_role', 'Role for Banned Group Users', 'one-line', 0, 0, 1);
INSERT INTO `gr_language_strings` (`string_id`, `string_constant`, `string_value`, `string_type`, `skip_update`, `skip_cache`, `language_id`) VALUES
(1190, 'enter_otp', 'Enter OTP', 'one-line', 0, 0, 1),
(1191, 'edit_package', 'Edit Package', 'one-line', 0, 0, 1),
(1192, 'show_on_guest_login', 'Show on Guest Login Page', 'one-line', 0, 0, 1),
(1193, 'site_admin_role', 'Site Administrator Role', 'one-line', 0, 0, 1),
(1194, 'slug', 'Slug', 'one-line', 0, 0, 1),
(1195, 'link_filter', 'Link Filter', 'one-line', 0, 0, 1),
(1196, 'filter_username', 'Filter Username', 'one-line', 0, 0, 1),
(1197, 'forward_messages', 'Forward Messages', 'one-line', 0, 0, 1),
(1198, 'verify', 'Verify', 'one-line', 0, 0, 1),
(1199, 'site_unverified_user_role', 'Role for Unverified Site Users (Email)', 'one-line', 0, 0, 1),
(1200, 'landing_page_faq_question_7_answer', '', 'one-line', 0, 0, 1),
(1201, 'recipient', 'Recipient', 'one-line', 0, 0, 1),
(1202, 'contains_blacklisted_links', 'Your message contains blacklisted links', 'one-line', 0, 0, 1),
(1203, 'benefit', 'Benefit', 'one-line', 0, 0, 1),
(1204, 'view_password_protected_groups', 'View Password Protected Groups', 'one-line', 0, 0, 1),
(1205, 'content', 'Content', 'one-line', 0, 0, 1),
(1206, 'pinned_group', 'Pinned', 'one-line', 0, 0, 1),
(1207, 'site_slogan', 'Site Slogan', 'one-line', 0, 0, 1),
(1208, 'landing_page_faq_question_8', '', 'one-line', 0, 0, 1),
(1209, 'total_members', 'Total Members', 'one-line', 0, 0, 1),
(1210, 'guest_user_role', 'Site Role for Guests Logging In', 'one-line', 0, 0, 1),
(1211, 'category', 'Category', 'one-line', 0, 0, 1),
(1212, 'web_push_new_pm_message', 'Sent you a private message.', 'one-line', 0, 0, 1),
(1213, 'filter_full_name', 'Filter Full Name', 'one-line', 0, 0, 1),
(1214, 'user_does_not_exist', 'User Does not Exist', 'one-line', 0, 0, 1),
(1215, 'link_filter_tip', 'To block all domain pages and subpages, just append an asterisk (*) at the end of the domain in your blacklist.', 'one-line', 0, 0, 1),
(1216, 'site_banned_user_role', 'Site Role for Banned Users', 'one-line', 0, 0, 1),
(1217, 'color_scheme', 'Color Scheme', 'one-line', 0, 0, 1),
(1218, 'landing_page_faq_question_8_answer', '', 'one-line', 0, 0, 1),
(1219, 'membership', 'Membership', 'one-line', 0, 0, 1),
(1220, 'verify_phone_number', 'Verify Phone Number', 'one-line', 0, 0, 1),
(1221, 'cloud_storage_ftp_path', 'FTP Path', 'one-line', 0, 0, 1),
(1222, 'view_all', 'View All', 'one-line', 0, 0, 1),
(1223, 'found_duplicate_roles', 'Choose a distinct role for each attribute', 'one-line', 0, 0, 1),
(1224, 'tls', 'TLS', 'one-line', 0, 0, 1),
(1225, 'landing_page_faq_question_9', '', 'one-line', 0, 0, 1),
(1226, 'laut_fm', 'Laut.fm', 'one-line', 0, 0, 1),
(1227, 'filter_messages', 'Filter Messages', 'one-line', 0, 0, 1),
(1228, 'platform', 'Platform', 'one-line', 0, 0, 1),
(1229, 'create_badge', 'Create Badge', 'one-line', 0, 0, 1),
(1230, 'verify_phone_number_text', 'We send you a one-time password to the mobile number that you entered. Type the OTP received and click on verify.', 'one-line', 0, 0, 1),
(1231, 'your_current_info', 'Your Current Info', 'one-line', 0, 0, 1),
(1232, 'group_invitation_email_content', 'Come join our community where you can share, learn, and discover amazing resources, ask questions, engage in conversations.', 'one-line', 0, 0, 1),
(1233, 'landing_page_faq_question_9_answer', '', 'one-line', 0, 0, 1),
(1234, 'invoice_logo', 'Invoice Logo', 'one-line', 0, 0, 1),
(1235, 'react', 'React', 'one-line', 0, 0, 1),
(1236, 'add_provider', 'Add Provider', 'one-line', 0, 0, 1),
(1237, 'resend_otp', 'Resend OTP', 'one-line', 0, 0, 1),
(1238, 'ban_from_group_confirmation', 'Are you sure you want to ban this user from Group ?', 'one-line', 0, 0, 1),
(1239, 'set_fake_online_users', 'Set Fake Online Users', 'one-line', 0, 0, 1),
(1240, 'verify_your_phone_number', 'The phone number was not verified. In order to proceed, please, verify.', 'one-line', 0, 0, 1),
(1241, 'name', 'Name', 'one-line', 0, 0, 1),
(1242, 'message_alignment', 'Message Alignment', 'one-line', 0, 0, 1),
(1243, 'disable_right_click', 'Disable Right Click', 'one-line', 0, 0, 1),
(1244, 'page_title', 'Page Title', 'one-line', 0, 0, 1),
(1245, 'validate_settings', 'Validate Settings', 'one-line', 0, 0, 1),
(1246, 'user_pending_approval_email_heading', 'Pending Approval', 'one-line', 0, 0, 1),
(1247, 'phone_number_verified', 'Your phone number has been verified', 'one-line', 0, 0, 1),
(1248, 'user_initiated_audio_call', 'The user has initiated a audio call', 'one-line', 0, 0, 1),
(1249, 'landing_page_faq_question_6_answer', '', 'one-line', 0, 0, 1),
(1250, 'user_pending_approval_email_content', 'A new user has registered on your website and is currently pending approval. The user&#039;s details are as follows:', 'one-line', 0, 0, 1),
(1251, 'advanced_search_criteria', 'To perform a search, you need to provide at least one field to search.', 'one-line', 0, 0, 1),
(1252, 'line_busy_message', 'The line is currently busy with an ongoing audio/video call.', 'one-line', 0, 0, 1),
(1253, 'denied', 'Permission Denied', 'one-line', 0, 0, 1),
(1254, 'transactions', 'Transactions', 'one-line', 0, 0, 1),
(1255, 'invalid_otp_code', 'Invalid OTP Code', 'one-line', 0, 0, 1),
(1256, 'video_audio_chat', 'Video/Audio Chat', 'one-line', 0, 0, 1),
(1257, 'samesite_cookies', 'SameSite Cookies', 'one-line', 0, 0, 1),
(1258, 'user_pending_approval_email_subject', 'New User Pending Approval', 'one-line', 0, 0, 1),
(1259, 'audio_chat', 'Audio Chat', 'one-line', 0, 0, 1),
(1260, 'temporary_ban_from_group', 'Temporary Ban', 'one-line', 0, 0, 1),
(1261, 'packages', 'Packages', 'one-line', 0, 0, 1),
(1262, 'create_audio_chat_groups', 'Create Groups with Audio Chat', 'one-line', 0, 0, 1),
(1263, 'cloud_storage_ftp_endpoint', 'FTP Endpoint', 'one-line', 0, 0, 1),
(1264, 'user_pending_approval_email_button_label', 'Visit Website', 'one-line', 0, 0, 1),
(1265, 'sharing_your_location', 'Sharing Your Location', 'one-line', 0, 0, 1),
(1266, 'now_playing', 'Now Playing', 'one-line', 0, 0, 1),
(1267, 'shared_location', 'Shared Location', 'one-line', 0, 0, 1),
(1268, 'pricing_subtitle', 'Get More with Subscriptions: Exploring Pricing Options', 'one-line', 0, 0, 1),
(1269, 'view_invisible_users', 'View Invisible Users (Offline Mode)', 'one-line', 0, 0, 1),
(1270, 'share_location', 'Share Location', 'one-line', 0, 0, 1),
(1271, 'on_private_message_offline', 'Someone send a Private Message when Offline', 'one-line', 0, 0, 1),
(1272, 'preview_pdf_files', 'Preview PDF Files', 'one-line', 0, 0, 1),
(1273, 'invalid_group_role', 'Invalid Group Role', 'one-line', 0, 0, 1),
(1274, 'welcome_user_email_subject', 'Welcome Aboard', 'one-line', 0, 0, 1),
(1275, 'welcome_user_email_heading', 'Welcome Aboard', 'one-line', 0, 0, 1),
(1276, 'approve_phone_number', 'Approve Phone Number', 'one-line', 0, 0, 1),
(1277, 'welcome_user_email_content', 'A warm and heartfelt welcome to you! We are absolutely thrilled to welcome you as a cherished member of our online community. Feel free to explore, express yourself, and make this space your own. We&#039;re here to support you every step of the way.', 'one-line', 0, 0, 1),
(1278, 'available_packages', 'Available Packages', 'one-line', 0, 0, 1),
(1279, 'web_push_on_friend_request', 'Sent you a friend request', 'one-line', 0, 0, 1),
(1280, 'assets_folder_missing', '&quot;files&quot; folder missing. [1] Login to your cloud storage account [2] Create &quot;assets&quot; folder [3] Upload &quot;assets/files/&quot; folder &amp; its contents inside &quot;assets&quot; folder', 'one-line', 0, 0, 1),
(1281, 'welcome_user_email_button_label', 'Explore Now', 'one-line', 0, 0, 1),
(1282, 'on_friend_request', 'Someone send a Friend Request', 'one-line', 0, 0, 1),
(1283, 'twilio_account_sid', 'Twilio Account SID', 'one-line', 0, 0, 1),
(1284, 'show_timestamp_on_mouseover', 'Show Timestamp on Mouseover', 'one-line', 0, 0, 1),
(1285, 'send_welcome_email', 'Send Welcome Email', 'one-line', 0, 0, 1),
(1286, 'when_changing_group_role', 'When Changing Group Role', 'one-line', 0, 0, 1),
(1287, 'previous', 'Previous', 'one-line', 0, 0, 1),
(1288, 'separate_commas', 'Separate with commas', 'one-line', 0, 0, 1),
(1289, 'email_contents', 'Email Contents', 'one-line', 0, 0, 1),
(1290, 'line_break_delimiter', 'Use line break as the delimiter', 'one-line', 0, 0, 1),
(1291, 'invalid_bucket_name', 'Invalid Bucket Name', 'one-line', 0, 0, 1),
(1292, 'email_category', 'Email Category', 'one-line', 0, 0, 1),
(1293, 'download_files', 'Download Files', 'one-line', 0, 0, 1),
(1294, 'offline_page_button', 'Refresh', 'one-line', 0, 0, 1),
(1295, 'welcome_email', 'Welcome Email', 'one-line', 0, 0, 1),
(1296, 'invalid_pwa_icon_dimensions', 'Use an image that is at least 512X512 pixels', 'one-line', 0, 0, 1),
(1297, 'verification_email', 'Verification Email', 'one-line', 0, 0, 1),
(1298, 'group_invitation_email_heading', 'You&#039;ve Got an invitation', 'one-line', 0, 0, 1),
(1299, 'login_link_email', 'Login Link Email', 'one-line', 0, 0, 1),
(1300, 'fields', 'Fields', 'one-line', 0, 0, 1),
(1301, 'new_friend_request', 'New Friend Request', 'one-line', 0, 0, 1),
(1302, 'sending_limit', 'Sending Limit (Per Minute)', 'one-line', 0, 0, 1),
(1303, 'new_private_message', 'New Private Message', 'one-line', 0, 0, 1),
(1304, 'on_new_user_pending_approval', 'New user signup pending approval', 'one-line', 0, 0, 1),
(1305, 'user_pending_approval', 'User Pending Approval', 'one-line', 0, 0, 1),
(1306, 'twilio_auth_token', 'Twilio Auth Token', 'one-line', 0, 0, 1),
(1307, 'reset_password', 'Reset Password', 'one-line', 0, 0, 1),
(1308, 'csrf_token', 'CSRF Token', 'one-line', 0, 0, 1),
(1309, 'updated_user_group_role', 'Group role has been changed to', 'one-line', 0, 0, 1),
(1310, 'email_subject', 'Email Subject', 'one-line', 0, 0, 1),
(1311, 'unleavable_group', 'Unleavable Group', 'one-line', 0, 0, 1),
(1312, 'private_conversations', 'Private Conversations', 'one-line', 0, 0, 1),
(1313, 'sms_settings', 'SMS Settings', 'one-line', 0, 0, 1),
(1314, 'email_heading', 'Email Heading', 'one-line', 0, 0, 1),
(1315, 'delete_all', 'Delete All', 'one-line', 0, 0, 1),
(1316, 'dateformat', 'Date Format', 'one-line', 0, 0, 1),
(1317, 'allow_sharing_email_addresses', 'Allow Sharing Email Addresses', 'one-line', 0, 0, 1),
(1318, 'email_content', 'Email Content', 'one-line', 0, 0, 1),
(1319, 'request_timeout', 'Timeout Seconds (Long Polling)', 'one-line', 0, 0, 1),
(1320, 'set_group_slug', 'Set Group Slug', 'one-line', 0, 0, 1),
(1321, 'enable_photo_upload_on_signup', 'Enable Photo upload on Signup', 'one-line', 0, 0, 1),
(1322, 'quinary_text_color', 'Quinary Text Color', 'one-line', 0, 0, 1),
(1323, 'email_button_label', 'Email Button Label', 'one-line', 0, 0, 1),
(1324, 'sms_src', 'Sender/From Number', 'one-line', 0, 0, 1),
(1325, 'javascript_files', 'Javascript Files', 'one-line', 0, 0, 1),
(1326, 'select_option', 'Select Option from Dropdown', 'one-line', 0, 0, 1),
(1327, 'register', 'Register', 'one-line', 0, 0, 1),
(1328, 'landing_page_faq_question_4_answer', '', 'one-line', 0, 0, 1),
(1329, 'notification_settings', 'Notifications', 'one-line', 0, 0, 1),
(1330, 'group_invitation_email', 'Group Invitation', 'one-line', 0, 0, 1),
(1331, 'complainant', 'Complainant', 'one-line', 0, 0, 1),
(1332, 'messagebird', 'MessageBird', 'one-line', 0, 0, 1),
(1333, 'loading_image', 'Loading Image', 'one-line', 0, 0, 1),
(1334, 'sightengine', 'Sightengine', 'one-line', 0, 0, 1),
(1335, 'conversation', 'Conversation', 'one-line', 0, 0, 1),
(1336, 'required', 'Required', 'one-line', 0, 0, 1),
(1337, 'change_username', 'Change Username', 'one-line', 0, 0, 1),
(1338, 'google_cloud_vision', 'Google Cloud Vision', 'one-line', 0, 0, 1),
(1339, 'set_default_group_role_within_group', 'Set Default Group Role within the Group', 'one-line', 0, 0, 1),
(1340, 'on_updating_group_info', 'Updating Group Information', 'one-line', 0, 0, 1),
(1341, 'search_on_change_of_input', 'Search on Change of Input', 'one-line', 0, 0, 1),
(1342, 'footer', 'Footer', 'one-line', 0, 0, 1),
(1343, 'messagebird_api_key', 'MessageBird API Key', 'one-line', 0, 0, 1),
(1344, 'welcome_screen_footer_text', 'By accessing this website, you are agreeing to be bound by the Terms and Conditions of Use', 'one-line', 0, 0, 1),
(1345, 'flood_control_time_difference', 'Required time difference between each message (seconds)', 'one-line', 0, 0, 1),
(1346, 'read_more', 'Read More', 'one-line', 0, 0, 1),
(1347, 'new_friend_request_email_subject', 'New Friend Request Received', 'one-line', 0, 0, 1),
(1348, 'group_role_4', 'Member', 'one-line', 0, 0, 1),
(1349, 'blacklist_user_permission_denied', 'Permission Denied : You are not allowed to block/ignore Site Administrators', 'one-line', 0, 0, 1),
(1350, 'abuse', 'Abuse', 'one-line', 0, 0, 1),
(1351, 'secret_group', 'Secret Group', 'one-line', 0, 0, 1),
(1352, 'invalid_credentials', 'Invalid Credentials', 'one-line', 0, 0, 1),
(1353, 'change_email_address', 'Change Email Address', 'one-line', 0, 0, 1),
(1354, 'number_field', 'Number', 'one-line', 0, 0, 1),
(1355, 'delete_older_than', 'Delete older than (Minutes)', 'one-line', 0, 0, 1),
(1356, 'new_friend_request_email_heading', 'New Friend Request', 'one-line', 0, 0, 1),
(1357, 'custom_background', 'Custom Background', 'one-line', 0, 0, 1),
(1358, 'google_cloud_vision_api_key', 'Google Cloud API key', 'one-line', 0, 0, 1),
(1359, 'next', 'Next', 'one-line', 0, 0, 1),
(1360, 'delete_file_confirmation', 'Are you sure you want to delete this file ?', 'one-line', 0, 0, 1),
(1361, 'missing_profile_image', 'Please upload a profile image to continue', 'one-line', 0, 0, 1),
(1362, 'gcv_image_removal_criteria', 'Delete Images that contain', 'one-line', 0, 0, 1),
(1363, 'create_public_group', 'Create Public Group', 'one-line', 0, 0, 1),
(1364, 'linkedin_url', 'LinkedIn URL', 'one-line', 0, 0, 1),
(1365, 'pm_only_specific_roles', 'PM only specific roles', 'one-line', 0, 0, 1),
(1366, 'adult_content', 'Adult Content', 'one-line', 0, 0, 1),
(1367, 'selected_package_information', 'Selected Package Information', 'one-line', 0, 0, 1),
(1368, 'change_avatar', 'Change Avatar', 'one-line', 0, 0, 1),
(1369, 'profile_image', 'Profile Image', 'one-line', 0, 0, 1),
(1370, 'spoof_content', 'Spoof', 'one-line', 0, 0, 1),
(1371, 'new_friend_request_email_content', 'You have received a new friend request on our platform. You can view and respond to this request by logging into your account and going to the &quot;Friends&quot; section.', 'one-line', 0, 0, 1),
(1372, 'left', 'Left', 'one-line', 0, 0, 1),
(1373, 'registration_otp_message', 'Your OTP for registration is', 'one-line', 0, 0, 1),
(1374, 'medical_content', 'Medical', 'one-line', 0, 0, 1),
(1375, 'default_bg_group_chat', 'Default Background Image [Group Chat]', 'one-line', 0, 0, 1),
(1376, 'octonary_border_color', 'Octonary Border Color', 'one-line', 0, 0, 1),
(1377, 'hide', 'Hide', 'one-line', 0, 0, 1),
(1378, 'violence_content', 'Violence', 'one-line', 0, 0, 1),
(1379, 'available_payment_gateways', 'Available Payment Gateways', 'one-line', 0, 0, 1),
(1380, 'choose_site_roles', 'Choose Site Roles', 'one-line', 0, 0, 1),
(1381, 'create_groups', 'Create Groups', 'one-line', 0, 0, 1),
(1382, 'racy_content', 'Racy', 'one-line', 0, 0, 1),
(1383, 'chat_page_footer', 'Footer (Chat Page)', 'one-line', 0, 0, 1),
(1384, 'new_friend_request_email_button_label', 'View Request', 'one-line', 0, 0, 1),
(1385, 'delete_files', 'Delete Files', 'one-line', 0, 0, 1),
(1386, 'perspective_api', 'Perspective API', 'one-line', 0, 0, 1),
(1387, 'chat_page_header', 'Header (Chat Page)', 'one-line', 0, 0, 1),
(1388, 'text_moderation', 'Text Moderation', 'one-line', 0, 0, 1),
(1389, 'forward', 'Forward', 'one-line', 0, 0, 1),
(1390, 'price', 'Price', 'one-line', 0, 0, 1),
(1391, 'phone_number_exists', 'Phone number already exists', 'one-line', 0, 0, 1),
(1392, 'view_reactions', 'View Reactions', 'one-line', 0, 0, 1),
(1393, 'perspective_google_cloud_api_key', 'Google Cloud API Key', 'one-line', 0, 0, 1),
(1394, 'default_bg_private_chat', 'Default Background Image [Private Chat]', 'one-line', 0, 0, 1),
(1395, 'advanced_user_searches', 'Advanced User Searches', 'one-line', 0, 0, 1),
(1396, 'values', 'Values', 'one-line', 0, 0, 1),
(1397, 'select_plan', 'Select Plan', 'one-line', 0, 0, 1),
(1398, 'video_chat', 'Video Chat', 'one-line', 0, 0, 1),
(1399, 'choose_file', 'Choose a file', 'one-line', 0, 0, 1),
(1400, 'schedule_cronjob_command_message', 'Make sure to schedule the following cronjob command in your hosting server to run the script automatically at your preferred intervals', 'one-line', 0, 0, 1),
(1401, 'background', 'Background', 'one-line', 0, 0, 1),
(1402, 'view_email_address', 'View Email Address', 'one-line', 0, 0, 1),
(1403, 'access_logs', 'Access Logs', 'one-line', 0, 0, 1),
(1404, 'please_note', 'Please Note', 'one-line', 0, 0, 1),
(1405, 'videos', 'Videos', 'one-line', 0, 0, 1),
(1406, 'view_phone_number', 'View Phone Number', 'one-line', 0, 0, 1),
(1407, 'clear_chat_history', 'Clear Chat History', 'one-line', 0, 0, 1),
(1408, 'send_email_notification', 'Send Email Notification', 'one-line', 0, 0, 1),
(1409, 'welcome_message', 'Welcome Message', 'one-line', 0, 0, 1),
(1410, 'new_private_message_email_subject', 'New Private Message Received', 'one-line', 0, 0, 1),
(1411, 'quinary_font_size', 'Quinary Font Size', 'one-line', 0, 0, 1),
(1412, 'perspective_api_threshold', 'Perspective API Threshold', 'one-line', 0, 0, 1),
(1413, 'agora', 'Agora', 'one-line', 0, 0, 1),
(1414, 'toxicity', 'Toxicity', 'one-line', 0, 0, 1),
(1415, 'realtime_settings', 'Realtime Settings', 'one-line', 0, 0, 1),
(1416, 'new_private_message_email_heading', 'New Message Received', 'one-line', 0, 0, 1),
(1417, 'days', 'Days', 'one-line', 0, 0, 1),
(1418, 'refresh', 'Refresh', 'one-line', 0, 0, 1),
(1419, 'custom_page_1_content', '<p>This is a simple and easy to use chatroom. Join us now and talk to people from all over the world.\r\n\r\nOur mission is to share and grow the world’s knowledge. </p><p>A vast amount of the knowledge that would be valuable to many people is currently only available to a few — either locked in people’s heads, or only accessible to select groups. We want to connect the people who have knowledge to the people who need it, to bring together people with different perspectives so they can understand each other better, and to empower everyone to share their knowledge for the benefit of the rest of the world.</p><p>A chat room is a Web site, part of a Web site, or part of an online service, that provides a venue for communities of users with a common interest to communicate in real time. Forums and discussion groups, in comparison, allow users to post messages but don\'t have the capacity for interactive messaging. Most chat rooms don\'t require users to have any special software; those that do, such as Internet Relay Chat (IRC) allow users to download it from the Internet.</p><p>Chat room users register for the chat room of their choice, choose a user name and password, and log into a particular room (most sites have multiple chat rooms). Inside the chat room, generally there is a list of the people currently online, who also are alerted that another person has entered the chat room. To chat, users type a message into a text box. The message is almost immediately visible in the larger communal message area and other users respond. Users can enter chat rooms and read messages without sending any, a practice known as lurking.</p><p>Because chat room messages are spontaneous and instantly visible, there is a potential for abuse, which may or may not be intentional. Site hosts typically post a frequently asked questions (FAQ) list to guide users to appropriate chat room behavior, such as introducing yourself when you enter a room, making it clear when you are directing a question or response to a specific user, and reporting disruptive users, for example. Disruptive users may verbally abuse other chatters, monopolize the conversation, or even just disable it by repeatedly typing the same word or phrase into the conversation, a practice (much frowned upon) known as scrolling.</p><p>Chat rooms can be found that focus on virtually any aspect of human endeavor or interest: there are current communities based on classic movies, Irish ancestry, baton twirling, and psychic readings, for example. Various sites, such as Yahoo, provide a directory of chat sites. Others, such as MSN Web Communities, guide users through the steps required to create their own chat room.</p><p>\r\n</p>\r\n\r\n', 'multi_line', 1, 1, 1),
(1420, 'pages', 'Pages', 'one-line', 0, 0, 1),
(1421, 'cloudflare_turnstile', 'Cloudflare Turnstile', 'one-line', 0, 0, 1),
(1422, 'severe_toxicity', 'Severe Toxicity', 'one-line', 0, 0, 1),
(1423, 'septenary_font_size', 'Septenary Font Size', 'one-line', 0, 0, 1),
(1424, 'profanity', 'Profanity', 'one-line', 0, 0, 1),
(1425, 'attach_files', 'Attach Files', 'one-line', 0, 0, 1),
(1426, 'select_group', 'Select a Group or Chat', 'one-line', 0, 0, 1),
(1427, 'threat', 'Threat', 'one-line', 0, 0, 1),
(1428, 'vc_twilio_account_sid', 'Twilio Account SID', 'one-line', 0, 0, 1),
(1429, 'unblock_user', 'Unblock User', 'one-line', 0, 0, 1),
(1430, 'identity_attack', 'Identity Attack', 'one-line', 0, 0, 1),
(1431, 'new_private_message_email_content', 'You have received a new private message. Please log in to your account to read the message and reply if necessary.', 'one-line', 0, 0, 1),
(1432, 'insult', 'Insult', 'one-line', 0, 0, 1),
(1433, 'cron_job_url', 'Cron Job URL', 'one-line', 0, 0, 1),
(1434, 'ip_blacklisted', 'Your IP is blacklisted', 'one-line', 0, 0, 1),
(1435, 'perspectiveapi_text_removal_criteria', 'Delete text that contain', 'one-line', 0, 0, 1),
(1436, 'menu_items', 'Menu Items', 'one-line', 0, 0, 1),
(1437, 'new_private_message_email_button_label', 'View Message', 'one-line', 0, 0, 1),
(1438, 'normalize_text_message', 'Normalize Text Message', 'one-line', 0, 0, 1),
(1439, 'detected_forbidden_words_content', 'Your message includes forbidden words or content.', 'one-line', 0, 0, 1),
(1440, 'twitch_url', 'Twitch URL', 'one-line', 0, 0, 1),
(1441, 'group_create_limit', 'Maximum Number of Groups a User can Create', 'one-line', 0, 0, 1),
(1442, 'entry_page_footer_text', 'Ⓒ 2023. All Rights Reserved. Site Name', 'one-line', 0, 0, 1),
(1443, 'exceeded_group_creation_limit', 'Exceeded Maximum Number of Groups You can Create', 'one-line', 0, 0, 1),
(1444, 'switch_color_scheme', 'Switch Color Scheme', 'one-line', 0, 0, 1),
(1445, 'hide_fakes', 'Hide Fakes', 'one-line', 0, 0, 1),
(1446, 'ltr', 'Left to Right', 'one-line', 0, 0, 1),
(1447, 'show_language', 'Show Language', 'one-line', 0, 0, 1),
(1448, 'generate_fake_users', 'Generate Fake Users', 'one-line', 0, 0, 1),
(1449, 'no_of_users', 'Number of Users', 'one-line', 0, 0, 1),
(1450, 'gfycat', 'Gfycat', 'one-line', 0, 0, 1),
(1451, 'skip_text_moderation', 'Skip Text Moderation', 'one-line', 0, 0, 1),
(1452, 'generate_profile_picture', 'Generate Profile Picture', 'one-line', 0, 0, 1),
(1453, 'confirm_action', 'Are you sure you want to continue ?', 'one-line', 0, 0, 1),
(1454, 'invalid_order_id', 'Invalid Order ID', 'one-line', 0, 0, 1),
(1455, 'skip_image_moderation', 'Skip Image Moderation', 'one-line', 0, 0, 1),
(1456, 'email_domain', 'Email Domain', 'one-line', 0, 0, 1),
(1457, 'menu_item', 'Menu Item', 'one-line', 0, 0, 1),
(1458, 'vc_twilio_auth_token', 'Twilio Auth Token', 'one-line', 0, 0, 1),
(1459, 'locale', 'Locale', 'one-line', 0, 0, 1),
(1460, 'show_group_members_count', 'Show Group Members Count', 'one-line', 0, 0, 1),
(1461, 'created', 'Created', 'one-line', 0, 0, 1),
(1462, 'videosdk', 'Video SDK', 'one-line', 0, 0, 1),
(1463, 'max_fake_users_limit', 'The maximum number of fake users you can create per run is 50.', 'one-line', 0, 0, 1),
(1464, 'change_password', 'Change Password', 'one-line', 0, 0, 1),
(1465, 'total_users', 'Total Users', 'one-line', 0, 0, 1),
(1466, 'ip_intelligence', 'IP Intelligence', 'one-line', 0, 0, 1),
(1467, 'stay_online', 'Stay Online', 'one-line', 0, 0, 1),
(1468, 'set_timezone', 'Set Timezone', 'one-line', 0, 0, 1),
(1469, 'check_inbox', 'We have sent an email to your email address. Please check your Inbox.', 'one-line', 0, 0, 1),
(1470, 'amount', 'Amount', 'one-line', 0, 0, 1),
(1471, 'found_cache_folder_cloud_storage', 'Found the &quot;assets/cache&quot; directory in your cloud storage. You only need to upload the &quot;assets/files&quot; directory. Please remove the &quot;assets/cache/&quot; directory from the cloud storage.', 'one-line', 0, 0, 1),
(1472, 'temporarily_banned_from_group_message', 'You are Temporarily Banned from accessing the Group', 'one-line', 0, 0, 1),
(1473, 'vc_twilio_api_key', 'Twilio API Key (SID)', 'one-line', 0, 0, 1),
(1474, 'sort_index', 'Sort Index', 'one-line', 0, 0, 1),
(1475, 'screen_sharing', 'Screen Sharing', 'one-line', 0, 0, 1),
(1476, 'bank_account_details', 'Bank Account Details', 'one-line', 0, 0, 1),
(1477, 'sharing_video', 'Sharing Video', 'one-line', 0, 0, 1),
(1478, 'group_categories', 'Categories', 'one-line', 0, 0, 1),
(1479, 'proxycheck_io', 'ProxyCheck.io', 'one-line', 0, 0, 1),
(1480, 'manage_group_categories', 'Manage Group Categories', 'one-line', 0, 0, 1),
(1481, 'bank_transfer_details_subheading', 'Transfer the amount to the bank account provided below.', 'one-line', 0, 0, 1),
(1482, 'video_search_engine', 'Video Search Engine', 'one-line', 0, 0, 1),
(1483, 'create_group_category', 'Create Group Category', 'one-line', 0, 0, 1),
(1484, 'vc_twilio_api_secret_key', 'Twilio API Secret Key', 'one-line', 0, 0, 1),
(1485, 'video_search_engine_api', 'Video Search Engine API', 'one-line', 0, 0, 1),
(1486, 'categorize_groups', 'Categorize Groups', 'one-line', 0, 0, 1),
(1487, 'bank_transfer_reciept_pending_approval', 'Upload Receipt Status: Waiting for Approval', 'one-line', 0, 0, 1),
(1488, 'videos_per_load', 'Videos Per Load', 'one-line', 0, 0, 1),
(1489, 'category_name', 'Category Name', 'one-line', 0, 0, 1),
(1490, 'youtube', 'Youtube', 'one-line', 0, 0, 1),
(1491, 'category_order', 'Category Order', 'one-line', 0, 0, 1),
(1492, 'search_share_videos', 'Search &amp; Share Videos', 'one-line', 0, 0, 1),
(1493, 'getipintel', 'GetIPIntel', 'one-line', 0, 0, 1),
(1494, 'create_category', 'Create Category', 'one-line', 0, 0, 1),
(1495, 'message_text_formatting', 'Message Text Formatting', 'one-line', 0, 0, 1),
(1496, 'upload_receipt', 'Upload Receipt', 'one-line', 0, 0, 1),
(1497, 'edit_category', 'Edit Category', 'one-line', 0, 0, 1),
(1498, 'selection', 'Selection', 'one-line', 0, 0, 1),
(1499, 'vc_agora_app_id', 'Agora App ID', 'one-line', 0, 0, 1),
(1500, 'category_image', 'Category Image', 'one-line', 0, 0, 1),
(1501, 'membership_expiring', 'Your membership package will expire soon', 'one-line', 0, 0, 1),
(1502, 'group_category_id', 'Group Category ID', 'one-line', 0, 0, 1),
(1503, 'go_homepage', 'Go Homepage', 'one-line', 0, 0, 1),
(1504, 'membership_expired', 'Your membership package has expired', 'one-line', 0, 0, 1),
(1505, 'set_group_category', 'Set Group Category', 'one-line', 0, 0, 1),
(1506, 'default_group_category', 'Default Group Category', 'one-line', 0, 0, 1),
(1507, 'ip_intelligence_api_key', 'API Key', 'one-line', 0, 0, 1),
(1508, 'group_order', 'Group Order (Descending Order)', 'one-line', 0, 0, 1),
(1509, 'no_of_groups_landing_page', 'Number of Groups to Show', 'one-line', 0, 0, 1),
(1510, 'landing_page_packages_section_heading', 'Membership Packages', 'one-line', 0, 0, 1),
(1511, 'landing_page_packages_section_description', 'Get More with Subscriptions: Exploring Pricing Options', 'one-line', 0, 0, 1),
(1512, 'pricing_menu_item', 'Pricing', 'one-line', 0, 0, 1),
(1513, 'packages_section_status', 'Membership Packages Section [Status]', 'one-line', 0, 0, 1),
(1514, 'packages_section_heading', 'Membership Packages Section [Heading]', 'one-line', 0, 0, 1),
(1515, 'packages_section_description', 'Membership Packages Section [Description]', 'one-line', 0, 0, 1),
(1516, 'require_billing_address', 'Require Billing Address', 'one-line', 0, 0, 1),
(1517, 'under_mainteance_title', 'Temporarily Unavailable', 'one-line', 0, 0, 1),
(1518, 'under_mainteance_description', 'Our site is currently undergoing maintenance. We&#039;ll be back soon. Thank you for your patience.', 'one-line', 0, 0, 1),
(1519, 'under_mainteance_button', 'Refresh Page', 'one-line', 0, 0, 1),
(1520, 'under_maintenance_mode', 'Under Maintenance Mode', 'one-line', 0, 0, 1),
(1521, 'maintenance_mode_access_code', 'Access URL (Maintenance Mode)', 'one-line', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_login_sessions`
--

CREATE TABLE `gr_login_sessions` (
  `login_session_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `initiated_ip_address` varchar(90) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `csrf_token` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `csrf_token_generated_on` datetime NOT NULL,
  `log_device` int NOT NULL DEFAULT '1',
  `login_from_user_id` bigint DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '0 = Inactive\r\n1 = Active\r\n2 = Expired',
  `failed_attempts` int NOT NULL,
  `time_stamp` int NOT NULL,
  `last_access` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_mailbox`
--

CREATE TABLE `gr_mailbox` (
  `mail_id` bigint NOT NULL,
  `email_addresses` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `email_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_status` int NOT NULL DEFAULT '0' COMMENT '0 = Pending\r\n1 = Send\r\n2 = Failed',
  `mail_error_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_membership_orders`
--

CREATE TABLE `gr_membership_orders` (
  `order_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `membership_package_id` bigint NOT NULL,
  `order_status` int NOT NULL DEFAULT '0' COMMENT '0 = Pending\r\n1 - Success\r\n2 - Failed\r\n3 - Requires Validation',
  `payment_gateway_id` bigint DEFAULT NULL,
  `transaction_info` text COLLATE utf8mb4_unicode_ci,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_membership_packages`
--

CREATE TABLE `gr_membership_packages` (
  `membership_package_id` bigint NOT NULL,
  `string_constant` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_recurring` int NOT NULL DEFAULT '0',
  `pricing` int NOT NULL DEFAULT '0',
  `duration` int NOT NULL DEFAULT '30',
  `related_site_role_id` bigint NOT NULL,
  `site_role_id_on_expire` bigint NOT NULL,
  `package_sort_index` bigint NOT NULL DEFAULT '1',
  `disabled` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_payment_gateways`
--

CREATE TABLE `gr_payment_gateways` (
  `payment_gateway_id` bigint NOT NULL,
  `identifier` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credentials` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `disabled` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_private_chat_messages`
--

CREATE TABLE `gr_private_chat_messages` (
  `private_chat_message_id` bigint NOT NULL,
  `private_conversation_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `original_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `filtered_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `system_message` int NOT NULL DEFAULT '0',
  `parent_message_id` bigint DEFAULT NULL,
  `attachment_type` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_preview` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_reactions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `read_status` int NOT NULL DEFAULT '0' COMMENT '0 = Unread\r\n1 = Read',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_private_chat_messages_reactions`
--

CREATE TABLE `gr_private_chat_messages_reactions` (
  `private_chat_message_reaction_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `private_chat_message_id` bigint NOT NULL,
  `reaction_id` int NOT NULL COMMENT '1 = Like 2 = Love 3 = Haha 4 = Wow 5 = Sad 6 = Angry',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_private_conversations`
--

CREATE TABLE `gr_private_conversations` (
  `private_conversation_id` bigint NOT NULL,
  `initiator_user_id` bigint NOT NULL,
  `recipient_user_id` bigint NOT NULL,
  `initiator_load_message_id_from` bigint DEFAULT NULL,
  `recipient_load_message_id_from` bigint DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_push_subscriptions`
--

CREATE TABLE `gr_push_subscriptions` (
  `push_subscriber_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `device_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `push_notification_service` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_realtime_logs`
--

CREATE TABLE `gr_realtime_logs` (
  `realtime_log_id` bigint NOT NULL,
  `log_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `related_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_scheduled_messages`
--

CREATE TABLE `gr_scheduled_messages` (
  `scheduled_message_id` bigint NOT NULL,
  `message_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `repeat_message` int NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `repeat_interval` int NOT NULL,
  `repetition_rate` int NOT NULL,
  `iteration_count` int NOT NULL DEFAULT '0',
  `send_message_on` datetime NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_settings`
--

CREATE TABLE `gr_settings` (
  `setting_id` bigint NOT NULL,
  `setting` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` int NOT NULL DEFAULT '0',
  `category` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other_settings',
  `field_attributes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `settings_order` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_settings`
--

INSERT INTO `gr_settings` (`setting_id`, `setting`, `options`, `value`, `required`, `category`, `field_attributes`, `settings_order`, `updated_on`) VALUES
(1, 'site_name', '0', 'Site Name', 1, 'general_settings', NULL, 1, '2022-01-20 14:28:51'),
(2, 'site_description', 'textarea', 'Share what is on your mind with other people from all around the world to find new friends.', 1, 'general_settings', NULL, 2, '2022-01-27 14:02:45'),
(3, 'site_slogan', '0', 'Your Slogan Goes Here', 1, 'general_settings', NULL, 3, '2022-01-20 14:29:04'),
(4, 'meta_title', '0', 'Meet New Friends on Site Name', 1, 'general_settings', NULL, 4, '2022-01-20 23:41:46'),
(5, 'default_timezone', 'select', 'Asia/Kolkata', 1, 'general_settings', NULL, 8, '2023-05-12 05:00:31'),
(6, 'captcha', '[\"disable\",\"hcaptcha\",\"google_recaptcha_v2\",\"cloudflare_turnstile\"]', 'disable', 0, 'login_settings', NULL, 8, '2023-04-06 06:05:27'),
(7, 'captcha_site_key', '0', '', 0, 'login_settings', NULL, 9, '2023-04-06 06:05:27'),
(8, 'captcha_secret_key', '0', '', 0, 'login_settings', NULL, 10, '2023-04-06 06:05:27'),
(9, 'default_language', 'select', '1', 1, 'general_settings', NULL, 9, '2022-02-15 09:51:46'),
(10, 'guest_login', '[\"enable\",\"disable\"]', 'enable', 0, 'login_settings', NULL, 2, '2022-01-21 01:37:18'),
(11, 'sender_name', '0', 'Site Name', 0, 'email_settings', NULL, 7, '2022-01-20 23:44:27'),
(12, 'user_email_verification', '[\"enable\",\"disable\"]', 'disable', 0, 'login_settings', NULL, 4, '2024-02-02 01:53:53'),
(13, 'smtp_authentication', '[\"enable\",\"disable\"]', 'disable', 0, 'email_settings', NULL, 1, '2024-03-16 14:46:21'),
(14, 'smtp_host', '0', '', 0, 'email_settings', NULL, 3, '2024-03-16 14:46:21'),
(15, 'system_email_address', '0', 'email@systemdomain.test', 0, 'email_settings', NULL, 2, '2024-03-16 14:46:35'),
(16, 'smtp_username', '0', '', 0, 'email_settings', NULL, 4, '2024-03-16 14:46:21'),
(17, 'smtp_password', '0', '', 0, 'email_settings', NULL, 5, '2024-03-16 14:46:21'),
(18, 'smtp_protocol', '[\"ssl\",\"tls\"]', 'tls', 0, 'email_settings', NULL, 6, '2022-01-20 23:44:34'),
(19, 'smtp_port', '[25,587,465,2525]', '587', 0, 'email_settings', NULL, 8, '2021-09-23 00:50:10'),
(20, 'gif_search_engine', '[\"disable\",\"tenor\",\"gfycat\",\"giphy\"]', 'disable', 0, 'message_settings', NULL, 5, '2024-05-15 00:26:27'),
(21, 'gif_search_engine_api', '0', '', 0, 'message_settings', NULL, 6, '2024-05-15 00:26:27'),
(22, 'gifs_per_load', 'number', '25', 0, 'message_settings', NULL, 7, '2021-07-22 15:56:00'),
(23, 'records_per_call', 'number', '25', 1, 'general_settings', NULL, 10, '2024-05-06 12:42:44'),
(24, 'messages_per_call', 'number', '25', 1, 'message_settings', NULL, 1, '2021-06-23 15:52:17'),
(25, 'refresh_rate', 'number', '3000', 1, 'realtime_settings', NULL, 3, '2024-04-30 23:55:43'),
(26, 'request_timeout', 'number', '1', 1, 'realtime_settings', NULL, 4, '2023-09-08 10:32:05'),
(27, 'minimum_message_length', 'number', '1', 1, 'message_settings', NULL, 12, '2022-01-21 23:45:15'),
(28, 'maximum_message_length', 'number', '2000', 1, 'message_settings', NULL, 13, '2022-01-23 14:59:36'),
(29, 'read_more_criteria', 'number', '250', 1, 'message_settings', NULL, 14, '2022-01-23 23:34:17'),
(30, 'force_https', '[\"yes\",\"no\"]', 'no', 0, 'general_settings', NULL, 11, '2022-01-20 15:33:03'),
(31, 'non_latin_usernames', '[\"enable\",\"disable\"]', 'enable', 0, 'login_settings', NULL, 3, '2021-06-23 15:52:17'),
(32, 'system_messages_groups', '[multi_select][\"on_group_creation\",\"on_join_group_chat\",\"on_removal_from_group\",\"on_leaving_group_chat\",\"on_awarding_group_badges\",\"on_getting_banned_from_group\",\"on_getting_unbanned_from_group\",\"on_getting_temporarily_banned_from_group\",\"on_updating_group_info\",\"when_changing_group_role\"]', 'a:10:{i:0;s:17:\"on_group_creation\";i:1;s:18:\"on_join_group_chat\";i:2;s:21:\"on_removal_from_group\";i:3;s:21:\"on_leaving_group_chat\";i:4;s:24:\"on_awarding_group_badges\";i:5;s:28:\"on_getting_banned_from_group\";i:6;s:30:\"on_getting_unbanned_from_group\";i:7;s:40:\"on_getting_temporarily_banned_from_group\";i:8;s:22:\"on_updating_group_info\";i:9;s:24:\"when_changing_group_role\";}', 0, 'notification_settings', NULL, 11, '2024-05-15 00:28:07'),
(33, 'site_notifications', '[multi_select][\"on_user_mention_group_chat\",\"on_group_invitation\",\"on_new_site_badges\",\"on_reply_group_messages\"]', 'a:4:{i:0;s:26:\"on_user_mention_group_chat\";i:1;s:19:\"on_group_invitation\";i:2;s:18:\"on_new_site_badges\";i:3;s:23:\"on_reply_group_messages\";}', 0, 'notification_settings', NULL, 10, '2024-05-15 00:28:07'),
(34, 'login_cookie_validity', 'number', '30', 0, 'login_settings', NULL, 6, '2022-10-28 11:58:43'),
(35, 'gravatar', '[\"enable\",\"disable\"]', 'disable', 0, 'login_settings', NULL, 20, '2021-06-23 15:52:17'),
(36, 'minimum_username_length', 'number', '4', 0, 'login_settings', NULL, 10, '2021-06-23 15:52:17'),
(37, 'maximum_username_length', 'number', '30', 0, 'login_settings', NULL, 11, '2021-06-23 15:52:17'),
(38, 'group_join_confirmation', '[\"enable\",\"disable\"]', 'enable', 0, 'general_settings', NULL, 12, '2022-01-20 15:38:52'),
(39, 'view_groups_without_login', '[\"enable\",\"disable\"]', 'enable', 0, 'general_settings', NULL, 13, '2024-05-04 16:39:37'),
(40, 'api_secret_key', '0', 'XuGbAWD3esdsdQ', 0, 'general_settings', NULL, 14, '2024-05-15 00:23:46'),
(41, 'dateformat', '[\"dmy_format\",\"mdy_format\",\"ymd_format\"]', 'dmy_format', 0, 'general_settings', NULL, 15, '2023-08-22 22:47:08'),
(42, 'autoplay_audio_player', '[\"yes\",\"no\"]', 'no', 0, 'general_settings', NULL, 16, '2022-04-02 20:10:21'),
(43, 'cookie_consent', '[\"enable\",\"disable\"]', 'enable', 0, 'general_settings', NULL, 17, '2022-01-20 21:58:26'),
(44, 'notification_tone', 'select', 'assets/files/sound_notifications/case-closed.mp3', 1, 'realtime_settings', NULL, 5, '2024-04-30 23:55:43'),
(45, 'google_analytics_id', '0', '', 0, 'general_settings', NULL, 18, '2022-01-20 22:29:56'),
(46, 'time_format', '[\"24_format\",\"12_format\"]', '12_format', 1, 'general_settings', NULL, 19, '2022-06-27 01:23:34'),
(47, 'on_load_guest_login_window', '[\"enable\",\"disable\"]', 'disable', 0, 'login_settings', NULL, 23, '2022-01-21 02:48:09'),
(48, 'color_scheme', '[\"light_mode\",\"dark_mode\"]', 'dark_mode', 1, 'general_settings', NULL, 20, '2024-05-04 14:37:00'),
(49, 'default_font', 'select', 'inter', 1, 'general_settings', NULL, 21, '2023-09-21 19:15:41'),
(50, 'ffmpeg', '[\"enable\",\"disable\"]', 'disable', 0, 'general_settings', NULL, 22, '2024-05-12 21:37:50'),
(51, 'ffmpeg_binaries_path', '0', '', 0, 'general_settings', NULL, 23, '2024-05-15 00:23:46'),
(52, 'profanity_filter', '[\"enable\",\"disable\",\"strict_mode\"]', 'enable', 0, 'other_settings', NULL, 0, '2022-06-14 13:24:38'),
(53, 'firewall', '[\"enable\",\"disable\"]', 'disable', 0, 'other_settings', NULL, 0, '2023-09-24 14:01:39'),
(54, 'maximum_login_attempts', 'number', '10', 0, 'login_settings', NULL, 26, '2021-06-23 15:52:17'),
(55, 'ffprobe_binaries_path', '0', '', 0, 'general_settings', NULL, 24, '2024-05-15 00:23:46'),
(56, 'user_registration', '[\"enable\",\"disable\"]', 'enable', 0, 'login_settings', NULL, 1, '2022-01-31 17:48:26'),
(57, 'push_notifications', '[\"disable\",\"webpushr\",\"onesignal\", \"firebase\"]', 'disable', 1, 'notification_settings', '{\"class\":\"field toggle_form_fields\",\"hide_field\":\"web_push_fields\",\"show_fields\":\"onesignal|onesignal,webpushr|webpushr,firebase|firebase\"}', 1, '2024-02-02 01:57:29'),
(58, 'send_push_notification', '[multi_select][\"on_private_message\",\"on_private_message_offline\",\"on_friend_request\",\"on_new_private_video_call\",\"on_user_mention_group_chat\",\"on_reply_group_messages\"]', 'a:5:{i:0;s:26:\"on_private_message_offline\";i:1;s:17:\"on_friend_request\";i:2;s:25:\"on_new_private_video_call\";i:3;s:26:\"on_user_mention_group_chat\";i:4;s:23:\"on_reply_group_messages\";}', 0, 'notification_settings', NULL, 8, '2024-05-15 00:28:07'),
(59, 'onesignal_app_id', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields onesignal d-none\"}', 2, '2023-09-24 14:06:58'),
(60, 'onesignal_rest_api_key', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields onesignal d-none\"}', 3, '2023-09-24 14:06:58'),
(61, 'onesignal_safari_web_id', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields onesignal d-none\"}', 4, '2022-03-21 01:16:35'),
(62, 'webpushr_public_key', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields webpushr d-none\"}', 5, '2023-11-07 00:01:02'),
(63, 'webpushr_rest_api_key', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields webpushr d-none\"}', 6, '2023-11-07 00:01:02'),
(64, 'webpushr_authentication_token', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields webpushr d-none\"}', 7, '2023-11-07 00:01:02'),
(65, 'progressive_web_application', '[\"enable\",\"disable\"]', 'disable', 0, 'pwa_settings', NULL, 1, '2024-05-10 03:03:20'),
(66, 'pwa_name', '', 'Site Name', 0, 'pwa_settings', NULL, 2, '2022-01-27 14:05:26'),
(67, 'pwa_short_name', '', 'Site Name', 0, 'pwa_settings', NULL, 3, '2022-01-27 14:05:26'),
(68, 'pwa_background_color', 'color', '#000000', 0, 'pwa_settings', NULL, 5, '2022-11-09 17:32:28'),
(69, 'pwa_theme_color', 'color', '#020202', 0, 'pwa_settings', NULL, 6, '2022-04-05 08:31:23'),
(70, 'pwa_description', 'textarea', 'Share what is on your mind with other people from all around the world to find new friends.', 0, 'pwa_settings', NULL, 4, '2022-01-27 14:05:26'),
(71, 'pwa_display', '[\"standalone\",\"minimal-ui\",\"fullscreen\",\"browser\"]', 'standalone', 0, 'pwa_settings', NULL, 7, '2022-01-27 14:05:26'),
(72, 'gif_content_filtering', '[\"high\",\"medium\",\"low\",\"off\"]', 'off', 0, 'message_settings', NULL, 8, '2022-04-11 13:32:19'),
(73, 'message_alignment', '[\"left\",\"right\"]', 'left', 1, 'message_settings', NULL, 2, '2022-10-28 03:29:26'),
(74, 'own_message_alignment', '[\"left\",\"right\"]', 'left', 1, 'message_settings', NULL, 3, '2024-05-08 13:31:50'),
(75, 'play_notification_sound', '[multi_select][\"on_new_message\",\"on_new_site_notification\",\"on_group_unread_count_change\",\"on_private_conversation_unread_count_change\"]', 'a:4:{i:0;s:14:\"on_new_message\";i:1;s:24:\"on_new_site_notification\";i:2;s:28:\"on_group_unread_count_change\";i:3;s:43:\"on_private_conversation_unread_count_change\";}', 0, 'realtime_settings', NULL, 6, '2024-05-15 01:05:38'),
(76, 'entry_page_background', '[\"slideshow\",\"static_image\"]', 'slideshow', 0, 'login_settings', NULL, 21, '2022-03-20 04:20:40'),
(77, 'change_to_idle_status_after', 'number', '3', 0, 'realtime_settings', NULL, 1, '2022-04-06 05:57:12'),
(78, 'change_to_offline_status_after', 'number', '5', 0, 'realtime_settings', NULL, 2, '2022-04-06 05:57:12'),
(79, 'chat_page_boxed_layout', '[\"enable\",\"disable\"]', 'disable', 0, 'general_settings', NULL, 26, '2023-09-07 13:32:44'),
(80, 'facebook_url', NULL, '#facebook', 0, 'other_settings', NULL, 0, '2022-03-19 10:05:13'),
(81, 'instagram_url', NULL, '#Instagram', 0, 'other_settings', NULL, 0, '2022-03-19 10:06:57'),
(82, 'twitter_url', NULL, '#Twitter', 0, 'other_settings', NULL, 0, '2022-03-20 04:38:57'),
(83, 'linkedin_url', NULL, '#LinkedIn', 0, 'other_settings', NULL, 0, '2022-03-19 10:06:57'),
(84, 'twitch_url', NULL, '#Twitch', 0, 'other_settings', NULL, 0, '2022-03-19 10:37:58'),
(85, 'landing_page', '[\"enable\",\"disable\"]', 'enable', 0, 'other_settings', NULL, 0, '2022-03-20 06:50:08'),
(86, 'enter_is_send', '[\"enable\",\"disable\"]', 'enable', 0, 'message_settings', NULL, 4, '2022-04-05 14:25:29'),
(87, 'disallowed_slugs', 'textarea', 'a:0:{}', 0, 'general_settings', NULL, 41, '2024-05-15 00:41:02'),
(88, 'mini_audio_player', '[\"enable\",\"disable\"]', 'enable', 0, 'general_settings', NULL, 27, '2022-04-05 15:51:38'),
(89, 'load_group_info_on_group_load', '[\"enable\",\"disable\"]', 'enable', 0, 'general_settings', NULL, 28, '2022-04-05 16:07:47'),
(90, 'new_user_approval', '[\"enable\",\"disable\"]', 'disable', 0, 'login_settings', NULL, 5, '2024-01-30 22:30:11'),
(91, 'custom_login_url', NULL, '', 0, 'login_settings', NULL, 24, '2022-04-08 07:34:54'),
(92, 'custom_url_on_logout', NULL, '', 0, 'login_settings', NULL, 25, '2022-04-08 07:24:11'),
(93, 'hero_section_animation', '[\"enable\",\"disable\"]', 'enable', 0, 'other_settings', NULL, 0, '2022-04-10 12:27:34'),
(94, 'hide_group_member_list_from_non_members', '[\"yes\",\"no\"]', 'no', 0, 'general_settings', NULL, 29, '2022-06-07 23:39:13'),
(95, 'display_full_file_name_of_attachments', '[\"yes\",\"no\"]', 'no', 0, 'general_settings', NULL, 30, '2023-04-23 14:09:05'),
(96, 'groups_section_status', '[\"enable\",\"disable\"]', 'enable', 0, 'other_settings', NULL, 0, '2022-06-10 10:15:37'),
(97, 'faq_section_status', '[\"enable\",\"disable\"]', 'enable', 0, 'other_settings', NULL, 0, '2022-06-10 10:15:37'),
(98, 'view_public_group_messages_non_member', '[\"enable\",\"disable\"]', 'enable', 0, 'general_settings', NULL, 32, '2022-06-13 10:38:30'),
(99, 'hide_name_field_in_registration_page', '[\"yes\",\"no\"]', 'no', 0, 'login_settings', NULL, 16, '2022-06-20 07:01:09'),
(100, 'hide_email_address_field_in_registration_page', '[\"yes\",\"no\"]', 'no', 0, 'login_settings', NULL, 17, '2022-11-18 07:02:41'),
(101, 'hide_username_field_in_registration_page', '[\"yes\",\"no\"]', 'no', 0, 'login_settings', NULL, 18, '2022-06-20 07:08:11'),
(102, 'image_moderation', '[\"disable\",\"sightengine\",\"google_cloud_vision\"]', 'disable', 0, 'moderation_settings', '{\"class\":\"field toggle_form_fields\",\"hide_field\":\"image_mod_fields\",\"show_fields\":\"sightengine|sightengine,google_cloud_vision|google_cloud_vision\"}', 5, '2024-02-02 01:56:35'),
(103, 'sightengine_api_user', NULL, '', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields sightengine d-none\"}', 6, '2023-09-24 14:06:32'),
(104, 'sightengine_api_secret', NULL, '', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields sightengine d-none\"}', 7, '2023-09-24 14:06:32'),
(105, 'image_removal_criteria', '[multi_select][\"partial_nudity\",\"explicit_nudity\",\"wad_content\",\"offensive_signs_gestures\",\"graphic_violence_gore\"]', 'a:5:{i:0;s:14:\"partial_nudity\";i:1;s:15:\"explicit_nudity\";i:2;s:11:\"wad_content\";i:3;s:24:\"offensive_signs_gestures\";i:4;s:21:\"graphic_violence_gore\";}', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields sightengine d-none\"}', 8, '2024-05-15 00:27:43'),
(106, 'minimum_score_required_partial_nudity', 'number', '40', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields sightengine d-none\"}', 9, '2022-06-23 00:06:20'),
(107, 'minimum_score_required_explicit_nudity', 'number', '60', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields sightengine d-none\"}', 10, '2022-06-23 00:06:20'),
(108, 'minimum_score_required_wad_content', 'number', '60', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields sightengine d-none\"}', 11, '2022-06-23 00:05:50'),
(109, 'minimum_score_required_offensive', 'number', '60', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields sightengine d-none\"}', 12, '2022-06-23 00:05:50'),
(110, 'minimum_score_required_graphic_violence_gore', 'number', '70', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields sightengine d-none\"}', 13, '2022-06-23 00:09:09'),
(111, 'minimum_full_name_length', 'number', '5', 0, 'login_settings', NULL, 14, '2022-10-28 22:52:51'),
(112, 'maximum_full_name_length', 'number', '30', 0, 'login_settings', NULL, 15, '2022-10-09 08:46:22'),
(113, 'friend_system', '[\"enable\",\"disable\"]', 'enable', 0, 'general_settings', NULL, 34, '2022-10-10 08:25:07'),
(114, 'display_username_group_chats', '[\"enable\",\"disable\"]', 'disable', 0, 'message_settings', NULL, 15, '2022-10-19 00:00:32'),
(115, 'display_username_private_chats', '[\"enable\",\"disable\"]', 'disable', 0, 'message_settings', NULL, 16, '2022-10-19 00:01:35'),
(116, 'compress_video_files', '[\"enable\",\"disable\"]', 'disable', 0, 'general_settings', NULL, 36, '2024-05-15 00:23:46'),
(117, 'compress_image_files', '[\"enable\",\"disable\"]', 'disable', 0, 'general_settings', NULL, 37, '2024-02-02 01:52:31'),
(118, 'compress_audio_files', '[\"enable\",\"disable\"]', 'disable', 0, 'general_settings', NULL, 38, '2024-05-15 00:23:46'),
(119, 'people_nearby_feature', '[\"enable\",\"disable\"]', 'enable', 0, 'general_settings', NULL, 31, '2023-08-22 22:59:16'),
(120, 'people_nearby_max_distance', 'number', '100', 0, 'general_settings', NULL, 33, '2022-10-21 01:36:56'),
(121, 'allow_guest_users_create_accounts', '[\"yes\",\"no\"]', 'yes', 0, 'login_settings', NULL, 22, '2022-10-21 19:07:01'),
(122, 'email_validator', '[\"enable\",\"disable\",\"strict_mode\"]', 'enable', 0, 'other_settings', NULL, 0, '2022-10-28 22:35:28'),
(123, 'show_timestamp_on_mouseover', '[\"enable\",\"disable\"]', 'enable', 0, 'message_settings', NULL, 17, '2022-10-24 18:24:22'),
(124, 'search_on_change_of_input', '[\"enable\",\"disable\"]', 'disable', 0, 'general_settings', NULL, 39, '2022-10-27 22:47:13'),
(125, 'show_side_navigation_on_load', '[\"yes\",\"no\"]', 'yes', 0, 'general_settings', NULL, 40, '2022-10-27 22:50:24'),
(126, 'cloud_storage', '[\"disable\", \"amazon_s3\",\"s3_compatible\"]', 'disable', 1, 'cloud_storage', '{\"class\":\"field toggle_form_fields\",\"hide_field\":\"cloud_storage_fields\",\"show_fields\":\"amazon_s3|s3_compatible,ftp_storage|ftp_storage,s3_compatible|s3_compatible\"}', 1, '2024-02-25 14:23:57'),
(127, 'cloud_storage_api_key', NULL, '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields s3_compatible d-none\"}', 2, '2024-02-25 14:23:57'),
(128, 'cloud_storage_secret_key', NULL, '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields s3_compatible d-none\"}', 3, '2024-02-25 14:23:57'),
(129, 'cloud_storage_region', NULL, '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields s3_compatible d-none\"}', 4, '2024-02-25 14:23:57'),
(130, 'cloud_storage_bucket_name', NULL, '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields s3_compatible d-none\"}', 5, '2024-02-25 14:23:57'),
(131, 'cloud_storage_endpoint', NULL, '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields s3_compatible d-none\"}', 6, '2024-02-25 14:23:57'),
(132, 'cloud_storage_ftp_host', NULL, '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields ftp_storage d-none\"}', 2, '2022-04-05 08:00:24'),
(133, 'cloud_storage_ftp_username', NULL, '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields ftp_storage d-none\"}', 3, '2022-04-05 08:00:24'),
(134, 'cloud_storage_ftp_password', NULL, '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields ftp_storage d-none\"}', 4, '2022-04-05 08:00:24'),
(135, 'cloud_storage_ftp_port', 'number', '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields ftp_storage d-none\"}', 5, '2022-04-05 08:00:24'),
(136, 'cloud_storage_ftp_path', '', '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields ftp_storage d-none\"}', 6, '2022-04-05 08:00:24'),
(137, 'cloud_storage_ftp_endpoint', '', '', 0, 'cloud_storage', '{\"class\":\"field cloud_storage_fields ftp_storage d-none\"}', 7, '2022-04-05 08:00:24'),
(138, 'hide_phone_number_field_in_registration_page', '[\"yes\",\"no\"]', 'no', 0, 'login_settings', NULL, 19, '2022-06-20 07:08:11'),
(139, 'phone_number_verification', '[\"enable\",\"disable\"]', 'disable', 0, 'sms_settings', NULL, 1, '2023-04-23 01:00:37'),
(140, 'sms_gateway', '[\"disable\",\"twilio\",\"messagebird\"]', 'disable', 0, 'sms_settings', '{\"class\":\"field toggle_form_fields\",\"hide_field\":\"sms_gateway_fields\",\"show_fields\":\"twilio|twilio,messagebird|messagebird\"}', 2, '2023-09-24 14:04:29'),
(141, 'twilio_account_sid', '', '', 0, 'sms_settings', '{\"class\":\"field sms_gateway_fields twilio d-none\"}', 3, '2023-04-05 02:57:50'),
(142, 'twilio_auth_token', '', '', 0, 'sms_settings', '{\"class\":\"field sms_gateway_fields twilio d-none\"}', 4, '2023-04-05 02:57:50'),
(143, 'sms_src', '', '', 0, 'sms_settings', NULL, 6, '2023-04-05 02:57:50'),
(144, 'messagebird_api_key', '', '', 0, 'sms_settings', '{\"class\":\"field sms_gateway_fields messagebird d-none\"}', 5, '2023-04-05 02:57:50'),
(145, 'ip_intelligence', '[\"disable\",\"proxycheck_io\",\"getipintel\"]', 'disable', 0, 'ip_intelligence', '', 1, '2023-04-11 15:26:42'),
(146, 'ip_intelligence_api_key', NULL, '', 0, 'ip_intelligence', '', 2, '2023-04-11 14:07:20'),
(147, 'ip_intelligence_probability', '', '50', 0, 'ip_intelligence', '', 3, '2023-04-11 14:59:50'),
(148, 'ip_intel_validate_on_login', '[\"yes\",\"no\"]', 'yes', 0, 'ip_intelligence', NULL, 4, '2023-04-11 14:33:20'),
(149, 'ip_intel_validate_on_register', '[\"yes\",\"no\"]', 'yes', 0, 'ip_intelligence', NULL, 5, '2023-04-11 14:33:20'),
(150, 'minimum_guest_nickname_length', 'number', '5', 0, 'login_settings', NULL, 12, '2022-10-28 22:52:51'),
(151, 'maximum_guest_nickname_length', 'number', '30', 0, 'login_settings', NULL, 13, '2022-10-09 08:46:22'),
(152, 'adblock_detector', '[\"enable\",\"disable\"]', 'disable', 0, 'general_settings', NULL, 35, '2024-05-15 00:23:46'),
(153, 'footer_section_status', '[\"enable\",\"disable\"]', 'enable', 0, 'other_settings', NULL, 0, '2023-04-15 16:43:37'),
(154, 'profanity_filter_username', '[\"enable\",\"disable\",\"strict_mode\"]', 'enable', 0, 'other_settings', NULL, 0, '2023-04-17 06:33:25'),
(155, 'profanity_filter_full_name', '[\"enable\",\"disable\",\"strict_mode\"]', 'enable', 0, 'other_settings', NULL, 0, '2023-04-17 06:33:25'),
(156, 'enable_photo_upload_on_signup', '[\"yes\",\"no\",\"required\"]', 'yes', 0, 'login_settings', NULL, 7, '2023-04-22 04:25:54'),
(157, 'send_email_notification', '[multi_select][\"on_private_message_offline\",\"on_friend_request\", \"on_new_user_pending_approval\"]', 'a:1:{i:0;s:28:\"on_new_user_pending_approval\";}', 0, 'notification_settings', NULL, 9, '2024-05-15 00:28:07'),
(158, 'smtp_self_signed_certificate', '[\"yes\",\"no\"]', 'no', 0, 'email_settings', NULL, 9, '2023-04-23 11:29:27'),
(159, 'cloud_storage_public_url', NULL, '', 0, 'cloud_storage', '', 8, '2024-02-01 15:46:07'),
(160, 'show_profile_on_pm_open', '[\"enable\",\"disable\"]', 'enable', 0, 'general_settings', NULL, 25, '2023-08-22 15:05:23'),
(161, 'video_chat', '[\"disable\",\"agora\",\"live_kit\"]', 'disable', 0, 'video_audio_chat', '{\"class\":\"field toggle_form_fields\",\"hide_field\":\"video_chat_fields\",\"show_fields\":\"twilio|twilio,agora|agora,live_kit|live_kit\"}', 1, '2024-05-15 00:27:27'),
(162, 'vc_twilio_account_sid', '', '', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields twilio d-none\"}', 3, '2024-02-02 01:55:53'),
(163, 'vc_twilio_auth_token', '', '', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields twilio d-none\"}', 4, '2024-02-02 01:55:53'),
(164, 'vc_twilio_api_key', '', '', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields twilio d-none\"}', 5, '2024-02-02 01:55:53'),
(165, 'vc_twilio_api_secret_key', '', '', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields twilio d-none\"}', 6, '2024-02-02 01:55:53'),
(166, 'vc_agora_app_id', '', '', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields agora d-none\"}', 7, '2024-05-15 00:27:16'),
(167, 'vc_agora_app_certificate', '', '', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields agora d-none\"}', 8, '2024-05-15 00:27:16'),
(168, 'memberships', '[\"enable\",\"disable\"]', 'enable', 0, 'memberships', '', 1, '2023-10-01 20:56:25'),
(169, 'invoice_from', '', 'Company Name', 0, 'memberships', '', 2, '2023-09-08 13:25:09'),
(170, 'company_address', 'textarea', 'Company Address,\r\n973 Cordie Fort,\r\nSouth Carolina,\r\nEast Darrick,\r\n24212-8873', 0, 'memberships', '', 2, '2023-09-17 19:41:21'),
(171, 'invoice_footer', '', 'Thank you for your business!', 0, 'memberships', '', 2, '2023-09-08 13:25:09'),
(172, 'default_currency', 'select', 'USD', 0, 'memberships', '', 2, '2024-01-20 22:40:47'),
(173, 'default_currency_symbol', '', '$', 0, 'memberships', '', 2, '2023-09-18 13:59:23'),
(174, 'site_terms_conditions', 'select', '2', 0, 'login_settings', NULL, 3, '2023-09-21 00:09:09'),
(175, 'link_filter', '[\"enable\",\"disable\",\"strict_mode\"]', 'disable', 0, 'other_settings', NULL, 0, '2024-05-15 00:21:06'),
(176, 'disable_right_click', '[\"yes\",\"no\"]', 'no', 0, 'general_settings', NULL, 18, '2023-09-21 20:07:02'),
(177, 'live_kit_url', '', '', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields live_kit d-none\"}', 5, '2024-05-15 00:27:16'),
(178, 'live_kit_api_key', '', '', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields live_kit d-none\"}', 5, '2024-05-15 00:27:16'),
(179, 'live_kit_secret_key', '', '', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields live_kit d-none\"}', 5, '2024-05-15 00:27:16'),
(180, 'fb_apikey', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields firebase d-none\"}', 2, '2024-02-02 01:57:29'),
(181, 'fb_authdomain', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields firebase d-none\"}', 2, '2024-02-02 01:57:29'),
(182, 'fb_projectid', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields firebase d-none\"}', 2, '2024-02-02 01:57:29'),
(183, 'fb_messagingsenderid', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields firebase d-none\"}', 2, '2024-02-02 01:57:29'),
(184, 'fb_appid', NULL, '', 0, 'notification_settings', '{\"class\":\"field web_push_fields firebase d-none\"}', 2, '2024-02-02 01:57:29'),
(185, 'firebase_private_key', NULL, 'firebase_pk_KLxubkqFfC.json', 0, 'notification_settings', '{\"class\":\"field filebrowse web_push_fields firebase d-none\"}', 2, '2023-11-05 00:10:03'),
(186, 'audio_chat', '[\"enable\",\"disable\"]', 'enable', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields live_kit agora d-none\"}', 1, '2024-04-20 12:00:18'),
(187, 'send_welcome_email', '[\"enable\",\"disable\"]', 'disable', 0, 'login_settings', NULL, 2, '2022-01-21 01:37:18'),
(188, 'google_cloud_vision_api_key', NULL, '', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields google_cloud_vision d-none\"}', 14, '2024-01-31 02:00:21'),
(189, 'gcv_image_removal_criteria', '[multi_select][\"adult_content\",\"spoof_content\",\"medical_content\",\"violence_content\",\"racy_content\"]', 'a:5:{i:0;s:13:\"adult_content\";i:1;s:13:\"spoof_content\";i:2;s:15:\"medical_content\";i:3;s:16:\"violence_content\";i:4;s:12:\"racy_content\";}', 0, 'moderation_settings', '{\"class\":\"field image_mod_fields google_cloud_vision d-none\"}', 15, '2024-05-15 00:27:43'),
(190, 'text_moderation', '[\"disable\",\"perspective_api\"]', 'disable', 0, 'moderation_settings', '{\"class\":\"field toggle_form_fields\",\"hide_field\":\"text_mod_fields\",\"show_fields\":\"perspective_api|perspective_api\"}', 1, '2024-04-23 16:20:45'),
(191, 'perspective_google_cloud_api_key', NULL, '', 0, 'moderation_settings', '{\"class\":\"field text_mod_fields perspective_api d-none\"}', 2, '2024-04-23 16:20:45'),
(192, 'perspective_api_threshold', 'float', '0.85', 0, 'moderation_settings', '{\"class\":\"field text_mod_fields perspective_api d-none\"}', 3, '2024-01-31 13:54:55'),
(193, 'perspectiveapi_text_removal_criteria', '[multi_select][\"toxicity\",\"severe_toxicity\",\"profanity\",\"threat\",\"identity_attack\",\"insult\"]', 'a:6:{i:0;s:8:\"toxicity\";i:1;s:15:\"severe_toxicity\";i:2;s:9:\"profanity\";i:3;s:6:\"threat\";i:4;s:15:\"identity_attack\";i:5;s:6:\"insult\";}', 0, 'moderation_settings', '{\"class\":\"field text_mod_fields perspective_api d-none\"}', 4, '2024-05-15 00:27:43'),
(194, 'show_group_members_count', '[\"enable\",\"disable\"]', 'disable', 0, 'other_settings', NULL, 0, '2024-01-31 20:52:10'),
(195, 'screen_sharing', '[\"enable\",\"disable\"]', 'enable', 0, 'video_audio_chat', '{\"class\":\"field video_chat_fields live_kit agora d-none\"}', 1, '2024-04-23 21:46:01'),
(196, 'categorize_groups', '[\"yes\",\"no\"]', 'yes', 0, 'general_settings', NULL, 5, '2024-05-15 00:41:02'),
(197, 'no_of_groups_landing_page', 'number', '40', 1, 'other_settings', NULL, 7, '2024-05-02 00:12:58'),
(198, 'packages_section_status', '[\"enable\",\"disable\"]', 'enable', 0, 'other_settings', NULL, 0, '2024-05-04 14:54:15'),
(199, 'require_billing_address', '[\"yes\",\"no\"]', 'no', 0, 'memberships', '', 1, '2024-05-07 14:06:19'),
(200, 'under_maintenance_mode', '[\"enable\",\"disable\"]', 'disable', 0, 'general_settings', NULL, 6, '2024-05-06 13:11:20'),
(201, 'maintenance_mode_access_code', '0', 'keoxsYb7', 0, 'general_settings', NULL, 7, '2024-05-06 13:11:20'),
(202, 'normalize_text_message', '[\"yes\",\"no\"]', 'no', 0, 'message_settings', NULL, 4, '2024-05-15 00:26:27'),
(203, 'video_search_engine', '[\"disable\",\"youtube\"]', 'disable', 0, 'message_settings', NULL, 9, '2024-05-15 00:26:27'),
(204, 'video_search_engine_api', '0', '', 0, 'message_settings', NULL, 10, '2024-05-15 00:26:27'),
(205, 'videos_per_load', 'number', '25', 0, 'message_settings', NULL, 11, '2021-07-22 15:56:00'),
(206, 'message_text_formatting', '[\"enable\",\"disable\"]', 'enable', 0, 'message_settings', NULL, 4, '2024-05-13 01:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `gr_site_advertisements`
--

CREATE TABLE `gr_site_advertisements` (
  `site_advert_id` bigint NOT NULL,
  `site_advert_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_advert_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_advert_placement` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_advert_min_height` int DEFAULT NULL,
  `site_advert_max_height` int NOT NULL DEFAULT '150',
  `disabled` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_site_notifications`
--

CREATE TABLE `gr_site_notifications` (
  `notification_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `notification_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `related_group_id` bigint DEFAULT NULL,
  `related_user_id` bigint DEFAULT NULL,
  `related_message_id` bigint DEFAULT NULL,
  `related_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `read_status` int NOT NULL DEFAULT '0' COMMENT '0 = Unread\r\n1 = Read',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_site_roles`
--

CREATE TABLE `gr_site_roles` (
  `site_role_id` bigint NOT NULL,
  `string_constant` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_role_attribute` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_hierarchy` int NOT NULL DEFAULT '1',
  `disabled` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_site_roles`
--

INSERT INTO `gr_site_roles` (`site_role_id`, `string_constant`, `permissions`, `site_role_attribute`, `role_hierarchy`, `disabled`, `updated_on`) VALUES
(1, 'site_role_1', '{\"update\":\"site_roles\",\"name_color\":\"#DD8400\",\"role_hierarchy\":\"1\",\"disabled\":\"no\",\"load_profile_on_page_load\":\"\",\"left_panel_content_on_page_load\":\"\",\"main_panel_content_on_page_load\":\"\",\"hide_groups_on_group_url\":\"no\",\"skip_text_moderation\":\"no\",\"skip_image_moderation\":\"no\",\"default_group_visibility\":\"\",\"group_create_limit\":\"500\",\"default_group_category\":\"\",\"pm_only_specific_roles\":\"no\",\"group_join_limit\":\"100\",\"flood_control_time_difference\":\"20\",\"daily_send_limit_group_messages\":\"0\",\"daily_send_limit_private_messages\":\"0\",\"edit_message_time_limit\":\"10\",\"delete_message_time_limit\":\"10\",\"max_file_upload_size\":\"500\",\"maximum_storage_space\":\"500\"}', 'unverified_users', 1, 0, '2024-05-15 00:31:21'),
(2, 'site_role_2', '{\"update\":\"site_roles\",\"name_color\":\"#F06292\",\"role_hierarchy\":\"5\",\"disabled\":\"no\",\"load_profile_on_page_load\":\"yes\",\"left_panel_content_on_page_load\":\"group_categories\",\"main_panel_content_on_page_load\":\"statistics\",\"site_notifications\":[\"view\",\"delete\"],\"hide_groups_on_group_url\":\"no\",\"skip_text_moderation\":\"no\",\"skip_image_moderation\":\"no\",\"groups\":[\"view_public_groups\",\"view_secret_groups\",\"view_password_protected_groups\",\"view_joined_groups\",\"create_groups\",\"create_unleavable_group\",\"create_secret_group\",\"create_protected_group\",\"create_video_chat_groups\",\"create_audio_chat_groups\",\"video_chat\",\"audio_chat\",\"set_group_slug\",\"set_group_category\",\"pin_groups\",\"set_auto_join_groups\",\"set_participant_settings\",\"set_default_group_role_within_group\",\"add_meta_tags\",\"set_cover_pic\",\"set_custom_background\",\"download_attachments\",\"typing_indicator\",\"mention_users\",\"mention_everyone\",\"reply_messages\",\"forward_messages\",\"check_read_receipts\",\"join_group\",\"leave_group\",\"invite_users\",\"add_site_members\",\"view_reactions\",\"react_messages\",\"send_message\",\"send_audio_message\",\"attach_files\",\"attach_from_storage\",\"attach_gifs\",\"search_share_videos\",\"attach_stickers\",\"share_screenshot\",\"preview_pdf_files\",\"share_location\",\"allow_sharing_links\",\"allow_sharing_email_addresses\",\"generate_link_preview\",\"clear_chat_history\",\"export_chat\",\"embed_group\",\"send_as_another_user\",\"super_privileges\"],\"default_group_visibility\":\"visible\",\"group_create_limit\":\"10000\",\"default_group_category\":\"\",\"friend_system\":[\"view_friends\",\"send_requests\",\"receive_requests\"],\"private_conversations\":[\"super_privileges\",\"initiate_private_chat\",\"view_private_chats\",\"video_chat\",\"audio_chat\",\"send_message\",\"message_non_friends\",\"send_audio_message\",\"view_reactions\",\"react_messages\",\"attach_files\",\"attach_from_storage\",\"attach_gifs\",\"search_share_videos\",\"attach_stickers\",\"share_screenshot\",\"preview_pdf_files\",\"share_location\",\"allow_sharing_links\",\"allow_sharing_email_addresses\",\"generate_link_preview\",\"typing_indicator\",\"reply_messages\",\"check_read_receipts\",\"edit_own_message\",\"delete_own_message\",\"download_attachments\",\"export_chat\",\"clear_chat_history\"],\"pm_only_specific_roles\":\"no\",\"pm_restricted_roles\":[\"1\",\"2\",\"3\",\"5\"],\"group_join_limit\":\"99999999\",\"flood_control_time_difference\":\"2\",\"daily_send_limit_group_messages\":\"0\",\"daily_send_limit_private_messages\":\"0\",\"edit_message_time_limit\":\"10\",\"delete_message_time_limit\":\"99999999999999\",\"storage\":[\"access_storage\",\"upload_files\",\"download_files\",\"delete_files\",\"super_privileges\"],\"max_file_upload_size\":\"5000\",\"maximum_storage_space\":\"5000\",\"allowed_file_formats\":[\"image_files\",\"video_files\",\"audio_files\",\"documents\",\"all_file_formats\"],\"site_users\":[\"block_users\",\"ignore_users\",\"create_user\",\"import_users\",\"generate_fake_users\",\"set_fake_online_users\",\"edit_users\",\"delete_users\",\"approve_users\",\"ban_users_from_site\",\"unban_users_from_site\",\"view_site_users\",\"view_online_users\",\"view_nearby_users\",\"view_invisible_users\",\"ban_ip_addresses\",\"unban_ip_addresses\",\"manage_user_access_logs\",\"login_as_another_user\",\"advanced_user_searches\"],\"profile\":[\"edit_profile\",\"change_full_name\",\"change_username\",\"change_email_address\",\"change_password\",\"change_avatar\",\"set_timezone\",\"upload_custom_avatar\",\"set_cover_pic\",\"set_custom_background\",\"go_offline\",\"view_email_address\",\"view_phone_number\",\"view_profile_url\",\"switch_languages\",\"switch_color_scheme\",\"disable_private_messages\",\"deactivate_account\",\"delete_account\"],\"site_roles\":[\"create\",\"view\",\"edit\",\"delete\"],\"memberships\":[\"view_membership_info\",\"enroll_membership\",\"view_personal_transactions\",\"download_invoice\",\"view_site_transactions\",\"edit_site_transactions\"],\"bank_transfer_receipts\":[\"view\",\"validate\",\"delete\"],\"membership_packages\":[\"create\",\"view\",\"edit\",\"delete\"],\"group_roles\":[\"create\",\"view\",\"edit\",\"delete\"],\"custom_fields\":[\"create\",\"view\",\"edit\",\"delete\"],\"stickers\":[\"create\",\"view\",\"edit\",\"delete\"],\"custom_pages\":[\"create\",\"view\",\"edit\",\"delete\"],\"custom_menu\":[\"create\",\"view\",\"edit\",\"delete\"],\"avatars\":[\"upload\",\"view\",\"delete\"],\"languages\":[\"create\",\"view\",\"edit\",\"delete\",\"export\"],\"social_login_providers\":[\"add\",\"view\",\"edit\",\"delete\"],\"audio_player\":[\"listen_music\",\"add\",\"view\",\"edit\",\"delete\"],\"site_adverts\":[\"create\",\"view\",\"edit\",\"delete\"],\"badges\":[\"assign\",\"create\",\"view\",\"edit\",\"delete\"],\"complaints\":[\"report\",\"track_status\",\"review_complaints\",\"delete_complaints\"],\"super_privileges\":[\"monitor_group_chats\",\"monitor_private_chats\",\"view_statistics\",\"core_settings\",\"manage_group_categories\",\"manage_payment_gateways\",\"customizer\",\"slideshows\",\"group_headers\",\"header_footer\",\"firewall\",\"email_validator\",\"link_filter\",\"profanity_filter\",\"message_scheduler\",\"cron_jobs\"]}', 'administrators', 5, 0, '2024-05-15 00:32:44'),
(3, 'site_role_3', '{\"update\":\"site_roles\",\"name_color\":\"#00B107\",\"role_hierarchy\":\"4\",\"disabled\":\"no\",\"load_profile_on_page_load\":\"yes\",\"left_panel_content_on_page_load\":\"group_categories\",\"main_panel_content_on_page_load\":\"welcome_screen\",\"site_notifications\":[\"view\",\"delete\"],\"hide_groups_on_group_url\":\"no\",\"skip_text_moderation\":\"no\",\"skip_image_moderation\":\"no\",\"groups\":[\"view_public_groups\",\"view_password_protected_groups\",\"view_joined_groups\",\"video_chat\",\"audio_chat\",\"download_attachments\",\"typing_indicator\",\"mention_users\",\"reply_messages\",\"forward_messages\",\"check_read_receipts\",\"join_group\",\"leave_group\",\"invite_users\",\"view_reactions\",\"react_messages\",\"send_message\",\"send_audio_message\",\"attach_files\",\"attach_from_storage\",\"attach_gifs\",\"search_share_videos\",\"attach_stickers\",\"share_screenshot\",\"preview_pdf_files\",\"share_location\",\"allow_sharing_links\",\"allow_sharing_email_addresses\",\"generate_link_preview\",\"clear_chat_history\",\"export_chat\",\"embed_group\"],\"default_group_visibility\":\"hidden\",\"group_create_limit\":\"10\",\"default_group_category\":\"\",\"friend_system\":[\"view_friends\",\"send_requests\",\"receive_requests\"],\"private_conversations\":[\"initiate_private_chat\",\"view_private_chats\",\"video_chat\",\"audio_chat\",\"send_message\",\"send_audio_message\",\"view_reactions\",\"react_messages\",\"attach_files\",\"attach_from_storage\",\"attach_gifs\",\"search_share_videos\",\"attach_stickers\",\"share_screenshot\",\"preview_pdf_files\",\"share_location\",\"allow_sharing_links\",\"allow_sharing_email_addresses\",\"generate_link_preview\",\"typing_indicator\",\"reply_messages\",\"check_read_receipts\",\"edit_own_message\",\"delete_own_message\",\"download_attachments\",\"export_chat\",\"clear_chat_history\"],\"pm_only_specific_roles\":\"no\",\"group_join_limit\":\"100\",\"flood_control_time_difference\":\"2\",\"daily_send_limit_group_messages\":\"0\",\"daily_send_limit_private_messages\":\"0\",\"edit_message_time_limit\":\"10\",\"delete_message_time_limit\":\"10\",\"storage\":[\"access_storage\",\"upload_files\",\"download_files\",\"delete_files\"],\"max_file_upload_size\":\"500\",\"maximum_storage_space\":\"1000\",\"allowed_file_formats\":[\"image_files\",\"video_files\",\"audio_files\",\"documents\"],\"site_users\":[\"block_users\",\"ignore_users\",\"view_online_users\",\"view_nearby_users\",\"advanced_user_searches\"],\"profile\":[\"edit_profile\",\"change_full_name\",\"change_username\",\"change_email_address\",\"change_password\",\"change_avatar\",\"set_timezone\",\"upload_custom_avatar\",\"set_cover_pic\",\"set_custom_background\",\"go_offline\",\"view_profile_url\",\"switch_languages\",\"switch_color_scheme\",\"disable_private_messages\",\"deactivate_account\"],\"memberships\":[\"view_membership_info\",\"enroll_membership\",\"view_personal_transactions\",\"download_invoice\"],\"audio_player\":[\"listen_music\"],\"complaints\":[\"report\",\"track_status\"]}', 'default_site_role', 4, 0, '2024-05-15 00:35:30'),
(4, 'site_role_4', '{\"update\":\"site_roles\",\"name_color\":\"#FF1100\",\"role_hierarchy\":\"1\",\"disabled\":\"no\",\"load_profile_on_page_load\":\"\",\"left_panel_content_on_page_load\":\"\",\"main_panel_content_on_page_load\":\"\",\"hide_groups_on_group_url\":\"\",\"skip_text_moderation\":\"\",\"skip_image_moderation\":\"\",\"default_group_visibility\":\"\",\"group_create_limit\":\"500\",\"default_group_category\":\"\",\"pm_only_specific_roles\":\"\",\"group_join_limit\":\"100\",\"flood_control_time_difference\":\"20\",\"daily_send_limit_group_messages\":\"0\",\"daily_send_limit_private_messages\":\"0\",\"edit_message_time_limit\":\"10\",\"delete_message_time_limit\":\"10\",\"max_file_upload_size\":\"500\",\"maximum_storage_space\":\"500\"}', 'banned_users', 1, 0, '2024-05-15 00:35:38'),
(5, 'site_role_5', '{\"update\":\"site_roles\",\"name_color\":\"#5D01FF\",\"role_hierarchy\":\"3\",\"disabled\":\"no\",\"load_profile_on_page_load\":\"yes\",\"left_panel_content_on_page_load\":\"group_categories\",\"main_panel_content_on_page_load\":\"welcome_screen\",\"site_notifications\":[\"view\"],\"hide_groups_on_group_url\":\"no\",\"skip_text_moderation\":\"no\",\"skip_image_moderation\":\"no\",\"groups\":[\"view_public_groups\",\"view_password_protected_groups\",\"view_joined_groups\",\"video_chat\",\"audio_chat\",\"download_attachments\",\"typing_indicator\",\"mention_users\",\"reply_messages\",\"forward_messages\",\"check_read_receipts\",\"join_group\",\"leave_group\",\"invite_users\",\"view_reactions\",\"react_messages\",\"send_message\",\"send_audio_message\",\"attach_files\",\"attach_from_storage\",\"attach_gifs\",\"search_share_videos\",\"attach_stickers\",\"share_screenshot\",\"preview_pdf_files\",\"share_location\",\"allow_sharing_links\",\"allow_sharing_email_addresses\",\"generate_link_preview\",\"clear_chat_history\",\"export_chat\",\"embed_group\"],\"default_group_visibility\":\"hidden\",\"group_create_limit\":\"500\",\"default_group_category\":\"\",\"private_conversations\":[\"initiate_private_chat\",\"view_private_chats\",\"video_chat\",\"audio_chat\",\"send_message\",\"message_non_friends\",\"send_audio_message\",\"view_reactions\",\"react_messages\",\"attach_files\",\"attach_from_storage\",\"attach_gifs\",\"search_share_videos\",\"attach_stickers\",\"share_screenshot\",\"preview_pdf_files\",\"share_location\",\"allow_sharing_links\",\"allow_sharing_email_addresses\",\"generate_link_preview\",\"typing_indicator\",\"reply_messages\",\"check_read_receipts\",\"delete_own_message\",\"download_attachments\",\"export_chat\",\"clear_chat_history\"],\"pm_only_specific_roles\":\"\",\"group_join_limit\":\"10\",\"flood_control_time_difference\":\"4\",\"daily_send_limit_group_messages\":\"0\",\"daily_send_limit_private_messages\":\"0\",\"edit_message_time_limit\":\"10\",\"delete_message_time_limit\":\"10\",\"storage\":[\"access_storage\",\"upload_files\",\"download_files\"],\"max_file_upload_size\":\"100\",\"maximum_storage_space\":\"500\",\"allowed_file_formats\":[\"image_files\",\"video_files\",\"audio_files\",\"documents\"],\"site_users\":[\"block_users\",\"ignore_users\",\"view_online_users\",\"view_nearby_users\"],\"profile\":[\"edit_profile\",\"change_full_name\",\"change_avatar\",\"set_timezone\",\"upload_custom_avatar\",\"set_cover_pic\",\"switch_languages\",\"switch_color_scheme\"],\"audio_player\":[\"listen_music\"],\"complaints\":[\"report\",\"track_status\"]}', 'guest_users', 3, 0, '2024-05-15 00:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `gr_site_users`
--

CREATE TABLE `gr_site_users` (
  `user_id` bigint NOT NULL,
  `display_name` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unknown',
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unverified_email_address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified` int NOT NULL DEFAULT '1',
  `verification_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `one_time_pin` int DEFAULT NULL,
  `otp_generated_on` datetime DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `encrypt_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'php_password_hash' COMMENT 'md5,php_password_hash OR encrypt_method_id',
  `salt` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_role_id` bigint NOT NULL DEFAULT '1',
  `previous_site_role_id` bigint NOT NULL DEFAULT '3',
  `profile_picture` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_bg_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_cover_pic` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `online_status` int NOT NULL DEFAULT '0' COMMENT '0 = Offline\r\n1 = Online\r\n2 = Idle',
  `stay_online` int NOT NULL DEFAULT '0',
  `approved` int NOT NULL DEFAULT '1',
  `geo_latitude` decimal(10,8) DEFAULT NULL,
  `geo_longitude` decimal(11,8) DEFAULT NULL,
  `total_friends` bigint DEFAULT '0',
  `social_login_provider_id` bigint DEFAULT NULL,
  `access_token` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_generated_on` datetime DEFAULT '1970-01-02 00:00:00',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `last_seen_on` datetime DEFAULT NULL,
  `last_login_session` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gr_site_users`
--

INSERT INTO `gr_site_users` (`user_id`, `display_name`, `username`, `email_address`, `unverified_email_address`, `phone_number`, `phone_verified`, `verification_code`, `one_time_pin`, `otp_generated_on`, `password`, `encrypt_type`, `salt`, `site_role_id`, `previous_site_role_id`, `profile_picture`, `profile_bg_image`, `profile_cover_pic`, `online_status`, `stay_online`, `approved`, `geo_latitude`, `geo_longitude`, `total_friends`, `social_login_provider_id`, `access_token`, `token_generated_on`, `created_on`, `updated_on`, `last_seen_on`, `last_login_session`) VALUES
(1, 'Site Admin', 'admin', 'email@yourtestdomain.com', NULL, '', 1, NULL, NULL, NULL, '$2y$10$lJHtW9b45HcTF0qpgrYQ0egOOshkL/UV6Szb86UGxY2zBlzrnPFf2', 'php_password_hash', '', 2, 2, 'assets/files/site_users/profile_pics/052024/1-gr-cqS2ox.jfif', NULL, 'assets/files/site_users/cover_pics/default.png', 0, 0, 1, '9.93123280', '76.26730410', 0, NULL, 'bzf0vuh3IC', '2023-03-31 11:25:12', '2023-11-11 08:23:17', '2024-05-15 01:05:39', '2024-05-15 01:05:45', '2024-05-14 07:27:17');

-- --------------------------------------------------------

--
-- Table structure for table `gr_site_users_blacklist`
--

CREATE TABLE `gr_site_users_blacklist` (
  `user_blacklist_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `blacklisted_user_id` bigint NOT NULL,
  `ignore` int DEFAULT '0' COMMENT '0 = False\r\n1 = True',
  `block` int NOT NULL DEFAULT '0' COMMENT '0 = False\r\n1 = True',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_site_users_device_logs`
--

CREATE TABLE `gr_site_users_device_logs` (
  `access_log_id` bigint NOT NULL,
  `login_session_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `ip_address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_site_users_membership`
--

CREATE TABLE `gr_site_users_membership` (
  `membership_info_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `membership_package_id` bigint DEFAULT NULL,
  `started_on` datetime NOT NULL,
  `expiring_on` datetime NOT NULL,
  `non_expiring` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_site_users_settings`
--

CREATE TABLE `gr_site_users_settings` (
  `user_setting_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `offline_mode` int NOT NULL DEFAULT '0',
  `time_zone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Australia/Sydney',
  `language_id` bigint DEFAULT NULL,
  `notification_tone` varchar(199) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `disable_private_messages` int NOT NULL DEFAULT '0',
  `deactivated` int DEFAULT '0',
  `color_scheme` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'default',
  `updated_on` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_social_login_providers`
--

CREATE TABLE `gr_social_login_providers` (
  `social_login_provider_id` int NOT NULL,
  `identity_provider` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_key` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secret_key` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_in_popup` int NOT NULL DEFAULT '1',
  `create_user` int NOT NULL DEFAULT '0' COMMENT '0 = Don''t Create\r\n1 = Create',
  `disabled` int NOT NULL DEFAULT '0',
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gr_typing_status`
--

CREATE TABLE `gr_typing_status` (
  `user_input_log_id` bigint NOT NULL,
  `group_id` bigint DEFAULT NULL,
  `private_conversation_id` bigint DEFAULT NULL,
  `user_id` bigint NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gr_audio_player`
--
ALTER TABLE `gr_audio_player`
  ADD PRIMARY KEY (`audio_content_id`),
  ADD KEY `idx__disabled` (`disabled`);

--
-- Indexes for table `gr_badges`
--
ALTER TABLE `gr_badges`
  ADD PRIMARY KEY (`badge_id`);

--
-- Indexes for table `gr_badges_assigned`
--
ALTER TABLE `gr_badges_assigned`
  ADD PRIMARY KEY (`badge_assigned_id`),
  ADD KEY `badge_id_fk` (`badge_id`),
  ADD KEY `user_id_fk_20` (`user_id`),
  ADD KEY `group_id_fk_8` (`group_id`);

--
-- Indexes for table `gr_bank_transfer_receipts`
--
ALTER TABLE `gr_bank_transfer_receipts`
  ADD PRIMARY KEY (`bank_transfer_receipt_id`),
  ADD KEY `membership_order_id_fk_1` (`membership_order_id`);

--
-- Indexes for table `gr_billing_address`
--
ALTER TABLE `gr_billing_address`
  ADD PRIMARY KEY (`billing_address_id`),
  ADD KEY `user_id_fk_26` (`user_id`);

--
-- Indexes for table `gr_complaints`
--
ALTER TABLE `gr_complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `user_id_fk_7` (`complainant_user_id`),
  ADD KEY `idx__complaint_status_complaint_id` (`complaint_status`,`complaint_id`);

--
-- Indexes for table `gr_cron_jobs`
--
ALTER TABLE `gr_cron_jobs`
  ADD PRIMARY KEY (`cron_job_id`);

--
-- Indexes for table `gr_css_variables`
--
ALTER TABLE `gr_css_variables`
  ADD PRIMARY KEY (`css_variable_id`);

--
-- Indexes for table `gr_custom_fields`
--
ALTER TABLE `gr_custom_fields`
  ADD PRIMARY KEY (`field_id`),
  ADD KEY `idx__type_id` (`field_id`);

--
-- Indexes for table `gr_custom_fields_values`
--
ALTER TABLE `gr_custom_fields_values`
  ADD PRIMARY KEY (`field_value_id`),
  ADD KEY `field_id_fk` (`field_id`),
  ADD KEY `user_id_fk_6` (`user_id`),
  ADD KEY `group_id_fk_4` (`group_id`),
  ADD KEY `idx__user_id_field_id` (`user_id`,`field_id`);

--
-- Indexes for table `gr_custom_menu_items`
--
ALTER TABLE `gr_custom_menu_items`
  ADD PRIMARY KEY (`menu_item_id`),
  ADD KEY `page_id_fk` (`page_id`),
  ADD KEY `idx__menu_item_order` (`menu_item_order`);

--
-- Indexes for table `gr_custom_pages`
--
ALTER TABLE `gr_custom_pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `gr_friends`
--
ALTER TABLE `gr_friends`
  ADD PRIMARY KEY (`friendship_id`),
  ADD KEY `user_id_fk_23` (`to_user_id`),
  ADD KEY `user_id_fk_22` (`from_user_id`) USING BTREE,
  ADD KEY `idx__friend_status` (`from_user_id`,`to_user_id`,`relation_status`);

--
-- Indexes for table `gr_groups`
--
ALTER TABLE `gr_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `idx__secret_group_suspended_pin_group` (`secret_group`,`suspended`,`pin_group`),
  ADD KEY `group_category_id_fk_1` (`group_category_id`),
  ADD KEY `group_role_id_fk_1` (`default_group_role`),
  ADD KEY `idx__group_id_group_category_id` (`group_id`,`group_category_id`),
  ADD KEY `idx__created_by` (`created_by`);

--
-- Indexes for table `gr_group_categories`
--
ALTER TABLE `gr_group_categories`
  ADD PRIMARY KEY (`group_category_id`);

--
-- Indexes for table `gr_group_invitations`
--
ALTER TABLE `gr_group_invitations`
  ADD PRIMARY KEY (`group_invitation_id`),
  ADD KEY `group_id_fk_6` (`group_id`),
  ADD KEY `user_id_fk_9` (`user_id`);

--
-- Indexes for table `gr_group_members`
--
ALTER TABLE `gr_group_members`
  ADD PRIMARY KEY (`group_member_id`),
  ADD KEY `group_id_fk` (`group_id`),
  ADD KEY `user_id_fk` (`user_id`),
  ADD KEY `group_role_id_fk` (`group_role_id`),
  ADD KEY `idx__user_id_group_id` (`user_id`,`group_id`),
  ADD KEY `idx__group_id_user_id` (`group_id`,`user_id`),
  ADD KEY `idx__user_id_last_read_message_id` (`user_id`,`last_read_message_id`),
  ADD KEY `idx__user_id_group_role_id` (`user_id`,`group_role_id`),
  ADD KEY `idx__last_read_message_id` (`last_read_message_id`),
  ADD KEY `idx__group_id_group_member_id` (`group_id`,`group_member_id`);

--
-- Indexes for table `gr_group_messages`
--
ALTER TABLE `gr_group_messages`
  ADD PRIMARY KEY (`group_message_id`),
  ADD KEY `group_id_fk_2` (`group_id`),
  ADD KEY `user_id_fk_2` (`user_id`),
  ADD KEY `group_message_id_fk_3` (`parent_message_id`),
  ADD KEY `idx__group_id_group_message_id` (`group_id`,`group_message_id`),
  ADD KEY `idx__attachment_type_group_id_group_message_id` (`attachment_type`,`group_id`,`group_message_id`);

--
-- Indexes for table `gr_group_messages_reactions`
--
ALTER TABLE `gr_group_messages_reactions`
  ADD PRIMARY KEY (`group_message_reaction_id`),
  ADD KEY `group_message_id_fk_4` (`group_message_id`),
  ADD KEY `user_id_fk_10` (`user_id`);

--
-- Indexes for table `gr_group_roles`
--
ALTER TABLE `gr_group_roles`
  ADD PRIMARY KEY (`group_role_id`);

--
-- Indexes for table `gr_languages`
--
ALTER TABLE `gr_languages`
  ADD PRIMARY KEY (`language_id`);

--
-- Indexes for table `gr_language_strings`
--
ALTER TABLE `gr_language_strings`
  ADD PRIMARY KEY (`string_id`),
  ADD KEY `language_id_fk` (`language_id`),
  ADD KEY `idx__string_constant` (`string_constant`(16)),
  ADD KEY `idx__skip_cache_skip_update_language_id` (`skip_cache`,`skip_update`,`language_id`);

--
-- Indexes for table `gr_login_sessions`
--
ALTER TABLE `gr_login_sessions`
  ADD PRIMARY KEY (`login_session_id`),
  ADD KEY `user_id_fk_3` (`user_id`),
  ADD KEY `idx__login_status` (`status`);

--
-- Indexes for table `gr_mailbox`
--
ALTER TABLE `gr_mailbox`
  ADD PRIMARY KEY (`mail_id`);

--
-- Indexes for table `gr_membership_orders`
--
ALTER TABLE `gr_membership_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `gr_membership_packages`
--
ALTER TABLE `gr_membership_packages`
  ADD PRIMARY KEY (`membership_package_id`);

--
-- Indexes for table `gr_payment_gateways`
--
ALTER TABLE `gr_payment_gateways`
  ADD PRIMARY KEY (`payment_gateway_id`);

--
-- Indexes for table `gr_private_chat_messages`
--
ALTER TABLE `gr_private_chat_messages`
  ADD PRIMARY KEY (`private_chat_message_id`),
  ADD KEY `private_conversation_id_fk` (`private_conversation_id`),
  ADD KEY `user_id_fk_12` (`user_id`),
  ADD KEY `private_message_id_fk_2` (`parent_message_id`),
  ADD KEY `idx__read_status_private_conversation_id_user_id` (`read_status`,`private_conversation_id`,`user_id`),
  ADD KEY `idx__user_id_read_status_private_conversation_id` (`user_id`,`read_status`,`private_conversation_id`);

--
-- Indexes for table `gr_private_chat_messages_reactions`
--
ALTER TABLE `gr_private_chat_messages_reactions`
  ADD PRIMARY KEY (`private_chat_message_reaction_id`),
  ADD KEY `user_id_fk_27` (`user_id`),
  ADD KEY `private_chat_message_id_fk_1` (`private_chat_message_id`);

--
-- Indexes for table `gr_private_conversations`
--
ALTER TABLE `gr_private_conversations`
  ADD PRIMARY KEY (`private_conversation_id`),
  ADD KEY `idx__initiator_user_id_recipient_user_id` (`initiator_user_id`,`recipient_user_id`),
  ADD KEY `idx__recipient_user_id_initiator_user_id` (`recipient_user_id`,`initiator_user_id`);

--
-- Indexes for table `gr_push_subscriptions`
--
ALTER TABLE `gr_push_subscriptions`
  ADD PRIMARY KEY (`push_subscriber_id`),
  ADD KEY `user_id_fk_21` (`user_id`);

--
-- Indexes for table `gr_realtime_logs`
--
ALTER TABLE `gr_realtime_logs`
  ADD PRIMARY KEY (`realtime_log_id`);

--
-- Indexes for table `gr_scheduled_messages`
--
ALTER TABLE `gr_scheduled_messages`
  ADD PRIMARY KEY (`scheduled_message_id`),
  ADD KEY `group_id_fk_9` (`group_id`),
  ADD KEY `user_id_fk_25` (`user_id`);

--
-- Indexes for table `gr_settings`
--
ALTER TABLE `gr_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `gr_site_advertisements`
--
ALTER TABLE `gr_site_advertisements`
  ADD PRIMARY KEY (`site_advert_id`);

--
-- Indexes for table `gr_site_notifications`
--
ALTER TABLE `gr_site_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx__user_id_read_status` (`user_id`,`read_status`);

--
-- Indexes for table `gr_site_roles`
--
ALTER TABLE `gr_site_roles`
  ADD PRIMARY KEY (`site_role_id`);

--
-- Indexes for table `gr_site_users`
--
ALTER TABLE `gr_site_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `name` (`username`),
  ADD UNIQUE KEY `email` (`email_address`),
  ADD KEY `site_role_id_fk` (`site_role_id`),
  ADD KEY `idx__online_status` (`online_status`),
  ADD KEY `idx__last_login_session` (`last_login_session`),
  ADD KEY `idx__updated_on` (`updated_on`);

--
-- Indexes for table `gr_site_users_blacklist`
--
ALTER TABLE `gr_site_users_blacklist`
  ADD PRIMARY KEY (`user_blacklist_id`),
  ADD KEY `idx__blacklisted_user_id_user_id` (`blacklisted_user_id`,`user_id`),
  ADD KEY `idx__user_id_blacklisted_user_id` (`user_id`,`blacklisted_user_id`),
  ADD KEY `idx__user_id_ignore` (`user_id`,`ignore`),
  ADD KEY `idx__user_id_block` (`user_id`,`block`);

--
-- Indexes for table `gr_site_users_device_logs`
--
ALTER TABLE `gr_site_users_device_logs`
  ADD PRIMARY KEY (`access_log_id`),
  ADD KEY `user_id_fk_18` (`user_id`);

--
-- Indexes for table `gr_site_users_membership`
--
ALTER TABLE `gr_site_users_membership`
  ADD PRIMARY KEY (`membership_info_id`),
  ADD KEY `user_id_fk_24` (`user_id`),
  ADD KEY `membership_package_id_fk_1` (`membership_package_id`);

--
-- Indexes for table `gr_site_users_settings`
--
ALTER TABLE `gr_site_users_settings`
  ADD PRIMARY KEY (`user_setting_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `user_id_fk_4` (`user_id`),
  ADD KEY `language_id_fk_2` (`language_id`);

--
-- Indexes for table `gr_social_login_providers`
--
ALTER TABLE `gr_social_login_providers`
  ADD PRIMARY KEY (`social_login_provider_id`);

--
-- Indexes for table `gr_typing_status`
--
ALTER TABLE `gr_typing_status`
  ADD PRIMARY KEY (`user_input_log_id`),
  ADD KEY `user_id_fk_19` (`user_id`),
  ADD KEY `private_conversation_id_fk_2` (`private_conversation_id`),
  ADD KEY `group_id_fk_7` (`group_id`),
  ADD KEY `idx__group_id_user_id` (`group_id`,`user_id`),
  ADD KEY `idx__group_id_updated_on` (`group_id`,`updated_on`),
  ADD KEY `idx__private_conversation_id_user_id` (`private_conversation_id`,`user_id`),
  ADD KEY `idx__private_conversation_id_updated_on` (`private_conversation_id`,`updated_on`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gr_audio_player`
--
ALTER TABLE `gr_audio_player`
  MODIFY `audio_content_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_badges`
--
ALTER TABLE `gr_badges`
  MODIFY `badge_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_badges_assigned`
--
ALTER TABLE `gr_badges_assigned`
  MODIFY `badge_assigned_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_bank_transfer_receipts`
--
ALTER TABLE `gr_bank_transfer_receipts`
  MODIFY `bank_transfer_receipt_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_billing_address`
--
ALTER TABLE `gr_billing_address`
  MODIFY `billing_address_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_complaints`
--
ALTER TABLE `gr_complaints`
  MODIFY `complaint_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_cron_jobs`
--
ALTER TABLE `gr_cron_jobs`
  MODIFY `cron_job_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_css_variables`
--
ALTER TABLE `gr_css_variables`
  MODIFY `css_variable_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=505;

--
-- AUTO_INCREMENT for table `gr_custom_fields`
--
ALTER TABLE `gr_custom_fields`
  MODIFY `field_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gr_custom_fields_values`
--
ALTER TABLE `gr_custom_fields_values`
  MODIFY `field_value_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_custom_menu_items`
--
ALTER TABLE `gr_custom_menu_items`
  MODIFY `menu_item_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gr_custom_pages`
--
ALTER TABLE `gr_custom_pages`
  MODIFY `page_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gr_friends`
--
ALTER TABLE `gr_friends`
  MODIFY `friendship_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_groups`
--
ALTER TABLE `gr_groups`
  MODIFY `group_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_group_categories`
--
ALTER TABLE `gr_group_categories`
  MODIFY `group_category_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gr_group_invitations`
--
ALTER TABLE `gr_group_invitations`
  MODIFY `group_invitation_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_group_members`
--
ALTER TABLE `gr_group_members`
  MODIFY `group_member_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_group_messages`
--
ALTER TABLE `gr_group_messages`
  MODIFY `group_message_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_group_messages_reactions`
--
ALTER TABLE `gr_group_messages_reactions`
  MODIFY `group_message_reaction_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_group_roles`
--
ALTER TABLE `gr_group_roles`
  MODIFY `group_role_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gr_languages`
--
ALTER TABLE `gr_languages`
  MODIFY `language_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gr_language_strings`
--
ALTER TABLE `gr_language_strings`
  MODIFY `string_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1522;

--
-- AUTO_INCREMENT for table `gr_login_sessions`
--
ALTER TABLE `gr_login_sessions`
  MODIFY `login_session_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_mailbox`
--
ALTER TABLE `gr_mailbox`
  MODIFY `mail_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_membership_orders`
--
ALTER TABLE `gr_membership_orders`
  MODIFY `order_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_membership_packages`
--
ALTER TABLE `gr_membership_packages`
  MODIFY `membership_package_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_payment_gateways`
--
ALTER TABLE `gr_payment_gateways`
  MODIFY `payment_gateway_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_private_chat_messages`
--
ALTER TABLE `gr_private_chat_messages`
  MODIFY `private_chat_message_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_private_chat_messages_reactions`
--
ALTER TABLE `gr_private_chat_messages_reactions`
  MODIFY `private_chat_message_reaction_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_private_conversations`
--
ALTER TABLE `gr_private_conversations`
  MODIFY `private_conversation_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_push_subscriptions`
--
ALTER TABLE `gr_push_subscriptions`
  MODIFY `push_subscriber_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_realtime_logs`
--
ALTER TABLE `gr_realtime_logs`
  MODIFY `realtime_log_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_scheduled_messages`
--
ALTER TABLE `gr_scheduled_messages`
  MODIFY `scheduled_message_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_settings`
--
ALTER TABLE `gr_settings`
  MODIFY `setting_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT for table `gr_site_advertisements`
--
ALTER TABLE `gr_site_advertisements`
  MODIFY `site_advert_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_site_notifications`
--
ALTER TABLE `gr_site_notifications`
  MODIFY `notification_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_site_roles`
--
ALTER TABLE `gr_site_roles`
  MODIFY `site_role_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gr_site_users`
--
ALTER TABLE `gr_site_users`
  MODIFY `user_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gr_site_users_blacklist`
--
ALTER TABLE `gr_site_users_blacklist`
  MODIFY `user_blacklist_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_site_users_device_logs`
--
ALTER TABLE `gr_site_users_device_logs`
  MODIFY `access_log_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_site_users_membership`
--
ALTER TABLE `gr_site_users_membership`
  MODIFY `membership_info_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_site_users_settings`
--
ALTER TABLE `gr_site_users_settings`
  MODIFY `user_setting_id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `gr_social_login_providers`
--
ALTER TABLE `gr_social_login_providers`
  MODIFY `social_login_provider_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gr_typing_status`
--
ALTER TABLE `gr_typing_status`
  MODIFY `user_input_log_id` bigint NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gr_badges_assigned`
--
ALTER TABLE `gr_badges_assigned`
  ADD CONSTRAINT `badge_id_fk` FOREIGN KEY (`badge_id`) REFERENCES `gr_badges` (`badge_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_id_fk_8` FOREIGN KEY (`group_id`) REFERENCES `gr_groups` (`group_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_20` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_bank_transfer_receipts`
--
ALTER TABLE `gr_bank_transfer_receipts`
  ADD CONSTRAINT `membership_order_id_fk_1` FOREIGN KEY (`membership_order_id`) REFERENCES `gr_membership_orders` (`order_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_billing_address`
--
ALTER TABLE `gr_billing_address`
  ADD CONSTRAINT `user_id_fk_26` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_complaints`
--
ALTER TABLE `gr_complaints`
  ADD CONSTRAINT `user_id_fk_7` FOREIGN KEY (`complainant_user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `gr_custom_fields_values`
--
ALTER TABLE `gr_custom_fields_values`
  ADD CONSTRAINT `field_id_fk` FOREIGN KEY (`field_id`) REFERENCES `gr_custom_fields` (`field_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_id_fk_4` FOREIGN KEY (`group_id`) REFERENCES `gr_groups` (`group_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_6` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `gr_custom_menu_items`
--
ALTER TABLE `gr_custom_menu_items`
  ADD CONSTRAINT `page_id_fk` FOREIGN KEY (`page_id`) REFERENCES `gr_custom_pages` (`page_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `gr_friends`
--
ALTER TABLE `gr_friends`
  ADD CONSTRAINT `user_id_fk_22` FOREIGN KEY (`from_user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_23` FOREIGN KEY (`to_user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_groups`
--
ALTER TABLE `gr_groups`
  ADD CONSTRAINT `group_category_id_fk_1` FOREIGN KEY (`group_category_id`) REFERENCES `gr_group_categories` (`group_category_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `group_role_id_fk_1` FOREIGN KEY (`default_group_role`) REFERENCES `gr_group_roles` (`group_role_id`) ON DELETE SET NULL ON UPDATE RESTRICT;

--
-- Constraints for table `gr_group_invitations`
--
ALTER TABLE `gr_group_invitations`
  ADD CONSTRAINT `group_id_fk_6` FOREIGN KEY (`group_id`) REFERENCES `gr_groups` (`group_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_9` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_group_members`
--
ALTER TABLE `gr_group_members`
  ADD CONSTRAINT `group_id_fk` FOREIGN KEY (`group_id`) REFERENCES `gr_groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_role_id_fk` FOREIGN KEY (`group_role_id`) REFERENCES `gr_group_roles` (`group_role_id`) ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `gr_group_messages`
--
ALTER TABLE `gr_group_messages`
  ADD CONSTRAINT `group_id_fk_2` FOREIGN KEY (`group_id`) REFERENCES `gr_groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_message_id_fk_3` FOREIGN KEY (`parent_message_id`) REFERENCES `gr_group_messages` (`group_message_id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_2` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_group_messages_reactions`
--
ALTER TABLE `gr_group_messages_reactions`
  ADD CONSTRAINT `group_message_id_fk_4` FOREIGN KEY (`group_message_id`) REFERENCES `gr_group_messages` (`group_message_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_10` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_language_strings`
--
ALTER TABLE `gr_language_strings`
  ADD CONSTRAINT `language_id_fk` FOREIGN KEY (`language_id`) REFERENCES `gr_languages` (`language_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_login_sessions`
--
ALTER TABLE `gr_login_sessions`
  ADD CONSTRAINT `user_id_fk_3` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `gr_private_chat_messages`
--
ALTER TABLE `gr_private_chat_messages`
  ADD CONSTRAINT `private_conversation_id_fk` FOREIGN KEY (`private_conversation_id`) REFERENCES `gr_private_conversations` (`private_conversation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `private_message_id_fk_2` FOREIGN KEY (`parent_message_id`) REFERENCES `gr_private_chat_messages` (`private_chat_message_id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_12` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_private_chat_messages_reactions`
--
ALTER TABLE `gr_private_chat_messages_reactions`
  ADD CONSTRAINT `private_chat_message_id_fk_1` FOREIGN KEY (`private_chat_message_id`) REFERENCES `gr_private_chat_messages` (`private_chat_message_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_27` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_private_conversations`
--
ALTER TABLE `gr_private_conversations`
  ADD CONSTRAINT `user_id_fk_13` FOREIGN KEY (`recipient_user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_14` FOREIGN KEY (`initiator_user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_push_subscriptions`
--
ALTER TABLE `gr_push_subscriptions`
  ADD CONSTRAINT `user_id_fk_21` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_scheduled_messages`
--
ALTER TABLE `gr_scheduled_messages`
  ADD CONSTRAINT `group_id_fk_9` FOREIGN KEY (`group_id`) REFERENCES `gr_groups` (`group_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_25` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `gr_site_notifications`
--
ALTER TABLE `gr_site_notifications`
  ADD CONSTRAINT `user_id_fk_15` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_site_users`
--
ALTER TABLE `gr_site_users`
  ADD CONSTRAINT `site_role_id_fk` FOREIGN KEY (`site_role_id`) REFERENCES `gr_site_roles` (`site_role_id`);

--
-- Constraints for table `gr_site_users_blacklist`
--
ALTER TABLE `gr_site_users_blacklist`
  ADD CONSTRAINT `user_id_fk_16` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_17` FOREIGN KEY (`blacklisted_user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_site_users_device_logs`
--
ALTER TABLE `gr_site_users_device_logs`
  ADD CONSTRAINT `user_id_fk_18` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_site_users_membership`
--
ALTER TABLE `gr_site_users_membership`
  ADD CONSTRAINT `membership_package_id_fk_1` FOREIGN KEY (`membership_package_id`) REFERENCES `gr_membership_packages` (`membership_package_id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_24` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_site_users_settings`
--
ALTER TABLE `gr_site_users_settings`
  ADD CONSTRAINT `language_id_fk_2` FOREIGN KEY (`language_id`) REFERENCES `gr_languages` (`language_id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_4` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gr_typing_status`
--
ALTER TABLE `gr_typing_status`
  ADD CONSTRAINT `group_id_fk_7` FOREIGN KEY (`group_id`) REFERENCES `gr_groups` (`group_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `private_conversation_id_fk_2` FOREIGN KEY (`private_conversation_id`) REFERENCES `gr_private_conversations` (`private_conversation_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id_fk_19` FOREIGN KEY (`user_id`) REFERENCES `gr_site_users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
