-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2019 at 07:18 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bluepoll`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `p1` (IN `x` INT)  BEGIN 
    	SET x=0;
    	SELECT * FROM users WHERE users.user_id > @x;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p2` (`x` INTEGER)  BEGIN 
    	SET x=0;
    	SELECT * FROM users WHERE users.user_id > 0;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc` ()  begin 
        	SELECT @a FROM users;
        END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc2` (`a` INT)  begin 
        	SET a=a+1;
        	SELECT * FROM users WHERE @a > 5;
        END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'technology'),
(2, 'cinema'),
(3, 'art'),
(4, 'politics'),
(5, 'history'),
(6, 'science'),
(7, 'book'),
(8, 'music');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_poll_id` int(255) NOT NULL,
  `comment_content` longtext NOT NULL,
  `comment_user_id` varchar(255) NOT NULL,
  `comment_birthdate` datetime DEFAULT NULL,
  `comment_order` int(10) UNSIGNED DEFAULT NULL,
  `comment_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dislikes`
--

CREATE TABLE `dislikes` (
  `dislike_id` int(11) NOT NULL,
  `dislike_poll_id` int(11) NOT NULL,
  `dislike_disliker_id` int(11) NOT NULL,
  `dislike_disliked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dislikes`
--

INSERT INTO `dislikes` (`dislike_id`, `dislike_poll_id`, `dislike_disliker_id`, `dislike_disliked_at`) VALUES
(39, 73, 3, '2019-11-13 12:56:04'),
(40, 52, 3, '2019-11-13 12:56:04'),
(41, 27, 3, '2019-11-13 12:56:05'),
(52, 89, 7, '2019-11-15 06:44:40'),
(53, 91, 7, '2019-11-15 06:44:43'),
(54, 100, 7, '2019-11-15 06:44:45'),
(55, 89, 6, '2019-11-15 06:45:04'),
(185, 90, 7, '2019-11-17 16:45:25'),
(195, 90, 4, '2019-11-26 11:07:40'),
(209, 102, 6, '2019-11-26 11:59:20'),
(210, 103, 6, '2019-11-26 11:59:21'),
(212, 105, 6, '2019-11-26 11:59:33');

--
-- Triggers `dislikes`
--
DELIMITER $$
CREATE TRIGGER `remove_like` BEFORE INSERT ON `dislikes` FOR EACH ROW BEGIN
	DELETE FROM likes 
    	WHERE likes.like_poll_id = new.dislike_poll_id AND 
        	  likes.like_liker_id = new.dislike_disliker_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `remove_notification_for_dislike` AFTER DELETE ON `dislikes` FOR EACH ROW BEGIN
	DELETE FROM 
    	notifications 
    WHERE
    	notifications.notification_object_id = old.dislike_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `like_poll_id` int(11) NOT NULL,
  `like_liker_id` int(11) NOT NULL,
  `like_liked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `like_poll_id`, `like_liker_id`, `like_liked_at`) VALUES
(356, 107, 3, '2019-11-26 11:10:04'),
(357, 103, 3, '2019-11-26 11:10:23'),
(370, 100, 3, '2019-11-26 11:13:01'),
(372, 91, 3, '2019-11-26 11:38:32'),
(373, 102, 3, '2019-11-26 11:38:36'),
(374, 105, 3, '2019-11-26 11:38:40'),
(377, 100, 6, '2019-11-26 11:59:18'),
(381, 91, 6, '2019-11-26 12:36:07'),
(383, 90, 6, '2019-11-26 12:42:28'),
(384, 90, 3, '2019-12-03 14:26:31');

