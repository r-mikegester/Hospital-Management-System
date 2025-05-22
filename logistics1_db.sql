-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 07:54 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `logistics1_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval`
--

CREATE TABLE `approval` (
  `approval_id` int(11) NOT NULL,
  `procurement_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `approval_date` date NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset`
--

CREATE TABLE `asset` (
  `asset_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `acquisition_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `contract_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('active','expired','terminated','pending') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventoryitem`
--

CREATE TABLE `inventoryitem` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventorymovement`
--

CREATE TABLE `inventorymovement` (
  `log_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cpassword` varchar(255) NOT NULL,
  `user_role` enum('admin','user','manager') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `email`, `password`, `cpassword`, `user_role`, `created_at`, `updated_at`) VALUES
(5, 'supp.mikegester@gmail.com', '$2y$10$lZ/i3wbO1nqsHSzrSNHZvOn4MUTsRTOVxCFYbcYpyQa1imTOyCmrm', '', 'admin', '2025-05-21 18:20:51', '2025-05-21 18:20:51'),
(6, 'admin@gmail.com', '$2y$10$0Mp3QAQfa/4/HrnrApaX9.M/ctXvsfMeJUiCb6u/9cDvBNySl.W1W', '', 'admin', '2025-05-22 01:59:07', '2025-05-22 01:59:07');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `maintenance_id` int(11) NOT NULL,
  `asset_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenancelog`
--

CREATE TABLE `maintenancelog` (
  `log_id` int(11) NOT NULL,
  `maintenance_id` int(11) DEFAULT NULL,
  `log_date` datetime NOT NULL,
  `details` text DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `manager_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement`
--

CREATE TABLE `procurement` (
  `procurement_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `procurement_date` date NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `procurement`
--

INSERT INTO `procurement` (`procurement_id`, `supplier_id`, `procurement_date`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(2, NULL, '2025-05-01', 250.00, 'Pending', '2025-05-22 03:54:36', '2025-05-22 03:54:36');

-- --------------------------------------------------------

--
-- Table structure for table `procurement_item`
--

CREATE TABLE `procurement_item` (
  `item_id` int(11) NOT NULL,
  `procurement_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progressreport`
--

CREATE TABLE `progressreport` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `submission_date` date NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `name`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(9, 'dasdas', '2025-05-03', '2025-05-03', 'Not Started', '2025-05-21 11:33:42', '2025-05-21 11:33:42'),
(34, 'wewewewe', '3123-12-21', '1323-12-31', 'Ongoing', '2025-05-22 00:13:06', '2025-05-22 00:13:59'),
(35, 'wewewewasdasdasdasd', '1212-12-12', '1212-12-12', 'Ongoing', '2025-05-22 00:24:50', '2025-05-22 00:24:50'),
(36, 'denvernigga', '1212-12-12', '0000-00-00', 'Completed', '2025-05-22 00:44:50', '2025-05-22 00:44:50'),
(37, 'niggahi', '1212-12-12', '1212-12-12', 'Ongoing', '2025-05-22 00:48:33', '2025-05-22 00:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `project_employee`
--

CREATE TABLE `project_employee` (
  `project_employee_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `hours_assigned` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `bill_number` int(11) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `purchase_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`bill_number`, `supplier`, `purchase_date`, `item_id`, `item_name`, `quantity`, `unit_price`) VALUES
(1001, '	ABC Supplies', '2025-05-02', 501, '	Printer Ink', 10, 350.00);

-- --------------------------------------------------------

--
-- Table structure for table `registration_info`
--

CREATE TABLE `registration_info` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `products` text DEFAULT NULL,
  `business_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `quantity` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `risks`
--

CREATE TABLE `risks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `probability` varchar(20) DEFAULT NULL,
  `impact` varchar(20) DEFAULT NULL,
  `mitigation_plan` text DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `shipment_id` int(11) NOT NULL,
  `shipment_date` date NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spareparts`
--

CREATE TABLE `spareparts` (
  `part_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL CHECK (`rating` >= 0.0 and `rating` <= 5.0),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `name`, `project_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(20, 'asdasd', 9, '1212-12-12', '1212-12-12', 'Ongoing', '2025-05-22 00:32:23', '2025-05-22 00:32:23'),
(21, 'asdasdasd', 9, '1212-12-12', '1212-12-12', 'Ongoing', '2025-05-22 00:33:25', '2025-05-22 00:33:25'),
(22, 'asdasd', 34, '1212-12-12', '0000-00-00', 'Completed', '2025-05-22 00:38:49', '2025-05-22 00:38:49'),
(23, 'asdasdasd', 36, '1212-12-12', '1212-12-12', 'Ongoing', '2025-05-22 00:45:12', '2025-05-22 00:45:12'),
(24, 'qweqweqwe', 36, '1212-12-12', '1212-12-12', 'Ongoing', '2025-05-22 00:46:58', '2025-05-22 00:46:58'),
(25, 'asdasdasdasdasd', 34, '1212-12-12', '1212-12-12', 'Ongoing', '2025-05-22 00:48:15', '2025-05-22 00:48:15'),
(26, 'werwtite', 37, '1212-12-12', '1212-12-12', 'Pending', '2025-05-22 00:53:04', '2025-05-22 00:53:04'),
(27, 'tite', 37, '0000-00-00', '0000-00-00', 'Ongoing', '2025-05-22 00:56:31', '2025-05-22 00:56:31'),
(28, '232323', 36, '1212-12-12', '0122-12-12', 'Completed', '2025-05-22 01:04:38', '2025-05-22 01:04:38'),
(29, 'asdasdasd', 36, '0122-12-12', '0222-12-12', 'Completed', '2025-05-22 01:05:25', '2025-05-22 01:05:25');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `warehouse_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval`
--
ALTER TABLE `approval`
  ADD PRIMARY KEY (`approval_id`),
  ADD KEY `procurement_id` (`procurement_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `asset`
--
ALTER TABLE `asset`
  ADD PRIMARY KEY (`asset_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`contract_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `inventoryitem`
--
ALTER TABLE `inventoryitem`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `inventorymovement`
--
ALTER TABLE `inventorymovement`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`maintenance_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `maintenancelog`
--
ALTER TABLE `maintenancelog`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `maintenance_id` (`maintenance_id`),
  ADD KEY `performed_by` (`performed_by`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`);

--
-- Indexes for table `procurement`
--
ALTER TABLE `procurement`
  ADD PRIMARY KEY (`procurement_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `procurement_item`
--
ALTER TABLE `procurement_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `procurement_id` (`procurement_id`);

--
-- Indexes for table `progressreport`
--
ALTER TABLE `progressreport`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_employee`
--
ALTER TABLE `project_employee`
  ADD PRIMARY KEY (`project_employee_id`),
  ADD UNIQUE KEY `project_id` (`project_id`,`employee_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `registration_info`
--
ALTER TABLE `registration_info`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `risks`
--
ALTER TABLE `risks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`shipment_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `spareparts`
--
ALTER TABLE `spareparts`
  ADD PRIMARY KEY (`part_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`warehouse_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval`
--
ALTER TABLE `approval`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset`
--
ALTER TABLE `asset`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventoryitem`
--
ALTER TABLE `inventoryitem`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventorymovement`
--
ALTER TABLE `inventorymovement`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `maintenance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenancelog`
--
ALTER TABLE `maintenancelog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement`
--
ALTER TABLE `procurement`
  MODIFY `procurement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `procurement_item`
--
ALTER TABLE `procurement_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `progressreport`
--
ALTER TABLE `progressreport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `project_employee`
--
ALTER TABLE `project_employee`
  MODIFY `project_employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=502;

--
-- AUTO_INCREMENT for table `registration_info`
--
ALTER TABLE `registration_info`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `risks`
--
ALTER TABLE `risks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `shipment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spareparts`
--
ALTER TABLE `spareparts`
  MODIFY `part_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approval`
--
ALTER TABLE `approval`
  ADD CONSTRAINT `approval_ibfk_1` FOREIGN KEY (`procurement_id`) REFERENCES `procurement` (`procurement_id`),
  ADD CONSTRAINT `approval_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `asset`
--
ALTER TABLE `asset`
  ADD CONSTRAINT `asset_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouse_id`);

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `registration_info` (`company_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventoryitem`
--
ALTER TABLE `inventoryitem`
  ADD CONSTRAINT `inventoryitem_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouse_id`);

--
-- Constraints for table `inventorymovement`
--
ALTER TABLE `inventorymovement`
  ADD CONSTRAINT `inventorymovement_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventoryitem` (`item_id`);

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `asset` (`asset_id`);

--
-- Constraints for table `maintenancelog`
--
ALTER TABLE `maintenancelog`
  ADD CONSTRAINT `maintenancelog_ibfk_1` FOREIGN KEY (`maintenance_id`) REFERENCES `maintenance` (`maintenance_id`),
  ADD CONSTRAINT `maintenancelog_ibfk_2` FOREIGN KEY (`performed_by`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `procurement`
--
ALTER TABLE `procurement`
  ADD CONSTRAINT `procurement_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`);

--
-- Constraints for table `procurement_item`
--
ALTER TABLE `procurement_item`
  ADD CONSTRAINT `procurement_item_ibfk_1` FOREIGN KEY (`procurement_id`) REFERENCES `procurement` (`procurement_id`);

--
-- Constraints for table `progressreport`
--
ALTER TABLE `progressreport`
  ADD CONSTRAINT `progressreport_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `project_employee`
--
ALTER TABLE `project_employee`
  ADD CONSTRAINT `project_employee_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `project_employee_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `risks`
--
ALTER TABLE `risks`
  ADD CONSTRAINT `risks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouse_id`);

--
-- Constraints for table `spareparts`
--
ALTER TABLE `spareparts`
  ADD CONSTRAINT `spareparts_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`),
  ADD CONSTRAINT `spareparts_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouse_id`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD CONSTRAINT `warehouse_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `employee` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
