-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Nov 07, 2024 at 12:09 PM
-- Server version: 8.4.0
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taskdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` int NOT NULL,
  `project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` enum('pending','in_progress','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `owner_id` int NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `project_name`, `description`, `status`, `owner_id`, `due_date`, `created_at`) VALUES
(1, 'Task Management System', 'Developing a system to manage tasks for project collaboration', 'pending', 1, '2024-11-10', '2024-11-07 10:21:31'),
(2, 'Website Redesign', 'Redesigning and updating the company\'s main website for better UX', 'in_progress', 3, '2024-12-18', '2024-11-07 10:21:31'),
(3, 'Data Migration Project', 'Migrating legacy data to a new cloud-based infrastructure', 'in_progress', 2, '2025-06-17', '2024-11-07 10:21:31'),
(4, 'Cybersecurity Audit', 'Conducting a security audit to ensure compliance with industry standards', 'in_progress', 1, '2024-12-24', '2024-11-07 10:21:31'),
(5, 'E-commerce Platform Dev', 'Developing an e-commerce platform with user authentication, shopping cart, and payment gateway', 'in_progress', 3, '2024-11-24', '2024-11-07 10:21:31'),
(64, 'Bug Zapping Simulator', 'Developing a game where you zap bugs that keep crawling into your code editor.', 'in_progress', 1, '2024-11-21', '2024-11-07 11:15:20');

-- --------------------------------------------------------

--
-- Table structure for table `project_users`
--

CREATE TABLE `project_users` (
  `project_users_id` int NOT NULL,
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  `role` enum('owner','member') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'member',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_users`
--

INSERT INTO `project_users` (`project_users_id`, `project_id`, `user_id`, `role`, `added_at`) VALUES
(1, 1, 2, 'member', '2024-11-07 10:23:46'),
(2, 1, 3, 'member', '2024-11-07 10:23:46'),
(3, 3, 3, 'member', '2024-11-07 10:23:46'),
(4, 4, 2, 'member', '2024-11-07 10:23:46'),
(5, 5, 1, 'member', '2024-11-07 10:23:46'),
(6, 1, 1, 'owner', '2024-11-07 10:34:56'),
(7, 2, 3, 'owner', '2024-11-07 10:34:56'),
(8, 3, 2, 'owner', '2024-11-07 10:34:56'),
(9, 4, 1, 'owner', '2024-11-07 10:34:56'),
(10, 5, 3, 'owner', '2024-11-07 10:34:56'),
(29, 64, 1, 'owner', '2024-11-07 11:15:20');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int NOT NULL,
  `task_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` enum('pending','in_progress','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `project_id` int NOT NULL,
  `assigned_user_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `due_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`task_id`, `task_name`, `description`, `status`, `project_id`, `assigned_user_id`, `created_at`, `due_date`) VALUES