--
-- Triggers `likes`
--
DELIMITER $$
CREATE TRIGGER `remove_dislike` BEFORE INSERT ON `likes` FOR EACH ROW BEGIN
	DELETE FROM dislikes 
    	WHERE dislikes.dislike_poll_id = new.like_poll_id AND 
        	  dislikes.dislike_disliker_id = new.like_liker_id;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `remove_notification_for_like` AFTER DELETE ON `likes` FOR EACH ROW BEGIN
	DELETE FROM 
    	notifications 
    WHERE
    	notifications.notification_object_id = old.like_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `notification_actor_id` int(11) NOT NULL,
  `notification_action` varchar(1000) NOT NULL,
  `notification_poll_id` int(11) NOT NULL,
  `notification_object_id` int(11) DEFAULT NULL,
  `notification_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `notification_actor_id`, `notification_action`, `notification_poll_id`, `notification_object_id`, `notification_date`) VALUES
(718, 6, 'newOptionRequest', 100, 100, '2019-11-26 13:03:12'),
(719, 3, 'newComment', 103, 213, '2019-11-26 13:18:02'),
(720, 3, 'newComment', 91, 214, '2019-11-26 13:18:23'),
(721, 3, 'newComment', 91, 215, '2019-11-26 13:18:36'),
(722, 3, 'newComment', 91, 216, '2019-11-26 13:18:37'),
(724, 3, 'newComment', 91, 218, '2019-11-26 13:18:39'),
(725, 3, 'newComment', 91, 219, '2019-11-26 13:18:40'),
(726, 3, 'newComment', 91, 220, '2019-11-26 13:18:41'),
(727, 3, 'newComment', 91, 221, '2019-11-26 13:18:42'),
(728, 3, 'newVote', 91, 1, '2019-11-26 13:53:54'),
(729, 3, 'newVote', 91, 1, '2019-11-26 13:53:56'),
(730, 3, 'newVote', 90, 1, '2019-11-29 17:31:32'),
(731, 3, 'newVote', 102, 1, '2019-11-30 12:19:52'),
(732, 3, 'newVote', 102, 1, '2019-11-30 12:19:58'),
(733, 3, 'newOptionRequest', 90, 101, '2019-12-03 12:58:23'),
(734, 3, 'newVote', 90, 1, '2019-12-03 13:22:41'),
(735, 3, 'newVote', 90, 1, '2019-12-03 13:22:45'),
(736, 3, 'newVote', 91, 1, '2019-12-03 13:22:52'),
(737, 3, 'newVote', 90, 1, '2019-12-03 13:22:56'),
(738, 3, 'newVote', 103, 1, '2019-12-03 14:23:05'),
(739, 3, 'newComment', 90, 222, '2019-12-03 14:26:01'),
(741, 3, 'newLike', 90, 384, '2019-12-03 14:26:31');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_id` int(10) UNSIGNED NOT NULL,
  `option_name` varchar(10000) NOT NULL,
  `option_belongsto` int(11) NOT NULL,
  `option_addedby_id` int(11) NOT NULL,
  `option_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `option_votes` int(11) NOT NULL DEFAULT 0,
  `option_status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `option_name`, `option_belongsto`, `option_addedby_id`, `option_created_at`, `option_votes`, `option_status`) VALUES
