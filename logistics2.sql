-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: May 26, 2025 at 10:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `logistics2`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `type` enum('1','2','3','4','5','6','7','8','9') NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `email`, `username`, `password`, `type`, `date_created`) VALUES
(4, 'samuel@admin.com', 'samuel', 'samuelpogi', '1', '2025-05-20 17:21:40');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `certificate` text DEFAULT NULL,
  `equipment_license` varchar(255) DEFAULT NULL,
  `warranty` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_deliveries`
--

CREATE TABLE `audit_deliveries` (
  `audit_delivery_id` int(11) NOT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  `delivery_status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_document`
--

CREATE TABLE `audit_document` (
  `audit_doc_id` int(11) NOT NULL,
  `delivery_report_id` int(11) DEFAULT NULL,
  `vendor_report_id` int(11) DEFAULT NULL,
  `vehicle_report_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_procurement`
--

CREATE TABLE `audit_procurement` (
  `audit_proc_id` int(11) NOT NULL,
  `bill_number` varchar(100) DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `item_id` varchar(100) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `purchase_bills` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_procurement`
--

INSERT INTO `audit_procurement` (`audit_proc_id`, `bill_number`, `supplier`, `purchase_date`, `item_id`, `item_name`, `quantity`, `unit_price`, `total_price`, `purchase_bills`, `status`) VALUES
(1, '1001', '	ABC Supplies', '2025-05-02', '501', '	Printer Ink', 10, 350.00, 3500.00, '{\r\n    \"vendor_id\": 1,\r\n    \"bill_number\": \"1001\",\r\n    \"supplier\": \"ABC Supplies\",\r\n    \"purchase_date\": \"2025-05-02\",\r\n    \"item_id\": \"501\",\r\n    \"item_name\": \"Printer Ink\",\r\n    \"quantity\": 10,\r\n    \"unit_price\": 350.00,\r\n    \"total_price\": 3500.00\r\n}', 'Approved'),
(2, '1002', 'HBO', '2025-05-21', '502', 'Vics', 4, 10.00, 40.00, NULL, 'Approved'),
(7, '1003', 'HBW', '2025-05-31', '503', 'Inhaler', 1, 49.00, 49.00, NULL, 'Approved'),
(8, '1004', 'HBC', '2025-05-01', '504', 'Oxy', 5, 50.00, 250.00, NULL, 'Pending'),
(9, '1005', 'sha101', '2025-05-22', '505', 'dextrose', 5, 370.00, 1850.00, NULL, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `audit_vehicle`
--

CREATE TABLE `audit_vehicle` (
  `audit_vehicle_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `accidental_risk_level` varchar(100) DEFAULT NULL,
  `available_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_vendor`
--

CREATE TABLE `audit_vendor` (
  `audit_vendor_id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `performance_score` decimal(5,2) DEFAULT NULL,
  `contract_compliance_review` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_list`
--

CREATE TABLE `car_list` (
  `vehicle_id` int(11) NOT NULL,
  `car_name` varchar(100) DEFAULT NULL,
  `plate_name` varchar(50) DEFAULT NULL,
  `availability_status` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_list`
--

INSERT INTO `car_list` (`vehicle_id`, `car_name`, `plate_name`, `availability_status`, `type`) VALUES
(1, 'Toyota HiAce', 'PCB-4930', 'available', 'Van');

-- --------------------------------------------------------

--
-- Table structure for table `contract_and_compliance_management`
--

CREATE TABLE `contract_and_compliance_management` (
  `contract_id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `contract_start_date` date DEFAULT NULL,
  `contract_type` varchar(50) DEFAULT NULL,
  `renewal_status` varchar(50) DEFAULT NULL,
  `contract_end_date` date DEFAULT NULL,
  `compliance_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_history`
--

CREATE TABLE `delivery_history` (
  `delivery_id` int(11) NOT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `delivery_logs` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_reports`
--

CREATE TABLE `delivery_reports` (
  `report_id` int(11) NOT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  `performance_score` decimal(5,2) DEFAULT NULL,
  `driver_rating` decimal(3,2) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `success_rate` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_schedule_requests`
--

CREATE TABLE `delivery_schedule_requests` (
  `id` int(11) NOT NULL,
  `time_slot` varchar(100) NOT NULL,
  `delivery_type` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `frequency` varchar(50) NOT NULL,
  `responsible_staff` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_schedule_requests`
--

INSERT INTO `delivery_schedule_requests` (`id`, `time_slot`, `delivery_type`, `department`, `frequency`, `responsible_staff`, `status`, `requested_at`) VALUES
(1, '5:30 AM', 'Equipment', 'HR2', 'Daily', 'Logistic 2', 'pending', '2025-05-22 05:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `driver_id` int(11) NOT NULL,
  `frist_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_hired` date DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fleet_management`
--

CREATE TABLE `fleet_management` (
  `schedule_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `exception_notes` text DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement`
--

CREATE TABLE `procurement` (
  `procurement_id` int(11) NOT NULL,
  `purchase_receipt` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `procurement`
--

INSERT INTO `procurement` (`procurement_id`, `purchase_receipt`) VALUES
(1, '{\"vendor_id\":1,\"bill_number\":\"1001\",\"supplier\":\"ABC Supplies\",\"purchase_date\":\"2025-05-02\",\"item_id\":\"501\",\"item_name\":\"Printer Ink\",\"quantity\":10,\"unit_price\":350,\"total_price\":3500}');

-- --------------------------------------------------------

--
-- Table structure for table `procurement_receipts`
--

CREATE TABLE `procurement_receipts` (
  `id` int(11) NOT NULL,
  `procurement_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `route_id` int(11) NOT NULL,
  `route_name` varchar(100) DEFAULT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `destination` varchar(100) DEFAULT NULL,
  `distance_km` decimal(10,2) DEFAULT NULL,
  `estimated_time` time DEFAULT NULL,
  `route_description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracking_monitoring`
--

CREATE TABLE `tracking_monitoring` (
  `tracking_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `location_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_logs`
--

CREATE TABLE `vehicle_logs` (
  `log_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `date_time_of_usage` datetime DEFAULT NULL,
  `trip_logs` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_reservation`
--

CREATE TABLE `vehicle_reservation` (
  `reservation_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `date_time_to_use` datetime DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_reservation`
--

INSERT INTO `vehicle_reservation` (`reservation_id`, `vehicle_id`, `date_time_to_use`, `purpose`, `status`) VALUES
(1, 1, '2025-05-22 08:00:00', 'Delivery', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_usage_logs`
--

CREATE TABLE `vehicle_usage_logs` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `usage_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `log_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_contract`
--

CREATE TABLE `vendor_contract` (
  `contract_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `contract_start_date` date NOT NULL,
  `contract_expiry_date` date NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_contract`
--

INSERT INTO `vendor_contract` (`contract_id`, `vendor_id`, `contract_start_date`, `contract_expiry_date`, `status`) VALUES
(1, 7, '2025-05-22', '2025-05-30', 'Active'),
(2, 8, '2025-05-22', '2025-05-23', 'Active'),
(3, 9, '2025-05-20', '2025-05-21', 'Expired'),
(4, 10, '2025-05-23', '2025-05-30', 'Rejected'),
(5, 11, '2025-05-02', '2025-05-31', 'Rejected'),
(7, 15, '2025-05-23', '2025-05-31', 'Active'),
(8, 16, '2025-05-22', '2026-05-22', 'Active'),
(9, 17, '2025-05-12', '2025-05-22', 'Active'),
(10, 18, '2025-05-24', '2025-05-29', 'Active'),
(11, 19, '2025-05-21', '2025-05-16', 'Expired');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_profile_management`
--

CREATE TABLE `vendor_profile_management` (
  `vendorprofile_id` int(11) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_registration`
--

CREATE TABLE `vendor_registration` (
  `vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `products` text NOT NULL,
  `business_type` varchar(50) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_registration`
--

INSERT INTO `vendor_registration` (`vendor_id`, `vendor_name`, `email`, `contact_number`, `address`, `products`, `business_type`, `status`) VALUES
(7, 'RMG Hospital Supply, Inc.', 'info@rmgmedical.com', '8722-1033', '4th Floor, The Esquire Center, 412 Gomezville Street corner Guerrero Street, Addition Hills, Mandaluyong City, Philippines', '', 'Private Corporation (ISO-certified)', 'Approved'),
(8, 'Hopital Corp.', 'cs.valdez08@gmail.com', '09423124356', 'Barangay 143 Santa Rosa, Laguna, Philippines', '', 'Pharmacy', 'Approved'),
(9, 'Bambang Pharmaceutical Depot Inc.', 'bambangpharma@depot.com', '8322-1423', 'Bambang Street, Sta. Cruz, Manila, Philippines', '', 'Wholesaler, Importer, Exporter, Distributor', 'Approved'),
(10, 'Patient Care Corporation', 'some@vendor.com', '3475-8756', '124 East Main Avenue, Laguna Technopark, Biñan, Laguna, Philippines', '', ' Multinational Corporation (Medical Equipment Manu', 'Rejected'),
(11, 'rejection test', 'ten@email.com', '1209-1231', '123 heaeas', '', 'someguy', 'Rejected'),
(15, 'Mikey Corp.', 'mikey@corp.com', '1283-4029', 'Block 8 Lot 20 Manila Hills, Muzon, Bulacan', 'Generic and branded medicines, hospital supplies, medical equipment', 'Importer', 'Approved'),
(16, 'BCP hospital.inc', 'hakdog24@gmail.com', '09452517077', 'ph2 pkg5 blk99 lot01', 'medecine', 'pharmacy', 'Approved'),
(17, 'bestlinnk', 'monteves@gmail.com', '09293723627', 'Block 8 Lot 20 Manila Hills', 'Generic and branded medicines, hospital supplies', 'Pharmacys', 'Approved'),
(18, 'Montervs Corp.', 'montervscrop@email.com', '1928-3918', 'Barangay 837 Bagong Silang, Caloocan City', 'ointments', 'pharmcy', 'Approved'),
(19, 'Russel Supply, Inc.', 'russel@supply.com', '01298302918', 'barangay 685 biringan city', 'equipments', 'Retail', 'Approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `audit_deliveries`
--
ALTER TABLE `audit_deliveries`
  ADD PRIMARY KEY (`audit_delivery_id`),
  ADD KEY `delivery_id` (`delivery_id`);

--
-- Indexes for table `audit_document`
--
ALTER TABLE `audit_document`
  ADD PRIMARY KEY (`audit_doc_id`),
  ADD KEY `delivery_report_id` (`delivery_report_id`);

--
-- Indexes for table `audit_procurement`
--
ALTER TABLE `audit_procurement`
  ADD PRIMARY KEY (`audit_proc_id`);

--
-- Indexes for table `audit_vehicle`
--
ALTER TABLE `audit_vehicle`
  ADD PRIMARY KEY (`audit_vehicle_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `audit_vendor`
--
ALTER TABLE `audit_vendor`
  ADD PRIMARY KEY (`audit_vendor_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `contract_compliance_review` (`contract_compliance_review`);

--
-- Indexes for table `car_list`
--
ALTER TABLE `car_list`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- Indexes for table `contract_and_compliance_management`
--
ALTER TABLE `contract_and_compliance_management`
  ADD PRIMARY KEY (`contract_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `delivery_history`
--
ALTER TABLE `delivery_history`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `contract_id` (`contract_id`);

--
-- Indexes for table `delivery_reports`
--
ALTER TABLE `delivery_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `delivery_id` (`delivery_id`);

--
-- Indexes for table `delivery_schedule_requests`
--
ALTER TABLE `delivery_schedule_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `license_number` (`license_number`);

--
-- Indexes for table `fleet_management`
--
ALTER TABLE `fleet_management`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `route_id` (`route_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `procurement`
--
ALTER TABLE `procurement`
  ADD PRIMARY KEY (`procurement_id`);

--
-- Indexes for table `procurement_receipts`
--
ALTER TABLE `procurement_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procurement_id` (`procurement_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `tracking_monitoring`
--
ALTER TABLE `tracking_monitoring`
  ADD PRIMARY KEY (`tracking_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `vehicle_logs`
--
ALTER TABLE `vehicle_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `vehicle_reservation`
--
ALTER TABLE `vehicle_reservation`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `vehicle_usage_logs`
--
ALTER TABLE `vehicle_usage_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `vendor_contract`
--
ALTER TABLE `vendor_contract`
  ADD PRIMARY KEY (`contract_id`),
  ADD KEY `vendor_fk_key` (`vendor_id`);

--
-- Indexes for table `vendor_profile_management`
--
ALTER TABLE `vendor_profile_management`
  ADD PRIMARY KEY (`vendorprofile_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `address` (`address`),
  ADD KEY `contact_number` (`contact_number`);

--
-- Indexes for table `vendor_registration`
--
ALTER TABLE `vendor_registration`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `contact_number` (`contact_number`),
  ADD UNIQUE KEY `address` (`address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_deliveries`
--
ALTER TABLE `audit_deliveries`
  MODIFY `audit_delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_document`
--
ALTER TABLE `audit_document`
  MODIFY `audit_doc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_procurement`
--
ALTER TABLE `audit_procurement`
  MODIFY `audit_proc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `audit_vehicle`
--
ALTER TABLE `audit_vehicle`
  MODIFY `audit_vehicle_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_vendor`
--
ALTER TABLE `audit_vendor`
  MODIFY `audit_vendor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car_list`
--
ALTER TABLE `car_list`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contract_and_compliance_management`
--
ALTER TABLE `contract_and_compliance_management`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_history`
--
ALTER TABLE `delivery_history`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_reports`
--
ALTER TABLE `delivery_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_schedule_requests`
--
ALTER TABLE `delivery_schedule_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fleet_management`
--
ALTER TABLE `fleet_management`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement`
--
ALTER TABLE `procurement`
  MODIFY `procurement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `procurement_receipts`
--
ALTER TABLE `procurement_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracking_monitoring`
--
ALTER TABLE `tracking_monitoring`
  MODIFY `tracking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_logs`
--
ALTER TABLE `vehicle_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_reservation`
--
ALTER TABLE `vehicle_reservation`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicle_usage_logs`
--
ALTER TABLE `vehicle_usage_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_contract`
--
ALTER TABLE `vendor_contract`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vendor_profile_management`
--
ALTER TABLE `vendor_profile_management`
  MODIFY `vendorprofile_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_registration`
--
ALTER TABLE `vendor_registration`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_deliveries`
--
ALTER TABLE `audit_deliveries`
  ADD CONSTRAINT `audit_deliveries_ibfk_1` FOREIGN KEY (`delivery_id`) REFERENCES `delivery_history` (`delivery_id`);

--
-- Constraints for table `audit_document`
--
ALTER TABLE `audit_document`
  ADD CONSTRAINT `audit_document_ibfk_1` FOREIGN KEY (`delivery_report_id`) REFERENCES `delivery_reports` (`report_id`);

--
-- Constraints for table `audit_vehicle`
--
ALTER TABLE `audit_vehicle`
  ADD CONSTRAINT `audit_vehicle_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `car_list` (`vehicle_id`);

--
-- Constraints for table `audit_vendor`
--
ALTER TABLE `audit_vendor`
  ADD CONSTRAINT `audit_vendor_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor_profile_management` (`vendorprofile_id`),
  ADD CONSTRAINT `audit_vendor_ibfk_2` FOREIGN KEY (`contract_compliance_review`) REFERENCES `contract_and_compliance_management` (`contract_id`);

--
-- Constraints for table `contract_and_compliance_management`
--
ALTER TABLE `contract_and_compliance_management`
  ADD CONSTRAINT `contract_and_compliance_management_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor_registration` (`vendor_id`);

--
-- Constraints for table `delivery_history`
--
ALTER TABLE `delivery_history`
  ADD CONSTRAINT `delivery_history_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contract_and_compliance_management` (`contract_id`);

--
-- Constraints for table `delivery_reports`
--
ALTER TABLE `delivery_reports`
  ADD CONSTRAINT `delivery_reports_ibfk_1` FOREIGN KEY (`delivery_id`) REFERENCES `delivery_history` (`delivery_id`);

--
-- Constraints for table `fleet_management`
--
ALTER TABLE `fleet_management`
  ADD CONSTRAINT `fleet_management_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`),
  ADD CONSTRAINT `fleet_management_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`driver_id`),
  ADD CONSTRAINT `fleet_management_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `car_list` (`vehicle_id`);

--
-- Constraints for table `procurement_receipts`
--
ALTER TABLE `procurement_receipts`
  ADD CONSTRAINT `procurement_receipts_ibfk_1` FOREIGN KEY (`procurement_id`) REFERENCES `procurement` (`procurement_id`),
  ADD CONSTRAINT `procurement_receipts_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendor_registration` (`vendor_id`);

--
-- Constraints for table `tracking_monitoring`
--
ALTER TABLE `tracking_monitoring`
  ADD CONSTRAINT `tracking_monitoring_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `car_list` (`vehicle_id`);

--
-- Constraints for table `vehicle_logs`
--
ALTER TABLE `vehicle_logs`
  ADD CONSTRAINT `vehicle_logs_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `car_list` (`vehicle_id`);

--
-- Constraints for table `vehicle_reservation`
--
ALTER TABLE `vehicle_reservation`
  ADD CONSTRAINT `vehicle_reservation_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `car_list` (`vehicle_id`);

--
-- Constraints for table `vehicle_usage_logs`
--
ALTER TABLE `vehicle_usage_logs`
  ADD CONSTRAINT `vehicle_usage_logs_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `car_list` (`vehicle_id`),
  ADD CONSTRAINT `vehicle_usage_logs_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`driver_id`);

--
-- Constraints for table `vendor_contract`
--
ALTER TABLE `vendor_contract`
  ADD CONSTRAINT `vendor_fk_key` FOREIGN KEY (`vendor_id`) REFERENCES `vendor_registration` (`vendor_id`);

--
-- Constraints for table `vendor_profile_management`
--
ALTER TABLE `vendor_profile_management`
  ADD CONSTRAINT `vendor_profile_management_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor_registration` (`vendor_id`),
  ADD CONSTRAINT `vendor_profile_management_ibfk_2` FOREIGN KEY (`address`) REFERENCES `vendor_registration` (`address`),
  ADD CONSTRAINT `vendor_profile_management_ibfk_3` FOREIGN KEY (`contact_number`) REFERENCES `vendor_registration` (`contact_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
