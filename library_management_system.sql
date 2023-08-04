-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 04, 2023 at 05:33 AM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(25) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `thumbnail` blob NOT NULL,
  `author` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `return_date` varchar(255) NOT NULL,
  `availability` tinyint(1) NOT NULL DEFAULT '1',
  `price` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `description`, `thumbnail`, `author`, `genre`, `return_date`, `availability`, `price`) VALUES
(1, 'All the Bright Places', 'A compelling and beautiful story about a girl who learns to live from a boy who\'s drawn to death. ', 0x616c6c546865427269676874506c616365732e6a7067, 'Jennifer Niven', 'Fiction', '2023-08-07', 1, 250),
(6, 'The House of Fortune', ' Set in the golden city of Amsterdam in 1705, it is a story of fate and ambition, secrets and dreams, and one young woman\'s determination to rule her own destiny.', 0x686f7573654f66466f7274756e652e6a7067, 'Jessie Burton', 'Fiction', '2023-08-09', 1, 230),
(7, 'It\'s Not Summer Without You', 'Book 2 in The Summer I Turned Pretty series - now a major new TV show on Amazon Prime!', 0x6e6f7453756d6d6572312e6a7067, 'Jenny Han', 'Fiction', '2023-08-10', 1, 300),
(8, 'The Summer I Turned Pretty', 'The Summer I Turned Pretty is now a major new TV series on Amazon Prime! From the author of Netflix\'s smash-hit movie To All The Boys I\'ve Loved Before, this is the perfect funny summer romance for fans of The Kissing Booth and Holly Bourne. One girl. Two boys. And the summer that changed everything', 0x74686553756d6d65722e6a7067, 'Jenny Han', 'Fiction', '2023-08-15', 1, 300),
(9, 'Wineries of the Cape', 'From historic gabled manor houses to contemporary wineries, quirky family-run farms to iconic estates, country picnics to world-class fine dining restaurants, Wineries of the Cape profiles 58 of the very best visitor experiences in the winelands', 0x77696e652e6a7067, 'Lindsaye McGregor, Erica Bartholomae', 'Travel', '2023-08-08', 1, 450),
(10, 'One', 'In ONE, Jamie Oliver will guide you through over 120 recipes for tasty, fuss-free and satisfying dishes cooked in just one pan.', 0x6f6e652e6a7067, 'Jamie Oliver', 'Cooking', '2023-08-15', 1, 400),
(11, 'Guide to trees introduced into Southern Africa', ' This guide features nearly 600 of the most common and familiar of these and, using the same model of identification as FG Trees of Southern Africa, facilitates ID based on leaf and stem features.', 0x74726565732e6a7067, 'Hugh Glen, Braam Van Wyk', 'Gardening', '2023-08-16', 1, 380),
(12, 'Gardening With Keith Kirsten', 'The text has been updated to incorporate more indigenous species, locally bred hybrids, and waterwise plants, in keeping with changing trends that recognize the importance of gardening in harmony with the natural environment.', 0x67617264656e2e6a7067, 'Keith Kirsten', 'Gardening', '2023-08-10', 1, 290),
(16, 'Yes', 'Yes is a book', '', 'Yes', 'Yes', '2023-08-19', 1, 270);

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

CREATE TABLE `librarian` (
  `librarian_id` int(25) NOT NULL,
  `employee_number` int(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`librarian_id`, `employee_number`, `fullname`, `role`) VALUES
(1, 159753, 'Emily Houston', 'Head Librarian');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(25) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `username`, `fullname`, `address`, `password`, `email`) VALUES
(20, 'Mia', 'Mia Renolds', '19 Jacaranda Street', '123', 'mia@gmail.com'),
(21, 'Ben', 'Ben Swart', '20 Coral Reef', '147', 'benS@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `rental_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rental`
--

INSERT INTO `rental` (`rental_id`, `member_id`, `book_id`, `return_date`) VALUES
(146, 21, 10, '2023-08-09'),
(149, 20, 9, '2023-08-11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `librarian`
--
ALTER TABLE `librarian`
  ADD PRIMARY KEY (`librarian_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `librarian`
--
ALTER TABLE `librarian`
  MODIFY `librarian_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `rental`
--
ALTER TABLE `rental`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `rental_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `rental_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