(6, '3 Idiots', 2, 4, '2019-11-01 23:28:04', 0, 'active'),
(7, 'Lagaan', 2, 4, '2019-11-01 23:28:04', 0, 'active'),
(8, 'Kal Ho Na Ho', 2, 4, '2019-11-01 23:28:04', 0, 'active'),
(9, 'Chak De Basanti', 2, 4, '2019-11-01 23:28:04', 0, 'active'),
(10, 'Lage Raho Munna Bhai', 2, 4, '2019-11-01 23:28:04', 0, 'active'),
(11, 'Think and Grow Rich by Nepolion Hill', 3, 4, '2019-11-01 23:31:48', 0, 'active'),
(12, '7 Habits of Highly Effective People by Seteven Cuvy', 3, 4, '2019-11-01 23:31:48', 0, 'active'),
(13, 'How to Win Friends and Influence People by Dale Carnige', 3, 4, '2019-11-01 23:31:48', 0, 'active'),
(14, 'The Power Of Now by Eckhart Tolle', 3, 4, '2019-11-01 23:31:48', 0, 'active'),
(44, 'Swadesh', 16, 4, '2019-11-02 19:05:56', 0, 'active'),
(45, 'Lage Raho Munna Bhai', 16, 4, '2019-11-02 19:05:56', 0, 'active'),
(46, '3 idiots', 16, 4, '2019-11-02 19:05:56', 0, 'active'),
(47, 'PK', 16, 4, '2019-11-02 19:05:56', 0, 'active'),
(48, 'Dhoom 2', 16, 4, '2019-11-02 19:05:56', 0, 'active'),
(49, 'Tare Zameen Paar', 16, 4, '2019-11-02 19:05:56', 0, 'active'),
(50, 'Eckhart Tolle', 17, 4, '2019-11-02 19:19:02', 0, 'active'),
(51, 'Steven Cuvy', 17, 4, '2019-11-02 19:19:02', 0, 'active'),
(52, 'Robert Greene', 17, 4, '2019-11-02 19:19:02', 0, 'active'),
(56, 'Think and Grow Rich by Nepolion Hill', 19, 4, '2019-11-02 20:56:27', 0, 'active'),
(57, '7 Habits of Highly Effective People by Seteven Cuvy', 19, 4, '2019-11-02 20:56:27', 0, 'active'),
(58, 'How to Win Friends and Influence People by Dale Carnige', 19, 4, '2019-11-02 20:56:27', 0, 'active'),
(59, 'The Power Of Now by Eckhart Tolle', 19, 4, '2019-11-02 20:56:27', 0, 'active'),
(373, 'iPhone XI', 91, 3, '2019-11-15 00:44:49', 0, 'active'),
(374, 'Samsung Galaxy S10', 91, 3, '2019-11-15 00:44:49', 0, 'active'),
(375, 'OPPO Reno 2', 91, 3, '2019-11-15 00:44:49', 0, 'active'),
(376, 'Xiomi Redmi 5', 91, 3, '2019-11-15 00:44:49', 0, 'active'),
(397, 'The Dark Knight', 100, 3, '2019-11-15 02:08:37', 0, 'active'),
(398, 'Interstellar', 100, 3, '2019-11-15 02:08:37', 0, 'active'),
(399, 'Inception', 100, 3, '2019-11-15 02:08:37', 0, 'active'),
(400, 'Memento', 100, 3, '2019-11-15 02:08:37', 0, 'active'),
(401, 'The Batman', 100, 3, '2019-11-15 02:08:37', 0, 'active'),
(432, 'Laravel', 102, 4, '2019-11-15 02:47:10', 0, 'active'),
(433, 'Ruby On Rails', 102, 4, '2019-11-15 02:47:10', 0, 'active'),
(434, 'Java Spring', 102, 4, '2019-11-15 02:47:10', 0, 'active'),
(435, 'ASP.NET', 102, 4, '2019-11-15 02:47:10', 0, 'active'),
(436, 'Django', 102, 4, '2019-11-15 02:47:10', 0, 'active'),
(437, 'Flask', 102, 4, '2019-11-15 02:47:10', 0, 'active'),
(438, 'GodFather', 103, 4, '2019-11-15 06:47:30', 0, 'active'),
(439, 'GodFather II', 103, 4, '2019-11-15 06:47:30', 0, 'active'),
(440, 'The Dark Knight', 103, 4, '2019-11-15 06:47:30', 0, 'active'),
(441, 'Lord of the Rings the Return of the King', 103, 4, '2019-11-15 06:47:30', 0, 'active'),
(448, 'Google Chrome', 105, 4, '2019-11-17 16:44:33', 0, 'active'),
(449, 'Mozilla Firefox', 105, 4, '2019-11-17 16:44:33', 0, 'active'),
(450, 'Opera', 105, 4, '2019-11-17 16:44:33', 0, 'active'),
(451, 'Safari', 105, 4, '2019-11-17 16:44:33', 0, 'active'),
(516, 'Torch Browser', 105, 3, '2019-11-22 06:46:43', 0, NULL),
(520, 'Ali Zafar', 90, 3, '2019-11-29 11:50:14', 0, 'active'),
(521, 'Nusrat Fateh Ali Khan', 90, 3, '2019-11-29 11:50:14', 0, 'active'),
(522, 'Atif Aslam', 90, 3, '2019-11-29 11:50:14', 0, 'active'),
(523, 'Rahat Fateh Ali Khan', 90, 3, '2019-11-29 11:50:14', 0, 'active'),
(524, 'Quratul Blouch', 90, 3, '2019-11-29 11:50:14', 0, 'active');