(1, 'Database Setup', 'Setup MySQL database and configure Docker', 'completed', 1, 1, '2024-11-07 10:30:01', '2024-11-09'),
(2, 'User Authentication', 'Develop login/logout functionality with MySQL validation', 'completed', 1, 2, '2024-11-07 10:30:01', '2024-11-08'),
(3, 'Frontend Redesign', 'Update the frontend with a new color scheme and layout', 'pending', 2, 3, '2024-11-07 10:30:01', '2024-11-21'),
(4, 'Data Extraction', 'Extract data from the legacy system for migration', 'pending', 3, 2, '2024-11-07 10:30:01', '2025-01-22'),
(5, 'Data Cleaning', 'Clean and structure data for compatibility in the new system', 'pending', 3, 2, '2024-11-07 10:30:01', '2024-11-30'),
(6, 'Security Vulnerability Scan', 'Scan systems for potential vulnerabilities', 'in_progress', 4, 1, '2024-11-07 10:30:01', '2025-03-11'),
(7, 'Payment Gateway Integration', 'Set up payment gateway with necessary API calls', 'in_progress', 5, 1, '2024-11-07 10:30:01', '2024-11-26'),
(8, 'User Registration Form', 'Build a user registration form with data validation', 'pending', 5, 3, '2024-11-07 10:30:01', '2024-11-23'),
(13, 'Create Bug Models', 'Design and implement different bug models (e.g., coding errors, syntax bugs) that players can interact with in the game.', 'pending', 64, 1, '2024-11-07 11:16:06', '2024-11-09'),
(14, 'Build Zapping Mechanism', 'Develop the functionality for \"zapping\" the bugs, including animations and sound effects when a bug is hit.', 'in_progress', 64, 1, '2024-11-07 11:16:27', '2024-11-19'),
(15, 'Implement Level Progression', 'Add a system where the difficulty of the game increases with each level, introducing more complex bugs to zap.', 'in_progress', 64, 1, '2024-11-07 11:16:52', '2024-11-14'),
(17, 'Conduct Vulnerability Assessment', 'Scan all systems for known vulnerabilities and identify areas that require immediate attention or patching.', 'pending', 4, 1, '2024-11-07 11:19:02', '2024-11-26'),
(18, 'Review Access Control Policies', 'Assess user access controls and permissions to ensure that sensitive data is protected and only authorized personnel have access.', 'in_progress', 4, 2, '2024-11-07 11:19:22', '2024-11-12'),
(19, 'Perform Phishing Simulation', 'Simulate phishing attacks to test the employees\' ability to recognize suspicious emails and improve overall security awareness.', 'completed', 4, 2, '2024-11-07 11:19:42', '2024-11-05'),
(20, 'Generate Compliance Report', 'Compile a comprehensive report on the findings, including risks, vulnerabilities, and recommendations for achieving industry compliance.', 'in_progress', 4, 1, '2024-11-07 11:19:59', '2024-11-21'),
(21, 'Design Product Catalog', 'Create a user-friendly interface to display products, including filters by category, size, and price.', 'pending', 5, 1, '2024-11-07 11:21:39', '2024-11-29'),
(22, 'Implement User Registration and Login', 'Develop functionality for user sign-up, login, and password recovery with secure authentication methods.', 'in_progress', 5, 3, '2024-11-07 11:21:56', '2024-11-07'),
(23, 'Set Up Product Database', 'Build a database to store product details such as name, price, description, images, and inventory count.', 'pending', 5, 1, '2024-11-07 11:22:19', '2024-12-06'),
(24, 'Develop Shopping Cart', 'Implement a shopping cart system that allows users to add, remove, and update quantities of products.', 'pending', 5, 3, '2024-11-07 11:22:38', '2025-01-22'),
(25, 'Integrate Payment Gateway', 'Integrate a secure payment gateway (e.g., Stripe, PayPal) for processing customer payments.', 'completed', 5, 3, '2024-11-07 11:22:57', '2024-10-01'),
(26, 'Implement Order Management System', 'Create a system for managing orders, including order tracking, status updates, and shipment processing.', 'in_progress', 5, 1, '2024-11-07 11:23:14', '2024-11-18'),
(27, 'Set Up Customer Reviews', 'Allow users to leave reviews and ratings for products, including moderating and displaying these reviews.', 'in_progress', 5, 3, '2024-11-07 11:23:35', '2024-11-21'),
(28, 'Build User Profile Page', 'Design a user profile page where customers can view their order history, saved payment methods, and shipping addresses.', 'completed', 5, 1, '2024-11-07 11:23:53', '2024-10-31'),
(29, 'Analyze Legacy Data Structure', 'Review and document the existing data structure in the legacy system to ensure proper mapping to the new platform.', 'in_progress', 3, 2, '2024-11-07 11:25:41', '2024-11-29'),
(30, 'Develop Data Transformation Scripts', 'Write scripts to transform data from the legacy format into the new system’s format, ensuring compatibility and consistency.', 'in_progress', 3, 2, '2024-11-07 11:26:01', '2024-11-28'),
(31, 'Conduct Data Validation and Testing', 'Perform extensive testing to verify that migrated data is accurate, complete, and functional in the new system.', 'pending', 3, 3, '2024-11-07 11:26:26', '2025-01-01'),
(32, 'Make Presentation', 'Present what the app can do to our class and teacher.', 'in_progress', 1, 1, '2024-11-07 11:49:50', '2024-11-07'),
(33, 'Create Project Dashboard UI', 'Design and develop the project dashboard, allowing users to view all their projects and their current status in a clean and organized layout.', 'completed', 1, 3, '2024-11-07 11:50:55', '2024-11-10'),
(34, 'Build Task Assignment Functionality', 'Allow users to assign tasks to specific team members within projects, with the ability to track task status and due dates.', 'completed', 1, 2, '2024-11-07 11:51:13', '2024-11-11'),
(35, 'Integrate Task Notification System', 'Implement notifications to alert users about upcoming deadlines, task assignments, or updates to projects they are involved in.', 'pending', 1, 1, '2024-11-07 11:51:29', '2024-12-07'),
(36, 'Generate Project and Task Reports', 'Develop a reporting feature that lets users generate and download reports about project progress, completed tasks, and team performance.', 'pending', 1, 1, '2024-11-07 11:51:45', '2024-11-20'),
(37, 'Live Demo', 'the one you are watching right now', 'in_progress', 1, 1, '2024-11-07 11:52:23', '2024-11-07'),
(38, 'Code Review', 'Explain a part of the code that you wrote by yourself. ', 'completed', 1, 3, '2024-11-07 11:53:04', '2024-11-07');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `date`) VALUES
(1, 'Alex', 'alex', '2024-11-07 10:35:44'),
(2, 'Fynn', 'fynn', '2024-11-07 10:35:51'),
(3, 'Moritz', 'moritz', '2024-11-07 10:35:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `fk_owner` (`owner_id`);

--
-- Indexes for table `project_users`
--
ALTER TABLE `project_users`
  ADD PRIMARY KEY (`project_users_id`),
  ADD KEY `fk_projects` (`project_id`),
  ADD KEY `fk_users` (`user_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `fk_project` (`project_id`),
  ADD KEY `fk_user` (`assigned_user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `project_users`
--
ALTER TABLE `project_users`
  MODIFY `project_users_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_owner` FOREIGN KEY (`owner_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `project_users`
--
ALTER TABLE `project_users`
  ADD CONSTRAINT `fk_projects` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`assigned_user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
