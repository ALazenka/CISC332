-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 26, 2018 at 10:55 AM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `Actor`
--

CREATE TABLE `Actor` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `actor_movie`
--

CREATE TABLE `actor_movie` (
  `id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Customer`
--

CREATE TABLE `Customer` (
  `account_number` int(11) NOT NULL,
  `password` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `town` varchar(50) NOT NULL,
  `postalcode` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `phone_number` decimal(10,0) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `cc_number` bigint(20) NOT NULL,
  `cc_expiry_date` varchar(50) NOT NULL,
  `role` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Customer`
--

INSERT INTO `Customer` (`account_number`, `password`, `firstname`, `lastname`, `street`, `town`, `postalcode`, `province`, `country`, `phone_number`, `email_address`, `cc_number`, `cc_expiry_date`, `role`, `id`) VALUES
(10176676, 'letmein', 'Andrew', 'Lazenka', '18 Joseph Street', 'Uxbridge', 'L9P1H8', 'Ontario', 'Canada', '4169869241', 'andrewlazenka@gmail.com', 1111222233334444, '1118', 1, 1),
(10178345, 'apassword', 'Hillary', 'Lia', '200 Nelson St', 'Pickering', 'L4B4T9', 'Ontario', 'Canada', '6472810693', '14hl43@queensu.ca', 100010000102000, '0918', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `Movie`
--

CREATE TABLE `Movie` (
  `title` varchar(50) NOT NULL,
  `run_time` decimal(10,0) NOT NULL,
  `rating` varchar(5) NOT NULL,
  `synopsis` varchar(1000) NOT NULL,
  `director` varchar(50) NOT NULL,
  `production_company` varchar(50) NOT NULL,
  `supplier_name` varchar(50) NOT NULL,
  `start_date` decimal(10,0) NOT NULL,
  `end_date` decimal(10,0) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Movie`
--

INSERT INTO `Movie` (`title`, `run_time`, `rating`, `synopsis`, `director`, `production_company`, `supplier_name`, `start_date`, `end_date`, `id`) VALUES
('Black Panther', '132', 'PG', 'After the death of his father, T Challa returns home to the African nation of Wakanda to take his rightful place as king. When a powerful enemy suddenly reappears, T Challas mettle as king -- and as Black Panther -- gets tested when he is drawn into a conflict that puts the fate of Wakanda and the entire world at risk. Faced with treachery and danger, the young king must rally his allies and release the full power of Black Panther to defeat his foes and secure the safety of his people.', 'Ryan Coogler', 'Marvel Studios', 'Walt Disney Studios', '22042018', '15062018', 1),
('The Greatest Showman', '120', 'PG', 'Inspired by the imagination of P. T. Barnum, The Greatest Showman is an original musical that celebrates the birth of show business & tells of a visionary who rose from nothing to create a spectacle that became a worldwide sensation.', 'Michael Gracey', 'Seed Productions', 'Warner Bros', '11102017', '15022018', 2),
('Tomb Raider', '117', 'R', 'Lara Croft, the fiercely independent daughter of a missing adventurer, must push herself beyond her limits when she finds herself on the island where her father disappeared.', 'Roar Uthaug', 'Columbia Entertainment', 'Warner Bros', '22032018', '1052018', 5),
('Green Lantern', '114', 'G', 'Sworn to preserve intergalactic order, the Green Lantern Corps has existed for centuries. Its newest recruit, Hal Jordan (Ryan Reynolds), is the first human to join the ranks. The Green Lanterns have little regard for humans, who have thus far been unable to harness the powers of the ring each member wears. But Jordan, a gifted and cocky test pilot, may be the corps only hope when a new enemy called Parallax threatens the universal balance of power.', 'Martin Campbell', 'DC Entertainment', 'Warner Bros', '6152011', '9012011', 6);

-- --------------------------------------------------------

--
-- Table structure for table `Movie_Supplier`
--

CREATE TABLE `Movie_Supplier` (
  `company_name` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `town` varchar(50) NOT NULL,
  `postalcode` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `phone_number` decimal(10,0) NOT NULL,
  `contact_first_name` varchar(50) NOT NULL,
  `contact_last_name` varchar(50) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Reservation`
--

CREATE TABLE `Reservation` (
  `account_number` int(11) NOT NULL,
  `showing_id` int(11) NOT NULL,
  `tickets_reserved` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Reservation`
--

INSERT INTO `Reservation` (`account_number`, `showing_id`, `tickets_reserved`, `id`) VALUES
(10176676, 1, 6, 2),
(10176676, 1, 5, 4),
(10176676, 6, 6, 5),
(10176676, 6, 3, 6),
(10176676, 8, 3, 7),
(10176676, 11, 4, 9),
(10176676, 16, 3, 10),
(10178345, 1, 1, 12),
(10178345, 18, 2, 13);

-- --------------------------------------------------------

--
-- Table structure for table `Review`
--

CREATE TABLE `Review` (
  `title` varchar(50) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `score` int(1) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Review`
--

INSERT INTO `Review` (`title`, `content`, `score`, `movie_id`, `customer_id`, `id`) VALUES
('Very different for a Marvel movie', 'I was pleasantly surprised to see Marvel actually expanding on their characters and plot line, rather than constant action and chaos.', 4, 1, 1, 25),
('Best movie I have seen in a long time!', 'The cast they chose for this movie really took the cake for me, will recommend to all of my friends!', 5, 2, 3, 26),
('Such a let down...', 'There was no plot line to follow, the action was simply mediocre. Do not waste your time with this one.', 1, 6, 1, 27),
('Female lead in an action/exploration movie...', '... turns out to be a knock out! Such a great twist on the video game series as well, amazing camera angles and story line.', 3, 5, 3, 28);

-- --------------------------------------------------------

--
-- Table structure for table `Showing`
--

CREATE TABLE `Showing` (
  `id` int(11) NOT NULL,
  `theater_complex_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `theater_id` int(11) NOT NULL,
  `start_time` varchar(10) NOT NULL,
  `seats_available` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Showing`
--

INSERT INTO `Showing` (`id`, `theater_complex_id`, `movie_id`, `theater_id`, `start_time`, `seats_available`) VALUES
(11, 1, 1, 1, '4:20', 150),
(12, 2, 2, 11, '3:00', 0),
(13, 3, 6, 10, '12:00', 0),
(14, 2, 5, 3, '1:00', 0),
(15, 2, 1, 4, '1:10', 0),
(16, 3, 6, 5, '6:00', 0),
(17, 3, 2, 2, '9:00', 0),
(18, 1, 5, 9, '3:25', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Theater`
--

CREATE TABLE `Theater` (
  `theater_number` int(11) NOT NULL,
  `max_seats` int(11) NOT NULL,
  `screen_size` varchar(20) NOT NULL,
  `theater_complex_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Theater`
--

INSERT INTO `Theater` (`theater_number`, `max_seats`, `screen_size`, `theater_complex_id`, `id`) VALUES
(1, 100, 'medium', 1, 1),
(3, 150, 'medium', 3, 2),
(1, 200, 'large', 2, 3),
(2, 100, 'small', 2, 4),
(1, 150, 'medium', 3, 5),
(2, 150, 'medium', 1, 9),
(2, 0, 'large', 3, 10),
(3, 0, 'small', 2, 11),
(3, 0, 'medium', 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `Theater_Complex`
--

CREATE TABLE `Theater_Complex` (
  `name` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `town` varchar(50) NOT NULL,
  `postalcode` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `phone_number` decimal(10,0) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Theater_Complex`
--

INSERT INTO `Theater_Complex` (`name`, `street`, `town`, `postalcode`, `province`, `country`, `phone_number`, `id`) VALUES
('XScape Cineplex', 'Green Lane', 'Newmarket', 'K9K9K9', 'Ontario', 'Canada', '4166664164', 1),
('Kingston Cinemas', 'Gardiners Road', 'Kingston', 'L8L8L8', 'Ontario', 'Canada', '6136136136', 2),
('Landmark Cinema', '79 Division St', 'Kingston', 'K7K1M6', 'Ontario', 'Canada', '6478886543', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Actor`
--
ALTER TABLE `Actor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `actor_movie`
--
ALTER TABLE `actor_movie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actor_id` (`actor_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_number` (`account_number`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- Indexes for table `Movie`
--
ALTER TABLE `Movie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Movie_Supplier`
--
ALTER TABLE `Movie_Supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Reservation`
--
ALTER TABLE `Reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_number` (`account_number`);

--
-- Indexes for table `Review`
--
ALTER TABLE `Review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`) USING BTREE,
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `Showing`
--
ALTER TABLE `Showing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theater_complex_id` (`theater_complex_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `theater_id` (`theater_id`);

--
-- Indexes for table `Theater`
--
ALTER TABLE `Theater`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theater_complex_id` (`theater_complex_id`);

--
-- Indexes for table `Theater_Complex`
--
ALTER TABLE `Theater_Complex`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Actor`
--
ALTER TABLE `Actor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `actor_movie`
--
ALTER TABLE `actor_movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Customer`
--
ALTER TABLE `Customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Movie`
--
ALTER TABLE `Movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Movie_Supplier`
--
ALTER TABLE `Movie_Supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Reservation`
--
ALTER TABLE `Reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Review`
--
ALTER TABLE `Review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `Showing`
--
ALTER TABLE `Showing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `Theater`
--
ALTER TABLE `Theater`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Theater_Complex`
--
ALTER TABLE `Theater_Complex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actor_movie`
--
ALTER TABLE `actor_movie`
  ADD CONSTRAINT `actor_movie_ibfk_1` FOREIGN KEY (`actor_id`) REFERENCES `Actor` (`id`),
  ADD CONSTRAINT `actor_movie_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `Movie` (`id`);

--
-- Constraints for table `Reservation`
--
ALTER TABLE `Reservation`
  ADD CONSTRAINT `account_number` FOREIGN KEY (`account_number`) REFERENCES `Customer` (`account_number`);

--
-- Constraints for table `Review`
--
ALTER TABLE `Review`
  ADD CONSTRAINT `customer_id` FOREIGN KEY (`customer_id`) REFERENCES `Customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `movie_id` FOREIGN KEY (`movie_id`) REFERENCES `Movie` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Showing`
--
ALTER TABLE `Showing`
  ADD CONSTRAINT `theater_complex_id` FOREIGN KEY (`theater_complex_id`) REFERENCES `Theater_Complex` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `theater_id` FOREIGN KEY (`theater_id`) REFERENCES `Theater` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