--
-- Triggers `options`
--
DELIMITER $$
CREATE TRIGGER `after_delete_option` BEFORE DELETE ON `options` FOR EACH ROW BEGIN
	DELETE FROM votes WHERE votes.vote_option_id = old.option_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `poll_id` int(255) UNSIGNED NOT NULL,
  `poll_category` varchar(100) DEFAULT NULL,
  `poll_user_id` int(255) UNSIGNED NOT NULL,
  `poll_name` longtext NOT NULL,
  `poll_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `poll_likes` int(10) UNSIGNED DEFAULT 0,
  `poll_dislikes` int(10) UNSIGNED DEFAULT 0,
  `poll_view` int(10) UNSIGNED DEFAULT 0,
  `poll_status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`poll_id`, `poll_category`, `poll_user_id`, `poll_name`, `poll_created_at`, `poll_likes`, `poll_dislikes`, `poll_view`, `poll_status`) VALUES
(90, 'music', 3, 'Best Pakistani Singer', '2019-11-29 11:50:14', 0, 0, 0, 1),
(91, 'technology', 3, 'Best Phone of 2019', '2019-11-15 02:06:14', 0, 0, 0, 1),
(100, 'cinema', 3, 'Best Film Christopher Nonal', '2019-11-26 11:09:11', 0, 0, 0, 1),
(102, 'technology', 4, 'Top Backend Frameworks', '2019-11-17 18:44:34', 0, 0, 0, 1),
(103, 'cinema', 4, 'Best Movie Of All Time', '2019-11-15 06:47:30', 0, 0, 0, 1),
(105, 'technology', 4, 'Best Web Browser', '2019-11-17 16:44:33', 0, 0, 0, 1);

--
-- Triggers `polls`
--
DELIMITER $$
CREATE TRIGGER `after_delete_poll` BEFORE DELETE ON `polls` FOR EACH ROW BEGIN
	DELETE FROM options WHERE options.option_belongsto = old.poll_id;
	DELETE FROM comments WHERE comments.comment_poll_id = old.poll_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `proposed_option`
--

