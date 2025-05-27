-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2025 at 12:30 AM
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
  `asset_name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `acquisition_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `project_status` enum('pending','complete','onhold') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset`
--

INSERT INTO `asset` (`asset_id`, `asset_name`, `category`, `status`, `acquisition_date`, `location`, `warehouse_id`, `project_status`, `created_at`, `updated_at`) VALUES
(1, '	ECG Machine', '	Medical Equipment', 'Available', '2025-05-08', '	Cardiology Dept', 1, 'pending', '2025-05-25 19:13:57', '2025-05-25 19:13:57');

-- --------------------------------------------------------

--
-- Table structure for table `asset_log`
--

CREATE TABLE `asset_log` (
  `log_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `action_date` datetime NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_management_documents`
--

CREATE TABLE `asset_management_documents` (
  `record_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `associated_date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bed_assignments`
--

CREATE TABLE `bed_assignments` (
  `assignment_id` int(11) NOT NULL,
  `bed_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `assigned_date` datetime NOT NULL,
  `discharge_date` datetime DEFAULT NULL,
  `status` enum('Active','Discharged','Transferred') NOT NULL DEFAULT 'Active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bed_inventory`
--

CREATE TABLE `bed_inventory` (
  `bed_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `bed_number` varchar(50) NOT NULL,
  `room_number` varchar(50) DEFAULT NULL,
  `status` enum('Available','Allocated','Maintenance','Discarded') NOT NULL DEFAULT 'Available',
  `condition` enum('New','Good','Fair','Poor') NOT NULL DEFAULT 'New',
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
  `status` enum('pending','active','expired','terminated') NOT NULL DEFAULT 'pending',
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `name`, `role`, `department`, `contact_info`, `created_at`, `updated_at`, `status`) VALUES
(4, '	Alice Johnson', '	Software Engineer', 'IT', 'alice.johnson@email.com', '2025-05-25 20:02:35', '2025-05-25 20:02:35', 'Active'),
(5, '	Lisa Fernandez', 'Admin Staff', 'Administration', 'lisa.fernandez@hospital.com,', '2025-05-27 21:37:51', '2025-05-27 21:37:51', 'Resigned');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_suppliers`
--

CREATE TABLE `hospital_suppliers` (
  `supplier_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_suppliers`
--

INSERT INTO `hospital_suppliers` (`supplier_id`, `company_name`, `contact_person`, `email`, `phone`, `address`, `status`, `created_at`) VALUES
(1, '	MedSupply Co.', '	Jane Doe', 'jane.doe@medsupply.com', '0917-123-4567', '123 Health St., Cityville', 'active', '2025-05-27 21:54:30');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_supplies_inventory`
--

CREATE TABLE `hospital_supplies_inventory` (
  `supply_id` int(11) NOT NULL,
  `supply_name` varchar(255) NOT NULL,
  `category` enum('linen','hospital wear','equipment','other') NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `storage_location` varchar(255) NOT NULL,
  `status` enum('In Stock','Out of Stock','Discontinued') DEFAULT 'In Stock'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_supplies_inventory`
--

INSERT INTO `hospital_supplies_inventory` (`supply_id`, `supply_name`, `category`, `quantity`, `unit`, `price`, `manufacturer`, `supplier_id`, `storage_location`, `status`) VALUES
(1, 'Surgical Gloves', 'hospital wear', 500, 'boxes', 1200.00, 'MedEquip Inc.', 1, 'Warehouse A', 'In Stock');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_item`
--

CREATE TABLE `inventory_item` (
  `item_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `inventory_item_name` varchar(255) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `movement_id` int(11) NOT NULL,
  `item_type` enum('Bed','Linen','Other') NOT NULL,
  `item_id` int(11) NOT NULL,
  `from_warehouse_id` int(11) DEFAULT NULL,
  `to_warehouse_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `movement_type` enum('Inbound','Outbound','Transfer') NOT NULL,
  `movement_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_log`
--

CREATE TABLE `item_log` (
  `log_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `linen_inventory`
--

CREATE TABLE `linen_inventory` (
  `linen_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `condition` enum('New','Good','Fair','Poor') NOT NULL DEFAULT 'New',
  `status` enum('Available','In Use','In Laundry','Discarded') NOT NULL DEFAULT 'Available',
  `last_washed_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_role` enum('admin','manager','employee') NOT NULL DEFAULT 'employee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `email`, `password`, `user_role`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', '$2y$10$phPLv7D21Vp5jxJLMu.0i.SfEzxKjpX.Vh.OOh.3v3.NRjVGzE1uu', 'admin', '2025-05-23 21:08:40', '2025-05-23 21:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `maintenance_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
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
-- Table structure for table `maintenance_log`
--

CREATE TABLE `maintenance_log` (
  `log_id` int(11) NOT NULL,
  `maintenance_id` int(11) NOT NULL,
  `maintenance_name` varchar(255) DEFAULT NULL,
  `log_date` datetime NOT NULL,
  `details` text DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_schedule`
--

CREATE TABLE `maintenance_schedule` (
  `schedule_id` int(11) NOT NULL,
  `maintenance_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `frequency` varchar(50) NOT NULL COMMENT 'e.g., Daily, Weekly, Monthly, Quarterly, Annually',
  `next_schedule_date` date NOT NULL,
  `last_schedule_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `medical_history` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement`
--

CREATE TABLE `procurement` (
  `procurement_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('Pending','Approved','Rejected','Completed') NOT NULL DEFAULT 'Pending',
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `procurement`
--

INSERT INTO `procurement` (`procurement_id`, `date`, `total_amount`, `status`, `supplier_id`) VALUES
(1, '2025-05-27', 0.00, 'Pending', 1),
(2, '2025-05-28', 0.00, 'Pending', 2);

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

--
-- Dumping data for table `procurement_item`
--

INSERT INTO `procurement_item` (`item_id`, `procurement_id`, `item_name`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Surgical Masks', 500, 1.50, '2025-05-27 19:29:32', '2025-05-27 19:29:32'),
(2, 2, 'Hospital Beds', 5, 20000.00, '2025-05-27 21:42:31', '2025-05-27 21:42:31');

-- --------------------------------------------------------

--
-- Table structure for table `progress_report`
--

CREATE TABLE `progress_report` (
  `progress_report_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `submission_date` date NOT NULL,
  `progress_status` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `progress_report`
--

INSERT INTO `progress_report` (`progress_report_id`, `project_id`, `submission_date`, `progress_status`, `details`, `created_at`, `updated_at`) VALUES
(2, 1, '2025-05-26', 'Pending', 'Initial setup completed. Awaiting team review.', '2025-05-25 20:28:54', '2025-05-25 20:28:54'),
(3, 2, '2025-05-28', 'Pending', 'Final system testing and implementation completed.', '2025-05-27 21:40:07', '2025-05-27 21:40:07');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` int(11) NOT NULL,
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

INSERT INTO `project` (`project_id`, `name`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'New Patient Management System', '2025-05-26', '2025-05-28', 'Pending', '2025-05-25 19:28:19', '2025-05-25 19:28:19'),
(2, 'Patient Record System', '2025-05-28', '2025-05-29', 'Pending', '2025-05-27 21:36:05', '2025-05-27 21:36:05');

-- --------------------------------------------------------

--
-- Table structure for table `project_documentation`
--

CREATE TABLE `project_documentation` (
  `doc_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `doc_name` varchar(255) NOT NULL,
  `doc_type` varchar(100) NOT NULL,
  `submission_date` date NOT NULL,
  `review_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `compliance_status` enum('compliant','non-compliant') DEFAULT 'compliant',
  `last_reviewed_by` varchar(255) DEFAULT NULL,
  `last_reviewed_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_documentation`
--

INSERT INTO `project_documentation` (`doc_id`, `project_id`, `doc_name`, `doc_type`, `submission_date`, `review_status`, `compliance_status`, `last_reviewed_by`, `last_reviewed_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'System Requirements', 'Planning Document', '2025-05-28', 'approved', 'compliant', NULL, NULL, '2025-05-27 16:43:11', '2025-05-27 16:43:11');

-- --------------------------------------------------------

--
-- Table structure for table `project_employee`
--

CREATE TABLE `project_employee` (
  `project_employee_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `bill_number` varchar(100) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `purchase_date` date NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `bill_number`, `supplier`, `purchase_date`, `item_id`, `item_name`, `quantity`, `unit_price`, `total_price`) VALUES
(1, '	BN-20230501', 'MedSupplies Inc', '2025-05-28', '101', '	Surgical Masks', 500, 1.50, 750.00);

-- --------------------------------------------------------

--
-- Table structure for table `registration_info`
--

CREATE TABLE `registration_info` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `resources_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `quantity` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`resources_id`, `name`, `type`, `availability`, `quantity`, `project_id`, `created_at`, `updated_at`) VALUES
(3, 'Laptop Dell XPS 15', '	Hardware', 0, 10, 1, '2025-05-25 20:06:04', '2025-05-25 20:06:04'),
(4, 'ROG LAPTOP', 'Hardware', 5, 10, 2, '2025-05-27 21:38:38', '2025-05-27 21:38:38');

-- --------------------------------------------------------

--
-- Table structure for table `risks`
--

CREATE TABLE `risks` (
  `risks_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `probability` varchar(20) DEFAULT NULL,
  `impact_level` varchar(20) DEFAULT NULL,
  `mitigation_plan` text DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `risks`
--

INSERT INTO `risks` (`risks_id`, `name`, `description`, `probability`, `impact_level`, `mitigation_plan`, `project_id`, `created_at`, `updated_at`) VALUES
(2, '	Data Breach', 'Unauthorized access to sensitive data', 'High', 'Critical', 'Implement strict access controls', 1, '2025-05-25 20:16:17', '2025-05-25 20:16:17'),
(3, 'Cyber Attack', 'Potential attack on hospital servers', 'High', 'Critical', 'Conduct regular penetration testing and staff training', 2, '2025-05-27 21:39:41', '2025-05-27 21:39:41');

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

--
-- Dumping data for table `shipment`
--

INSERT INTO `shipment` (`shipment_id`, `shipment_date`, `status`, `type`, `warehouse_id`, `created_at`, `updated_at`) VALUES
(1, '2025-05-26', 'Pending', 'SAMPLE TYPE', 1, '2025-05-26 08:36:57', '2025-05-26 08:36:57'),
(2, '2025-05-24', 'Shipped', 'sadas', 2, '2025-05-26 08:40:56', '2025-05-26 08:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `spareparts`
--

CREATE TABLE `spareparts` (
  `spareparts_id` int(11) NOT NULL,
  `spareparts_name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
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

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `contact_info`, `rating`, `created_at`, `updated_at`) VALUES
(1, 'MediSupply Inc.', '	555-1111 / medsup@hmail.com', 4.8, '2025-05-27 07:39:38', '2025-05-27 07:39:38'),
(2, 'MediTech Supplies', '	medi-tech@gmail.com,', 4.8, '2025-05-27 21:40:56', '2025-05-27 21:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`task_id`, `project_id`, `name`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(0, 1, 'Design Database Schema', '2025-05-26', '2025-05-28', 'Pending', '2025-05-25 20:01:14', '2025-05-25 20:01:14'),
(0, 2, '	Design Patient Form', '2025-05-28', '2025-05-29', 'Pending', '2025-05-27 21:36:45', '2025-05-27 21:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `warehouse_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `warehouse_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`warehouse_id`, `location`, `capacity`, `created_at`, `updated_at`, `warehouse_name`) VALUES
(1, 'Commonwealth Gen. Ave', 5000, '2025-05-25 18:49:41', '2025-05-25 18:49:41', ''),
(2, 'SAMPLE WAREHOUSE', 255, '2025-05-26 08:36:32', '2025-05-26 08:36:32', '');

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
-- Indexes for table `asset_log`
--
ALTER TABLE `asset_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `asset_management_documents`
--
ALTER TABLE `asset_management_documents`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `bed_assignments`
--
ALTER TABLE `bed_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `bed_id` (`bed_id`);

--
-- Indexes for table `bed_inventory`
--
ALTER TABLE `bed_inventory`
  ADD PRIMARY KEY (`bed_id`),
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
-- Indexes for table `hospital_suppliers`
--
ALTER TABLE `hospital_suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `hospital_supplies_inventory`
--
ALTER TABLE `hospital_supplies_inventory`
  ADD PRIMARY KEY (`supply_id`),
  ADD KEY `fk_supplier` (`supplier_id`);

--
-- Indexes for table `inventory_item`
--
ALTER TABLE `inventory_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`movement_id`),
  ADD KEY `from_warehouse_id` (`from_warehouse_id`),
  ADD KEY `to_warehouse_id` (`to_warehouse_id`);

--
-- Indexes for table `item_log`
--
ALTER TABLE `item_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `linen_inventory`
--
ALTER TABLE `linen_inventory`
  ADD PRIMARY KEY (`linen_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

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
  ADD UNIQUE KEY `asset_id` (`asset_id`);

--
-- Indexes for table `maintenance_log`
--
ALTER TABLE `maintenance_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `maintenance_schedule`
--
ALTER TABLE `maintenance_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `maintenance_id` (`maintenance_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`);

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
-- Indexes for table `progress_report`
--
ALTER TABLE `progress_report`
  ADD PRIMARY KEY (`progress_report_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `project_documentation`
--
ALTER TABLE `project_documentation`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `project_id` (`project_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration_info`
--
ALTER TABLE `registration_info`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`resources_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `risks`
--
ALTER TABLE `risks`
  ADD PRIMARY KEY (`risks_id`);

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
  ADD PRIMARY KEY (`spareparts_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`warehouse_id`);

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
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `asset_log`
--
ALTER TABLE `asset_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_management_documents`
--
ALTER TABLE `asset_management_documents`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bed_assignments`
--
ALTER TABLE `bed_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bed_inventory`
--
ALTER TABLE `bed_inventory`
  MODIFY `bed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hospital_suppliers`
--
ALTER TABLE `hospital_suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hospital_supplies_inventory`
--
ALTER TABLE `hospital_supplies_inventory`
  MODIFY `supply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory_item`
--
ALTER TABLE `inventory_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `movement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_log`
--
ALTER TABLE `item_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `linen_inventory`
--
ALTER TABLE `linen_inventory`
  MODIFY `linen_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `maintenance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_log`
--
ALTER TABLE `maintenance_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_schedule`
--
ALTER TABLE `maintenance_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `progress_report`
--
ALTER TABLE `progress_report`
  MODIFY `progress_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_documentation`
--
ALTER TABLE `project_documentation`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_employee`
--
ALTER TABLE `project_employee`
  MODIFY `project_employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `registration_info`
--
ALTER TABLE `registration_info`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `resources_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `risks`
--
ALTER TABLE `risks`
  MODIFY `risks_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `shipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `spareparts`
--
ALTER TABLE `spareparts`
  MODIFY `spareparts_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Constraints for table `asset_log`
--
ALTER TABLE `asset_log`
  ADD CONSTRAINT `asset_log_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `asset` (`asset_id`);

--
-- Constraints for table `asset_management_documents`
--
ALTER TABLE `asset_management_documents`
  ADD CONSTRAINT `asset_management_documents_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `asset` (`asset_id`) ON DELETE CASCADE;

--
-- Constraints for table `bed_assignments`
--
ALTER TABLE `bed_assignments`
  ADD CONSTRAINT `bed_assignments_ibfk_1` FOREIGN KEY (`bed_id`) REFERENCES `bed_inventory` (`bed_id`) ON DELETE CASCADE;

--
-- Constraints for table `bed_inventory`
--
ALTER TABLE `bed_inventory`
  ADD CONSTRAINT `bed_inventory_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouse_id`) ON DELETE CASCADE;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `registration_info` (`company_id`);

--
-- Constraints for table `hospital_supplies_inventory`
--
ALTER TABLE `hospital_supplies_inventory`
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `hospital_suppliers` (`supplier_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_item`
--
ALTER TABLE `inventory_item`
  ADD CONSTRAINT `inventory_item_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouse_id`);

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouse` (`warehouse_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_ibfk_2` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouse` (`warehouse_id`) ON DELETE SET NULL;

--
-- Constraints for table `item_log`
--
ALTER TABLE `item_log`
  ADD CONSTRAINT `item_log_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory_item` (`item_id`);

--
-- Constraints for table `linen_inventory`
--
ALTER TABLE `linen_inventory`
  ADD CONSTRAINT `linen_inventory_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouse_id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_schedule`
--
ALTER TABLE `maintenance_schedule`
  ADD CONSTRAINT `maintenance_schedule_ibfk_1` FOREIGN KEY (`maintenance_id`) REFERENCES `maintenance` (`maintenance_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_schedule_ibfk_2` FOREIGN KEY (`asset_id`) REFERENCES `maintenance` (`asset_id`) ON DELETE CASCADE;

--
-- Constraints for table `procurement`
--
ALTER TABLE `procurement`
  ADD CONSTRAINT `procurement_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `procurement_item`
--
ALTER TABLE `procurement_item`
  ADD CONSTRAINT `procurement_item_ibfk_1` FOREIGN KEY (`procurement_id`) REFERENCES `procurement` (`procurement_id`);

--
-- Constraints for table `progress_report`
--
ALTER TABLE `progress_report`
  ADD CONSTRAINT `progress_report_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `project_documentation`
--
ALTER TABLE `project_documentation`
  ADD CONSTRAINT `project_documentation_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE;

--
-- Constraints for table `project_employee`
--
ALTER TABLE `project_employee`
  ADD CONSTRAINT `project_employee_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`),
  ADD CONSTRAINT `project_employee_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouse_id`);

--
-- Constraints for table `spareparts`
--
ALTER TABLE `spareparts`
  ADD CONSTRAINT `spareparts_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