CREATE TABLE `proposed_option` (
  `proposed_option_id` int(11) NOT NULL,
  `proposed_option_name` varchar(100) NOT NULL,
  `proposed_option_poll_id` int(11) NOT NULL,
  `proposed_option_poll_added_by` int(11) NOT NULL,
  `proposed_option_poll_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_firstname` varchar(100) NOT NULL,
  `user_lastname` varchar(100) NOT NULL,
  `user_username` varchar(255) NOT NULL,
  `user_password` mediumtext NOT NULL,
  `user_email` varchar(1000) NOT NULL,
  `user_phone` int(15) NOT NULL,
  `user_age` int(10) UNSIGNED NOT NULL,
  `user_gender` varchar(50) NOT NULL,
  `user_bloodgroup` varchar(50) NOT NULL,
  `user_education` varchar(100) NOT NULL,
  `user_profession` varchar(1000) NOT NULL,
  `user_current_city` varchar(100) NOT NULL,
  `user_from` varchar(100) NOT NULL,
  `user_avater` varchar(100) DEFAULT 'ano.jpg',
  `user_roll` varchar(100) DEFAULT 'user',
  `user_nationality` varchar(100) DEFAULT NULL,
  `user_ethnicity` varchar(100) DEFAULT NULL,
  `user_website` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_firstname`, `user_lastname`, `user_username`, `user_password`, `user_email`, `user_phone`, `user_age`, `user_gender`, `user_bloodgroup`, `user_education`, `user_profession`, `user_current_city`, `user_from`, `user_avater`, `user_roll`, `user_nationality`, `user_ethnicity`, `user_website`) VALUES
(3, 'Rakesh', 'Patel', 'rakesh', '$2y$10$7KL1xQtIL8mFrsiVFn0T9.NhTzd5k4jfo85OGqbB4VuFr2A6pDPaa', 'rakesh@gmail.com', 2344234, 72, 'Male', 'O+', 'Bcom', 'Film Maker', 'Mumbai', 'Mumbai', 'sticky Notes.PNG', 'user', NULL, NULL, NULL),
(4, 'Shuvo', 'Sarker', 'shuvo', '$2y$10$lvXSAaRict2XJlCM8.6fFet6RuOooKvX7wJSIIUYK6iBaA6toSWc2', 'shuvooa707@gmail.com', 1290129012, 25, 'Male', 'Ab+', 'BSc in CSE', 'Web Dev', 'Dhaka', 'Dhaka', 'sticky Notes.PNG', 'user', NULL, NULL, NULL),
(6, 'Andrew', 'Simon', 'simon', '$2y$10$6X3jQX3FaordlUgrAamyUOFUnwVX1eMbXPa9RDkaxC288w1PGL9EW', 'simon@gmail.com', 2147483647, 40, 'Male', 'O+', 'Journalism', 'Writter', 'New York', 'New York', 'post_5_thumpic.jpg', 'user', NULL, NULL, NULL),
(7, 'Aslam', 'Atif', 'atif', '$2y$10$cz6tTNL50zuh.CKuXsAuXesDWTG4RfaSlMAMsWRkh1rSkqb7jJfgC', 'atif@gmail.com', 23920112, 40, 'Male', 'O+', 'BCom', 'BCom', 'Karachi', 'Karachi', 'post_5_thumpic.jpg', 'user', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE `views` (
  `view_id` int(11) NOT NULL,
  `view_viewed_by` int(11) DEFAULT NULL,
  `view_poll_id` int(11) NOT NULL,
  `view_ip` varchar(20) DEFAULT NULL,
  `view_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(10) UNSIGNED NOT NULL,
  `vote_given_by` int(10) UNSIGNED NOT NULL,
  `vote_option_id` int(10) UNSIGNED NOT NULL,
  `vote_poll_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`vote_id`, `vote_given_by`, `vote_option_id`, `vote_poll_id`) VALUES
(552, 4, 373, 91),
(555, 4, 432, 102),
(559, 7, 376, 91),
(560, 7, 433, 102),
(561, 7, 399, 100),
(563, 6, 397, 100),
(566, 6, 373, 91),
(567, 6, 433, 102),
(568, 4, 438, 103),
(582, 4, 448, 105),
(584, 7, 449, 105),
(585, 3, 448, 105),
(586, 6, 450, 105),
(587, 6, 441, 103),
(593, 3, 400, 100),
(603, 4, 399, 100),
(611, 3, 436, 102),
(614, 3, 376, 91),
(615, 3, 521, 90),
(616, 3, 439, 103);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `dislikes`
--
ALTER TABLE `dislikes`
  ADD PRIMARY KEY (`dislike_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`poll_id`);

--
-- Indexes for table `proposed_option`
--
ALTER TABLE `proposed_option`
  ADD PRIMARY KEY (`proposed_option_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`view_id`);
ALTER TABLE `views` ADD FULLTEXT KEY `ip_check` (`view_ip`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `dislikes`
--
ALTER TABLE `dislikes`
  MODIFY `dislike_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=742;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `option_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=525;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `poll_id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `proposed_option`
--
ALTER TABLE `proposed_option`
  MODIFY `proposed_option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `views`
--
ALTER TABLE `views`
  MODIFY `view_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=617;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
