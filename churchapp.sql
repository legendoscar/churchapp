-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2022 at 12:22 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `churchapp3`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_status`
--

CREATE TABLE `account_status` (
  `id` int(11) NOT NULL,
  `status_name` varchar(255) NOT NULL,
  `other_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_status`
--

INSERT INTO `account_status` (`id`, `status_name`, `other_name`) VALUES
(1, 'Active', 'Active'),
(2, 'Suspended', 'Suspend'),
(3, 'Close Account', 'Close Account');

-- --------------------------------------------------------

--
-- Table structure for table `account_titles`
--

CREATE TABLE `account_titles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account_titles`
--

INSERT INTO `account_titles` (`id`, `name`, `created_by`, `status`, `url_id`, `date_created`, `last_updated`) VALUES
(1, 'Mr.', 0, '', '0987y6tfr23', '2022-05-29 10:12:21', '2022-06-10 00:36:51'),
(2, 'Mrs.', 0, '', 'kjh234512301', '2022-05-29 10:12:21', '2022-05-29 11:40:40'),
(3, 'Pst.', 0, '', '0987y6tfr222', '2022-05-29 10:12:21', '2022-05-29 11:40:37'),
(4, 'Sis.', 0, '', 'kjh2345000', '2022-05-29 10:12:21', '2022-05-29 11:40:43');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `code`) VALUES
(1, 'Access Bank', '044'),
(2, 'Citibank', '023'),
(3, 'Diamond Bank', '063'),
(5, 'Ecobank Nigeria', '050'),
(6, 'Fidelity Bank Nigeria', '070'),
(7, 'First Bank of Nigeria', '011'),
(8, 'First City Monument Bank', '214'),
(9, 'Guaranty Trust Bank', '058'),
(10, 'Heritage Bank Plc', '030'),
(11, 'Jaiz Bank', '301'),
(12, 'Keystone Bank Limited', '082'),
(13, 'Providus Bank Plc', '101'),
(14, 'Polaris Bank', '076'),
(15, 'Stanbic IBTC Bank Nigeria Limited', '221'),
(16, 'Standard Chartered Bank', '068'),
(17, 'Sterling Bank', '232'),
(18, 'Suntrust Bank Nigeria Limited', '100'),
(19, 'Union Bank of Nigeria', '032'),
(20, 'United Bank for Africa', '033'),
(21, 'Unity Bank Plc', '215'),
(22, 'Wema Bank', '035'),
(23, 'Zenith Bank', '057');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL DEFAULT 3,
  `account_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `method_id`, `account_name`, `account_number`, `bank_name`, `bank_id`, `status`, `url_id`, `date_created`) VALUES
(12, 1, 'CASH', '', '', 0, '', '876543w', '2022-07-23 18:01:18'),
(13, 3, 'DIOCESE OF ABA (ANGLICAN COMMUNION)', '0123456789', 'Access Bank', 1, '', 'mnbvcx', '2022-08-15 17:33:05'),
(14, 2, 'GT BANK - POS', '', '', 0, '', 'mjhgredc', '2022-07-23 19:04:07'),
(15, 4, 'CREDIT', '', '', 0, 'disabled', 'u7y6trfg', '2022-07-23 18:01:36'),
(16, 5, 'CHEQUE', '', '', 0, '', '87y6tre', '2022-07-23 18:01:26');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `created_by`, `status`, `url_id`, `date_created`, `last_updated`) VALUES
(1, 'Umuahia Branch', 0, '', '0987654', '2022-05-29 10:48:54', '2022-05-29 12:19:09'),
(2, 'Isieke Branch', 0, '', '345678', '2022-05-29 10:48:54', '2022-05-29 12:12:08'),
(7, 'aaa', 21721798276334, 'deleted', '652c6d35c88e9d1de362a8a75df38f93', '2022-08-16 06:44:28', '2022-08-16 06:45:05'),
(8, 'mk', 21721798276334, 'deleted', 'e554be7642a92b8dbb219a7baeb020de', '2022-08-16 06:44:59', '2022-08-16 06:45:02'),
(9, 'aaa', 21721798276334, 'deleted', 'ea8fb17239c21d750957c7baec2c7cdc', '2022-08-16 06:45:37', '2022-08-16 06:45:41');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `othername` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_id`, `surname`, `firstname`, `othername`, `phone`, `address`, `email_address`, `date_created`, `last_updated`) VALUES
(1, 'CST001', 'JUST A CUSTOMER', '', '', '', '', '', '2022-07-23 15:51:06', '2022-07-23 15:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_by`, `status`, `url_id`, `date_created`, `last_updated`) VALUES
(1, 'MUSIC MINISTRY', 0, '', '0987y6tfr', '2022-05-29 10:12:21', '2022-06-09 23:44:53'),
(2, 'CHILDREN MINISTRY', 0, '', 'kjh2345', '2022-05-29 10:12:21', '2022-05-29 12:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `name`, `created_by`, `status`, `url_id`, `date_created`, `last_updated`) VALUES
(1, 'Youth Pastor', 0, '', '', '2022-05-29 10:48:54', '2022-05-29 11:43:44'),
(2, 'Patron', 0, '', '', '2022-05-29 10:48:54', '2022-05-29 11:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `employee_bank_accounts`
--

CREATE TABLE `employee_bank_accounts` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `payslip_id` varchar(255) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee_payslip_banks`
--

CREATE TABLE `employee_payslip_banks` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `payslip_id` varchar(255) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `payment_bank` int(11) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `excess_status`
--

CREATE TABLE `excess_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug_name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `excess_status`
--

INSERT INTO `excess_status` (`id`, `name`, `slug_name`, `description`, `date_created`, `last_updated`) VALUES
(1, 'Paid', 'paid', 'Customer has been paid / refunded', '2022-03-01 10:30:05', '2022-03-01 10:32:47'),
(2, 'Used', 'used', 'Customer used the excess balance for another purchase', '2022-03-01 10:30:05', '2022-03-01 10:33:08'),
(3, 'Pending', 'pending', 'Excess paid has not been added to', '2022-03-01 10:30:05', '2022-03-01 10:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `excess_track_used_paid_balance`
--

CREATE TABLE `excess_track_used_paid_balance` (
  `id` int(11) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `used_bal` decimal(15,9) NOT NULL,
  `invoice_id` varchar(255) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_initiated` varchar(255) NOT NULL,
  `initiated_by` bigint(20) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `main_date_created` date NOT NULL DEFAULT current_timestamp(),
  `used_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `given_to` varchar(255) NOT NULL,
  `amount` decimal(65,2) NOT NULL,
  `expenses_number` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `expense_date` date NOT NULL DEFAULT current_timestamp(),
  `date_created` date NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_item`
--

CREATE TABLE `expenses_item` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expenses_item`
--

INSERT INTO `expenses_item` (`id`, `name`, `created_by`, `status`, `url_id`, `date_created`, `last_updated`) VALUES
(1, 'FUEL', 0, '', '8765456', '2022-05-30 11:11:24', '2022-06-02 04:37:47'),
(2, 'GENERATOR REPAIR', 0, '', '928373', '2022-05-30 11:11:24', '2022-06-02 04:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `total_paid` decimal(15,3) NOT NULL,
  `is_split` varchar(255) NOT NULL DEFAULT 'no',
  `payment_method` bigint(20) NOT NULL,
  `method_id` bigint(20) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `additional_note` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `date_created` date NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `url_id`, `invoice_number`, `customer_name`, `customer_phone`, `total_qty`, `total_paid`, `is_split`, `payment_method`, `method_id`, `created_by`, `payment_date`, `date_time`, `additional_note`, `status`, `date_created`, `last_updated`) VALUES
(1, '9af155ba5d1ee47afddcd3933caa0f2c', 'RCPT-590512511', 'CST001', '', 0, '19000.000', 'yes', 0, 0, 21721798276334, '0000-00-00', '2022-09-12 05:04:11', 'hello', 1, '2022-09-12', '2022-09-12 05:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_excess_payments`
--

CREATE TABLE `invoice_excess_payments` (
  `id` int(11) NOT NULL,
  `invoice_id` bigint(20) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `excess_amount` decimal(15,9) NOT NULL,
  `excess_status` int(11) NOT NULL DEFAULT 3,
  `main_date` date NOT NULL DEFAULT current_timestamp(),
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_status`
--

CREATE TABLE `invoice_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug_name` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invoice_status`
--

INSERT INTO `invoice_status` (`id`, `name`, `slug_name`, `date_created`, `last_updated`) VALUES
(1, 'Confirmed', 'confirmed', '2022-05-26 15:33:08', '2022-05-26 15:33:08'),
(2, 'Unconfirmed', 'unconfirmed', '2022-05-26 15:33:08', '2022-05-26 15:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_trn_details`
--

CREATE TABLE `invoice_trn_details` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `half_qty` int(11) NOT NULL,
  `rate` decimal(15,3) NOT NULL,
  `stock_price` decimal(15,9) NOT NULL,
  `discount` decimal(15,3) NOT NULL,
  `amount` decimal(15,3) NOT NULL,
  `discountid` varchar(255) NOT NULL,
  `transaction_type` varchar(255) NOT NULL DEFAULT 'wholesale',
  `additional_text` text NOT NULL,
  `date_created` date NOT NULL DEFAULT current_timestamp(),
  `date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `landing_pages`
--

CREATE TABLE `landing_pages` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `landing_pages`
--

INSERT INTO `landing_pages` (`id`, `url`, `page_name`, `date_created`, `last_updated`) VALUES
(1, '../', 'Home', '2021-06-17 18:11:16', '2021-06-20 02:27:42'),
(2, '../stock-report', 'Stock Report', '2021-06-17 18:11:16', '2021-06-20 02:27:47');

-- --------------------------------------------------------

--
-- Table structure for table `max_timing`
--

CREATE TABLE `max_timing` (
  `id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `max_timing`
--

INSERT INTO `max_timing` (`id`, `name`, `date_created`, `last_updated`) VALUES
(1, 5, '2021-12-01 17:28:43', '2021-12-01 17:28:43'),
(2, 10, '2021-12-01 17:28:43', '2021-12-01 17:28:43'),
(3, 15, '2021-12-01 17:28:43', '2021-12-01 17:28:43'),
(4, 20, '2021-12-01 17:28:43', '2021-12-01 17:28:43'),
(5, 25, '2021-12-01 17:28:43', '2021-12-01 17:28:43'),
(6, 30, '2021-12-01 17:28:43', '2021-12-01 17:28:43'),
(7, 0, '2021-12-01 17:28:43', '2021-12-01 17:28:43');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_chart_reports`
--

CREATE TABLE `monthly_chart_reports` (
  `report_id` int(11) NOT NULL,
  `month` varchar(255) NOT NULL,
  `year` varchar(11) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payable_items`
--

CREATE TABLE `payable_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payable_items`
--

INSERT INTO `payable_items` (`id`, `name`, `created_by`, `status`, `url_id`, `date_created`, `last_updated`) VALUES
(1, 'TITHE', 987654, '', 'f1de8b9b5462f2b01bdb9498c8a3ef55', '2022-05-23 18:17:05', '2022-05-30 06:24:09'),
(2, 'MONTHLY CONTRIBUTION', 987654, '', 'fa2e200ffde17e350829e3db20126c54', '2022-05-23 18:17:05', '2022-05-29 10:24:22'),
(3, 'YOUTH WEEK', 987654, '', '401f83d88338e225734bad9a343626a7', '2022-05-23 18:17:05', '2022-05-28 23:16:19'),
(4, 'CHURCH BUILDING', 987654, '', 'a49b9dbe07f215bc0fdbc8703e1464d1', '2022-05-23 18:17:05', '2022-05-28 23:16:25'),
(18, 'WELFARE CONTRIBUTION', 21721798276334, '', 'd8bf6279539b6c97f8a25c6723bd1fab', '2022-05-30 06:16:13', '2022-05-30 06:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `payment_banks`
--

CREATE TABLE `payment_banks` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_banks`
--

INSERT INTO `payment_banks` (`id`, `bank_name`, `url_id`, `date_created`, `last_updated`) VALUES
(2, 'Access Bank', 'bb9876543hgfd', '2022-09-12 10:17:58', '2022-09-12 12:01:16'),
(3, 'Zenith Bank', '6bcd9cbd6d780dff068ff571619048b5', '2022-09-12 12:05:13', '2022-09-12 12:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL,
  `methods` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'main',
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`id`, `methods`, `type`, `date_added`) VALUES
(1, 'Cash', 'main', '2021-03-23 15:35:19'),
(2, 'POS', 'main', '2021-03-23 15:35:19'),
(3, 'Bank Transfer', 'main', '2021-03-23 15:35:19'),
(4, 'Credit', 'main', '2021-03-23 15:35:19'),
(5, 'CHEQUE', 'main', '2021-03-23 15:35:19'),
(6, 'ACC/BAL', 'other_customer_balance', '2021-03-23 15:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `payslips`
--

CREATE TABLE `payslips` (
  `id` int(11) NOT NULL,
  `payslip_number` varchar(255) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `employee_department` int(11) NOT NULL,
  `employee_designation` int(11) NOT NULL,
  `employee_branch` int(11) NOT NULL,
  `monthly_basic_salary` decimal(65,2) NOT NULL,
  `total_amount` decimal(65,2) NOT NULL,
  `payslip_month` varchar(255) NOT NULL,
  `payment_date` date NOT NULL DEFAULT current_timestamp(),
  `additional_note` text NOT NULL,
  `payslip_status` int(11) NOT NULL DEFAULT 2,
  `payment_mode` int(11) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_created_only` date NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `time_sent` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payslip_items`
--

CREATE TABLE `payslip_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `slug_name` varchar(255) NOT NULL,
  `is_default` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_id` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payslip_items`
--

INSERT INTO `payslip_items` (`id`, `name`, `group_name`, `slug_name`, `is_default`, `status`, `url_id`, `created_by`, `date_created`, `last_updated`) VALUES
(1, 'Basic Salary', 'earnings', 'basic_salary', 'default', '', '', 0, '2022-06-04 05:14:31', '2022-06-07 15:39:58'),
(2, 'House Allowance    ', 'earnings', 'house-allowance', '', '', '', 0, '2022-06-04 05:14:31', '2022-06-10 01:27:12'),
(3, 'Late to work', 'deduction', 'late_to_work', '', '', '', 0, '2022-06-04 05:14:31', '2022-06-04 05:14:31'),
(4, 'Loan', 'deduction', 'loan', '', '', '', 0, '2022-06-04 05:14:31', '2022-06-04 05:14:31'),
(5, 'Provident Fund', 'deduction', 'loan', '', '', '', 0, '2022-06-04 05:14:31', '2022-06-07 23:18:45'),
(6, 'Meal Allowance  ', 'earnings', 'meal_allowance', '', '', '', 0, '2022-06-04 05:14:31', '2022-06-07 23:19:29'),
(7, 'Automobile Allowance', 'earnings', 'automobile_allowance', '', '', '', 0, '2022-06-04 05:14:31', '2022-06-04 05:14:31');

-- --------------------------------------------------------

--
-- Table structure for table `payslip_mode_of_payment`
--

CREATE TABLE `payslip_mode_of_payment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `las` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payslip_mode_of_payment`
--

INSERT INTO `payslip_mode_of_payment` (`id`, `name`, `slug_name`, `status`, `date_created`, `las`) VALUES
(1, 'CASH', 'cash', '', '2022-06-04 08:51:58', '2022-06-04 08:51:58'),
(2, 'BANK TRANSFER', 'bank_transfer', '', '2022-06-04 08:51:58', '2022-06-04 08:51:58'),
(3, 'CHEQUE', 'cheque', '', '2022-06-04 08:51:58', '2022-06-04 08:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `payslip_status`
--

CREATE TABLE `payslip_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug_name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payslip_status`
--

INSERT INTO `payslip_status` (`id`, `name`, `slug_name`, `color`, `date_created`, `last_updated`) VALUES
(1, 'PAID', 'paid', 'success', '2022-06-03 10:50:00', '2022-06-06 03:29:49'),
(2, 'PENDING', 'pending', 'danger', '2022-06-03 10:50:00', '2022-09-12 21:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `payslip_transaction_items`
--

CREATE TABLE `payslip_transaction_items` (
  `id` int(11) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `item_group` varchar(255) NOT NULL,
  `item_amount` decimal(65,2) NOT NULL,
  `payslip_id` bigint(20) NOT NULL,
  `payslip_number` varchar(255) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `payslip_month` varchar(255) NOT NULL,
  `payslip_date` date NOT NULL DEFAULT current_timestamp(),
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pos_list`
--

CREATE TABLE `pos_list` (
  `id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL DEFAULT 2,
  `pos_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `role_id`, `page_id`, `value`, `date_created`, `last_updated`) VALUES
(17627, 18, 2, 'allowed', '2022-02-03 12:29:03', '2022-02-03 12:29:03'),
(17628, 18, 4, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17629, 18, 31, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17630, 18, 5, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17631, 18, 6, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17632, 18, 7, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17633, 18, 8, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17634, 18, 24, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17635, 18, 30, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17636, 18, 9, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17637, 18, 10, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17638, 18, 12, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17639, 18, 11, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17640, 18, 38, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17641, 18, 39, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17642, 18, 81, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17643, 18, 82, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17644, 18, 17, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17645, 18, 18, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17646, 18, 20, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17647, 18, 21, 'allowed', '2022-02-03 12:29:04', '2022-02-03 12:29:04'),
(17648, 18, 19, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17649, 18, 23, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17650, 18, 26, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17651, 18, 25, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17652, 18, 73, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17653, 18, 72, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17654, 18, 80, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17655, 18, 78, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17656, 18, 71, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17657, 18, 15, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17658, 18, 1, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17659, 18, 16, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17660, 18, 79, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17661, 18, 22, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17662, 18, 87, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17663, 18, 85, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17664, 18, 88, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(17665, 18, 86, 'allowed', '2022-02-03 12:29:05', '2022-02-03 12:29:05'),
(31572, 3, 2, 'allowed', '2022-07-24 17:58:38', '2022-07-24 17:58:38'),
(31573, 3, 4, 'allowed', '2022-07-24 17:58:38', '2022-07-24 17:58:38'),
(31574, 3, 24, 'allowed', '2022-07-24 17:58:38', '2022-07-24 17:58:38'),
(31575, 3, 10, 'allowed', '2022-07-24 17:58:38', '2022-07-24 17:58:38'),
(31576, 3, 12, 'allowed', '2022-07-24 17:58:38', '2022-07-24 17:58:38'),
(31577, 3, 81, 'allowed', '2022-07-24 17:58:38', '2022-07-24 17:58:38'),
(31578, 3, 17, 'allowed', '2022-07-24 17:58:38', '2022-07-24 17:58:38'),
(31579, 3, 18, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31580, 3, 20, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31581, 3, 21, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31582, 3, 19, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31583, 3, 23, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31584, 3, 26, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31585, 3, 25, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31586, 3, 73, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31587, 3, 15, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31588, 3, 110, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31589, 3, 111, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31590, 3, 16, 'allowed', '2022-07-24 17:58:39', '2022-07-24 17:58:39'),
(31720, 1, 2, 'allowed', '2022-08-16 08:28:55', '2022-08-16 08:28:55'),
(31721, 1, 3, 'allowed', '2022-08-16 08:28:55', '2022-08-16 08:28:55'),
(31722, 1, 4, 'allowed', '2022-08-16 08:28:55', '2022-08-16 08:28:55'),
(31723, 1, 5, 'allowed', '2022-08-16 08:28:55', '2022-08-16 08:28:55'),
(31724, 1, 6, 'allowed', '2022-08-16 08:28:55', '2022-08-16 08:28:55'),
(31725, 1, 7, 'allowed', '2022-08-16 08:28:55', '2022-08-16 08:28:55'),
(31726, 1, 8, 'allowed', '2022-08-16 08:28:55', '2022-08-16 08:28:55'),
(31727, 1, 24, 'allowed', '2022-08-16 08:28:55', '2022-08-16 08:28:55'),
(31728, 1, 10, 'allowed', '2022-08-16 08:28:55', '2022-08-16 08:28:55'),
(31729, 1, 11, 'allowed', '2022-08-16 08:28:56', '2022-08-16 08:28:56'),
(31730, 1, 12, 'allowed', '2022-08-16 08:28:56', '2022-08-16 08:28:56'),
(31731, 1, 36, 'allowed', '2022-08-16 08:28:56', '2022-08-16 08:28:56'),
(31732, 1, 37, 'allowed', '2022-08-16 08:28:56', '2022-08-16 08:28:56'),
(31733, 1, 38, 'allowed', '2022-08-16 08:28:56', '2022-08-16 08:28:56'),
(31734, 1, 39, 'allowed', '2022-08-16 08:28:56', '2022-08-16 08:28:56'),
(31735, 1, 40, 'allowed', '2022-08-16 08:28:56', '2022-08-16 08:28:56'),
(31736, 1, 81, 'allowed', '2022-08-16 08:28:57', '2022-08-16 08:28:57'),
(31737, 1, 82, 'allowed', '2022-08-16 08:28:57', '2022-08-16 08:28:57'),
(31738, 1, 91, 'allowed', '2022-08-16 08:28:57', '2022-08-16 08:28:57'),
(31739, 1, 92, 'allowed', '2022-08-16 08:28:57', '2022-08-16 08:28:57'),
(31740, 1, 93, 'allowed', '2022-08-16 08:28:58', '2022-08-16 08:28:58'),
(31741, 1, 13, 'allowed', '2022-08-16 08:28:58', '2022-08-16 08:28:58'),
(31742, 1, 14, 'allowed', '2022-08-16 08:28:58', '2022-08-16 08:28:58'),
(31743, 1, 115, 'allowed', '2022-08-16 08:28:58', '2022-08-16 08:28:58'),
(31744, 1, 27, 'allowed', '2022-08-16 08:28:58', '2022-08-16 08:28:58'),
(31745, 1, 41, 'allowed', '2022-08-16 08:28:58', '2022-08-16 08:28:58'),
(31746, 1, 94, 'allowed', '2022-08-16 08:28:58', '2022-08-16 08:28:58'),
(31747, 1, 95, 'allowed', '2022-08-16 08:28:58', '2022-08-16 08:28:58'),
(31748, 1, 96, 'allowed', '2022-08-16 08:28:59', '2022-08-16 08:28:59'),
(31749, 1, 97, 'allowed', '2022-08-16 08:28:59', '2022-08-16 08:28:59'),
(31750, 1, 98, 'allowed', '2022-08-16 08:28:59', '2022-08-16 08:28:59'),
(31751, 1, 99, 'allowed', '2022-08-16 08:28:59', '2022-08-16 08:28:59'),
(31752, 1, 100, 'allowed', '2022-08-16 08:28:59', '2022-08-16 08:28:59'),
(31753, 1, 101, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31754, 1, 102, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31755, 1, 103, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31756, 1, 104, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31757, 1, 105, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31758, 1, 106, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31759, 1, 107, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31760, 1, 108, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31761, 1, 17, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31762, 1, 18, 'allowed', '2022-08-16 08:29:00', '2022-08-16 08:29:00'),
(31763, 1, 20, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31764, 1, 21, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31765, 1, 19, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31766, 1, 23, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31767, 1, 26, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31768, 1, 25, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31769, 1, 73, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31770, 1, 42, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31771, 1, 43, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31772, 1, 74, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31773, 1, 75, 'allowed', '2022-08-16 08:29:01', '2022-08-16 08:29:01'),
(31774, 1, 76, 'allowed', '2022-08-16 08:29:02', '2022-08-16 08:29:02'),
(31775, 1, 118, 'allowed', '2022-08-16 08:29:02', '2022-08-16 08:29:02'),
(31776, 1, 44, 'allowed', '2022-08-16 08:29:02', '2022-08-16 08:29:02'),
(31777, 1, 15, 'allowed', '2022-08-16 08:29:02', '2022-08-16 08:29:02'),
(31778, 1, 109, 'allowed', '2022-08-16 08:29:02', '2022-08-16 08:29:02'),
(31779, 1, 110, 'allowed', '2022-08-16 08:29:02', '2022-08-16 08:29:02'),
(31780, 1, 111, 'allowed', '2022-08-16 08:29:02', '2022-08-16 08:29:02'),
(31781, 1, 112, 'allowed', '2022-08-16 08:29:02', '2022-08-16 08:29:02'),
(31782, 1, 113, 'allowed', '2022-08-16 08:29:02', '2022-08-16 08:29:02'),
(31783, 1, 116, 'allowed', '2022-08-16 08:29:03', '2022-08-16 08:29:03'),
(31784, 1, 16, 'allowed', '2022-08-16 08:29:03', '2022-08-16 08:29:03'),
(31785, 1, 117, 'allowed', '2022-08-16 08:29:03', '2022-08-16 08:29:03');

-- --------------------------------------------------------

--
-- Table structure for table `privilege_groups`
--

CREATE TABLE `privilege_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_desc` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `privilege_groups`
--

INSERT INTO `privilege_groups` (`id`, `group_name`, `group_desc`, `date_created`, `last_updated`) VALUES
(1, 'Expenses Management', '', '2021-06-11 19:17:12', '2022-06-08 08:05:40'),
(2, 'Receipt Management', '', '2021-06-11 19:17:12', '2022-06-08 08:05:18'),
(3, 'Staff/Employee Management', '', '2021-06-11 19:17:12', '2021-06-11 19:17:12'),
(4, 'Payroll Management', '', '2021-06-11 19:17:12', '2022-06-08 08:57:12'),
(5, 'Reports Management', '', '2021-06-11 19:17:12', '2022-06-08 08:57:24'),
(6, 'Settings/Application Management', '', '2021-06-11 19:17:12', '2022-06-08 08:57:30'),
(7, 'Profile', '', '2021-06-11 19:17:12', '2022-06-08 08:57:36');

-- --------------------------------------------------------

--
-- Table structure for table `privilege_id`
--

CREATE TABLE `privilege_id` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `page_id` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `privilege_id`
--

INSERT INTO `privilege_id` (`id`, `group_id`, `page_id`, `description`, `ordering`, `date_added`, `last_updated`) VALUES
(2, 1, 'expenses', 'Can see list of expenses', 1, '2021-06-10 03:48:08', '2022-06-08 08:12:53'),
(3, 1, 'create-expenses', 'Can create new expenses', 2, '2021-06-10 03:48:08', '2022-06-08 08:20:09'),
(4, 1, 'edit-expenses', 'Can edit/modify expenses', 3, '2021-06-10 03:48:08', '2022-06-08 08:23:17'),
(5, 1, 'delete-expenses', 'Can delete expenses', 4, '2021-06-10 03:48:08', '2022-06-08 08:23:19'),
(6, 1, 'expenses-item', 'Can see list of expenses item', 5, '2021-06-10 03:48:08', '2022-06-08 08:23:20'),
(7, 1, 'create-expenses-item', 'Can create expenses item', 6, '2021-06-10 03:48:08', '2022-06-08 08:23:22'),
(8, 1, 'edit-expenses-item', 'Can edit/modify expenses item', 7, '2021-06-10 03:48:08', '2022-06-08 08:23:25'),
(10, 2, 'receipts', 'can see list of receipts', 1, '2021-06-10 03:48:08', '2022-06-08 08:37:34'),
(11, 2, 'create-receipt', 'Can create receipts', 2, '2021-06-10 03:48:08', '2022-06-08 08:37:35'),
(12, 2, 'edit-receipt', 'can edit/modify receipts', 3, '2021-06-10 03:48:08', '2022-06-08 08:37:37'),
(13, 3, 'employees', 'can see list of employees', 1, '2021-06-10 03:48:08', '2022-06-08 08:44:20'),
(14, 3, 'create-employee', 'Can create employee accounts', 2, '2021-06-10 03:48:08', '2022-06-08 08:44:21'),
(15, 6, 'company-settings', 'Can update company settings', 1, '2021-06-10 03:48:08', '2022-06-08 17:00:10'),
(16, 7, 'edit-profile', 'Can edit/modify profile', 2, '2021-06-10 03:48:08', '2022-06-08 17:08:56'),
(17, 4, 'payslips', 'Can see list of payslips', 1, '2021-06-10 03:48:08', '2022-06-08 08:59:48'),
(18, 4, 'create-payslip', 'Can create payslips', 2, '2021-06-10 03:48:08', '2022-06-08 09:00:07'),
(19, 4, 'print-payslip', 'Can print payslips', 5, '2021-06-10 03:48:08', '2022-06-08 09:01:43'),
(20, 4, 'edit-payslip', 'Can edit/modify payslips', 3, '2021-06-10 03:48:08', '2022-06-08 09:00:23'),
(21, 4, 'delete-payslip', 'Can delete payslips', 4, '2021-06-10 03:48:08', '2022-06-08 09:00:35'),
(23, 4, 'payslip-items', 'Can see list of payslip items', 6, '2021-06-10 03:48:08', '2022-06-08 09:02:17'),
(24, 1, 'delete-expenses-item', 'Can delete expenses item', 8, '2021-06-10 03:48:08', '2022-06-08 08:23:27'),
(25, 4, 'edit-payslip-item', 'Can edit/modify payslip items', 8, '2021-06-10 03:48:08', '2022-06-08 09:05:12'),
(26, 4, 'create-payslip-item', 'Can create payslip items', 7, '2021-06-10 03:48:08', '2022-06-08 09:02:36'),
(27, 3, 'edit-employee', 'Can edit/modify employee account', 3, '2021-06-10 03:48:08', '2022-06-08 08:44:23'),
(36, 2, 'delete-receipt', 'Can delete receipts', 4, '2021-06-10 03:48:08', '2022-06-08 08:37:39'),
(37, 2, 'print-receipt', 'Can print receipts', 5, '2021-06-10 03:48:08', '2022-06-08 08:37:41'),
(38, 2, 'payable-items', 'Can see list of payable items', 6, '2021-06-10 03:48:08', '2022-06-08 08:37:42'),
(39, 2, 'create-payable-item', 'Can create payable items', 7, '2021-06-10 03:48:08', '2022-06-08 08:37:44'),
(40, 2, 'edit-payable-item', 'Can edit/modify payable items', 8, '2021-06-10 03:48:08', '2022-06-08 08:37:47'),
(41, 3, 'departments', 'can see list of departments', 4, '2021-06-10 03:48:08', '2022-06-08 08:46:37'),
(42, 5, 'payment-report', 'Can see payment reports', 1, '2021-06-10 03:48:08', '2022-06-08 09:11:39'),
(43, 5, 'print-payment-report', 'Can print payment reports', 2, '2021-06-10 03:48:08', '2022-06-08 09:12:20'),
(44, 5, 'print-expenses-report', 'Can print expenses report', 6, '2021-06-10 03:48:08', '2022-06-08 09:20:07'),
(73, 4, 'delete-payslip-item', 'Can delete payslip items', 9, '2021-06-10 03:48:08', '2022-06-08 09:03:50'),
(74, 5, 'transaction-report', 'Can see transaction reports', 3, '2021-06-10 03:48:08', '2022-06-08 09:12:49'),
(75, 5, 'print-transaction-report', 'Can print transaction reports', 4, '2021-06-10 03:48:08', '2022-06-08 09:13:09'),
(76, 5, 'expenses-report', 'Can see expenses report', 5, '2021-06-10 03:48:08', '2022-06-08 09:18:47'),
(81, 2, 'delete-payable-item', 'can delete payable items', 9, '2021-06-10 03:48:08', '2022-06-08 08:37:49'),
(82, 2, 'payment-accounts', 'Can see list of payment accounts', 10, '2021-06-10 03:48:08', '2022-06-08 08:38:38'),
(91, 2, 'create-payment-account', 'Can create payment accounts', 11, '2021-06-10 03:48:08', '2022-06-08 08:37:47'),
(92, 2, 'edit-payment-account', 'can edit/modify payment accounts', 12, '2021-06-10 03:48:08', '2022-06-08 08:40:13'),
(93, 2, 'delete-payment-account', 'Can delete payment accounts', 13, '2021-06-10 03:48:08', '2022-06-08 08:40:16'),
(94, 3, 'create-department', 'can create departments', 5, '2021-06-10 03:48:08', '2022-06-08 08:46:49'),
(95, 3, 'edit-department', 'Can edit/modify departments', 6, '2021-06-10 03:48:08', '2022-06-08 08:47:06'),
(96, 3, 'delete-department', 'Can delete departments', 7, '2021-06-10 03:48:08', '2022-06-08 08:47:23'),
(97, 3, 'designations', 'Can see list of designations', 8, '2021-06-10 03:48:08', '2022-06-08 08:48:34'),
(98, 3, 'create-designation', 'Can create designations', 9, '2021-06-10 03:48:08', '2022-06-08 08:44:21'),
(99, 3, 'edit-designation', 'Can edit/modify designations', 10, '2021-06-10 03:48:08', '2022-06-08 08:44:23'),
(100, 3, 'delete-designation', 'Can delete designations', 11, '2021-06-10 03:48:08', '2022-06-08 08:46:37'),
(101, 3, 'account-titles', 'Can see list of account titles', 12, '2021-06-10 03:48:08', '2022-06-08 08:46:49'),
(102, 3, 'create-account-title', 'Can create account titles', 13, '2021-06-10 03:48:08', '2022-06-08 08:47:06'),
(103, 3, 'edit-account-title', 'can edit/modify account titles', 14, '2021-06-10 03:48:08', '2022-06-08 08:47:23'),
(104, 3, 'delete-account-title', 'Can delete account titles', 15, '2021-06-10 03:48:08', '2022-06-08 08:48:34'),
(105, 3, 'branches', 'Can see list of branches', 16, '2021-06-10 03:48:08', '2022-06-08 08:46:49'),
(106, 3, 'create-branches', 'Can create branches', 17, '2021-06-10 03:48:08', '2022-06-08 08:47:06'),
(107, 3, 'edit-branches', 'can edit/modify branches', 18, '2021-06-10 03:48:08', '2022-06-08 08:47:23'),
(108, 3, 'delete-branches', 'Can delete branches', 19, '2021-06-10 03:48:08', '2022-06-08 08:48:34'),
(109, 6, 'invoice-settings', 'Can update invoice settings', 1, '2021-06-10 03:48:08', '2022-06-08 17:00:52'),
(110, 6, 'privilege-settings', 'can update privilege settings', 1, '2021-06-10 03:48:08', '2022-06-08 17:02:16'),
(111, 6, 'create-user-role', 'Can create user roles', 1, '2021-06-10 03:48:08', '2022-06-08 17:02:45'),
(112, 6, 'edit-user-role', 'Can edit/modify user roles', 1, '2021-06-10 03:48:08', '2022-06-08 17:03:05'),
(113, 6, 'delete-user-role', 'Can delete user roles', 1, '2021-06-10 03:48:08', '2022-06-08 17:03:21'),
(115, 3, 'edit-employee-basic-salary', 'Can edit/modify employee basic salary', 2, '2021-06-10 03:48:08', '2022-07-23 15:33:09'),
(116, 6, 'payment-channels', 'can edit/modify payment channels', 1, '2021-06-10 03:48:08', '2022-06-08 17:02:16'),
(117, 7, 'edit-own-basic-salary', 'Can edit/modify own basic salary', 2, '2021-06-10 03:48:08', '2022-06-08 17:08:56'),
(118, 5, 'payroll-report', 'Can see payroll reports', 5, '2021-06-10 03:48:08', '2022-06-08 09:18:47');

-- --------------------------------------------------------

--
-- Table structure for table `report_additional_notes`
--

CREATE TABLE `report_additional_notes` (
  `id` int(11) NOT NULL,
  `note_dates` date NOT NULL DEFAULT current_timestamp(),
  `note_text` text NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `softdata`
--

CREATE TABLE `softdata` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_description` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `company_phone1` varchar(255) NOT NULL,
  `company_phone2` varchar(255) NOT NULL,
  `invoice_prefix` varchar(255) NOT NULL,
  `app_version` varchar(255) NOT NULL,
  `show_app_version` varchar(255) NOT NULL DEFAULT '',
  `footer_text` varchar(255) NOT NULL DEFAULT 'VEhBTksgWU9VIEZPUiBZT1VSIFBBVFJPTkFHRS4=',
  `show_logo` varchar(255) NOT NULL,
  `uploaded_logo` varchar(255) NOT NULL,
  `invoice_printout` varchar(255) NOT NULL,
  `support` varchar(255) NOT NULL DEFAULT 'MDgwMjA5NTU2NTI=',
  `show_invoice_item_addtional_text` varchar(255) NOT NULL,
  `discount_pattern` varchar(255) NOT NULL,
  `discount_input_type` varchar(255) NOT NULL,
  `invoice_show_excess_payment_in_invoice` varchar(255) NOT NULL,
  `invoice_show_excess_payment_in_report` varchar(255) NOT NULL,
  `invoice_allow_duplicate_contents` varchar(255) NOT NULL,
  `invoice_allow_excess_payment` varchar(255) NOT NULL,
  `can_apply_customer_balance_to_invoice` varchar(255) NOT NULL,
  `can_apply_customer_credit_to_invoice` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `softdata`
--

INSERT INTO `softdata` (`id`, `company_name`, `company_description`, `company_address`, `company_phone1`, `company_phone2`, `invoice_prefix`, `app_version`, `show_app_version`, `footer_text`, `show_logo`, `uploaded_logo`, `invoice_printout`, `support`, `show_invoice_item_addtional_text`, `discount_pattern`, `discount_input_type`, `invoice_show_excess_payment_in_invoice`, `invoice_show_excess_payment_in_report`, `invoice_allow_duplicate_contents`, `invoice_allow_excess_payment`, `can_apply_customer_balance_to_invoice`, `can_apply_customer_credit_to_invoice`, `date_created`, `last_updated`) VALUES
(1, 'RElPQ0VTRSBPRiBBQkEgKEFOR0xJQ0FOIENPTU1VTklPTik=', '', 'Q2h1cmNoIEFkZHJlc3M=', 'MDgwMTIzNDU2Nzg=', 'MDgwODc2NTQzMjE=', 'UkNQVC0=', 'My4xLjA=', 'eWVz', 'VEhBTksgWU9VIEZPUiBDT05UUklCVVRJTkcgVE8gVEhFIEhPVVNFIE9GIEdPRA==', 'bm8=', '', 'aW52b2ljZS1QT1M=', 'MDgwMjA5NTU2NTI=', 'bm8=', 'Y2FsX2J5X3VuaXRfZGlzY291bnQ=', 'dGV4dGFyZWE=', 'eWVz', 'eWVz', 'bm8=', 'bm8=', 'eWVz', '', '2021-06-08 16:12:56', '2022-08-16 17:54:17');

-- --------------------------------------------------------

--
-- Table structure for table `soft_app_styles`
--

CREATE TABLE `soft_app_styles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `soft_app_styles`
--

INSERT INTO `soft_app_styles` (`id`, `name`, `value`, `date_created`, `last_updated`) VALUES
(1, 'Light', 'light', '2021-06-21 02:17:50', '2021-06-21 02:18:41'),
(2, 'Semi Dark', 'semi-dark', '2021-06-21 02:17:57', '2021-06-21 02:19:02'),
(3, 'Semi Dark Alt', 'semi-dark-alt', '2021-06-21 02:17:50', '2021-06-21 02:19:17'),
(4, 'Dark', 'dark', '2021-06-21 02:17:57', '2021-06-21 02:19:32'),
(5, 'Dark Alt', 'dark-alt', '2021-06-21 02:17:57', '2021-06-21 02:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `soft_color_codes`
--

CREATE TABLE `soft_color_codes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `soft_color_codes`
--

INSERT INTO `soft_color_codes` (`id`, `name`, `value`, `date_created`, `last_updated`) VALUES
(1, 'Default Theme', '#7367f0', '2021-06-21 02:12:08', '2021-06-21 02:12:08'),
(2, 'Orange Theme', '#eb6709', '2021-06-21 02:12:26', '2021-06-21 02:12:26'),
(3, 'Magenta Theme', '#ff00ff', '2021-06-21 02:12:08', '2021-06-21 02:12:08'),
(4, 'Violet Theme', '#ee82ee', '2021-06-21 02:12:26', '2021-06-21 02:12:26'),
(5, 'Life Theme', '#76c335', '2021-06-21 02:12:26', '2021-06-21 02:12:26');

-- --------------------------------------------------------

--
-- Table structure for table `soft_sidebar_styles`
--

CREATE TABLE `soft_sidebar_styles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `soft_sidebar_styles`
--

INSERT INTO `soft_sidebar_styles` (`id`, `name`, `value`, `date_created`) VALUES
(1, 'Normal', '-', '2021-06-21 02:58:18'),
(2, 'Small Menu Icon', 'small-menu-icon', '2021-06-21 02:58:29');

-- --------------------------------------------------------

--
-- Table structure for table `splitted_payments`
--

CREATE TABLE `splitted_payments` (
  `id` int(11) NOT NULL,
  `invoice_id` bigint(20) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `method_id` bigint(20) NOT NULL,
  `amount` decimal(65,2) NOT NULL,
  `payment_name` varchar(255) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `splitted_payments`
--

INSERT INTO `splitted_payments` (`id`, `invoice_id`, `invoice_number`, `payment_method`, `method_id`, `amount`, `payment_name`, `payment_date`, `date_added`, `date_created`, `last_updated`) VALUES
(1, 1, 'RCPT-590512511', 13, 3, '10000.00', 'ABC INC', '2022-09-12', '2022-09-12', '2022-09-12 05:04:11', '2022-09-12 05:04:11'),
(2, 1, 'RCPT-590512511', 14, 2, '9000.00', 'FRANKLIN CHIBUEZE ONUOHA', '2022-09-12', '2022-09-12', '2022-09-12 05:04:11', '2022-09-12 05:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `staff_roles`
--

CREATE TABLE `staff_roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `alias_name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'user',
  `description` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'can_login',
  `nature` varchar(100) NOT NULL DEFAULT 'editable',
  `default_landing` varchar(255) NOT NULL DEFAULT '1',
  `is_default` varchar(20) NOT NULL,
  `suspendable` varchar(255) NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff_roles`
--

INSERT INTO `staff_roles` (`id`, `role_name`, `alias_name`, `color`, `icon`, `description`, `status`, `nature`, `default_landing`, `is_default`, `suspendable`) VALUES
(1, ' Super Admin', 'super_admin', 'success', 'user-tie ', 'This role oversees/manages all functionalities of this software.', 'can_login', 'editable', '1', 'default', 'no'),
(3, ' Cashier', 'cashier', 'danger', 'donate', 'This role oversees/manages only assigned functionalities in this software.', 'can_login', 'editable', '1', 'default', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `track_inactivity`
--

CREATE TABLE `track_inactivity` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `last_check` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `track_inactivity`
--

INSERT INTO `track_inactivity` (`id`, `user_id`, `last_check`) VALUES
(1, 17634582176398, '2022-07-24 17:46:41'),
(2, 21721798276334, '2022-09-12 22:20:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `id` int(11) NOT NULL,
  `title` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `othername` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `contact_address` text NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `user_pwd` varchar(255) NOT NULL,
  `profile_pic` varchar(255) NOT NULL DEFAULT 'no-image.jpg',
  `branch` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `basic_salary` decimal(65,2) NOT NULL,
  `bank_id` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `payment_bank` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_status` varchar(20) NOT NULL DEFAULT '1',
  `is_2fa` varchar(10) NOT NULL,
  `acc_type` int(11) NOT NULL,
  `acc_id` bigint(20) NOT NULL,
  `signout_inactivity_after` int(11) NOT NULL DEFAULT 6,
  `url_id` varchar(255) NOT NULL,
  `acc_role` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `title`, `firstname`, `surname`, `othername`, `gender`, `dob`, `email`, `phone`, `contact_address`, `state`, `country`, `position`, `user_pwd`, `profile_pic`, `branch`, `department`, `designation`, `basic_salary`, `bank_id`, `bank_name`, `account_number`, `account_name`, `payment_bank`, `employee_id`, `reg_date`, `account_status`, `is_2fa`, `acc_type`, `acc_id`, `signout_inactivity_after`, `url_id`, `acc_role`, `date_created`, `last_updated`) VALUES
(12, 1, 'DEMO', 'USER', '', 'male', '2013-07-13', 'demo@gmail.com', '-', '-', '', '', '', '$2y$10$3LF/ZKOA1krhQpgB.hAZReHmAVff06xm9vj6fh0pYznjmtAihtzNu', 'no-image.jpg', '1', '1', '1', '0.00', '', '', '', '', 0, '', '2021-04-07 23:00:00', '1', '', 1, 21721798276334, 7, '5d943fb6754bd89a6180fde8be08ba22', 0, '2022-05-30 02:50:23', '2022-09-12 09:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `valid_colors`
--

CREATE TABLE `valid_colors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `valid_colors`
--

INSERT INTO `valid_colors` (`id`, `name`, `color`, `date_created`, `last_updated`) VALUES
(1, 'Green', 'success', '2021-06-17 21:35:07', '2021-06-17 22:16:03'),
(2, 'Red', 'danger', '2021-06-17 21:35:36', '2021-06-17 22:16:05'),
(3, 'Yellow', 'warning', '2021-06-17 21:35:48', '2021-06-17 22:16:08'),
(4, 'Blue', 'primary', '2021-06-17 21:35:58', '2021-06-17 22:16:11'),
(5, 'Black', 'secondary', '2021-06-17 21:36:06', '2021-06-17 22:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_chart_reports`
--

CREATE TABLE `weekly_chart_reports` (
  `report_id` int(11) NOT NULL,
  `month` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `amount` decimal(15,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_status`
--
ALTER TABLE `account_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_titles`
--
ALTER TABLE `account_titles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_bank_accounts`
--
ALTER TABLE `employee_bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_payslip_banks`
--
ALTER TABLE `employee_payslip_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `excess_status`
--
ALTER TABLE `excess_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `excess_track_used_paid_balance`
--
ALTER TABLE `excess_track_used_paid_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_item`
--
ALTER TABLE `expenses_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_excess_payments`
--
ALTER TABLE `invoice_excess_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_status`
--
ALTER TABLE `invoice_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_trn_details`
--
ALTER TABLE `invoice_trn_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landing_pages`
--
ALTER TABLE `landing_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `max_timing`
--
ALTER TABLE `max_timing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_chart_reports`
--
ALTER TABLE `monthly_chart_reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `payable_items`
--
ALTER TABLE `payable_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_banks`
--
ALTER TABLE `payment_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslips`
--
ALTER TABLE `payslips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_items`
--
ALTER TABLE `payslip_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_mode_of_payment`
--
ALTER TABLE `payslip_mode_of_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_status`
--
ALTER TABLE `payslip_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_transaction_items`
--
ALTER TABLE `payslip_transaction_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_list`
--
ALTER TABLE `pos_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privilege_groups`
--
ALTER TABLE `privilege_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privilege_id`
--
ALTER TABLE `privilege_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_additional_notes`
--
ALTER TABLE `report_additional_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `softdata`
--
ALTER TABLE `softdata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `soft_app_styles`
--
ALTER TABLE `soft_app_styles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `soft_color_codes`
--
ALTER TABLE `soft_color_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `soft_sidebar_styles`
--
ALTER TABLE `soft_sidebar_styles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `splitted_payments`
--
ALTER TABLE `splitted_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_roles`
--
ALTER TABLE `staff_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `track_inactivity`
--
ALTER TABLE `track_inactivity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `valid_colors`
--
ALTER TABLE `valid_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weekly_chart_reports`
--
ALTER TABLE `weekly_chart_reports`
  ADD PRIMARY KEY (`report_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_status`
--
ALTER TABLE `account_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `account_titles`
--
ALTER TABLE `account_titles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employee_bank_accounts`
--
ALTER TABLE `employee_bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_payslip_banks`
--
ALTER TABLE `employee_payslip_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `excess_status`
--
ALTER TABLE `excess_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `excess_track_used_paid_balance`
--
ALTER TABLE `excess_track_used_paid_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_item`
--
ALTER TABLE `expenses_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice_excess_payments`
--
ALTER TABLE `invoice_excess_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_status`
--
ALTER TABLE `invoice_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice_trn_details`
--
ALTER TABLE `invoice_trn_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landing_pages`
--
ALTER TABLE `landing_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `max_timing`
--
ALTER TABLE `max_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `monthly_chart_reports`
--
ALTER TABLE `monthly_chart_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `payable_items`
--
ALTER TABLE `payable_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `payment_banks`
--
ALTER TABLE `payment_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payslips`
--
ALTER TABLE `payslips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslip_items`
--
ALTER TABLE `payslip_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payslip_mode_of_payment`
--
ALTER TABLE `payslip_mode_of_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payslip_status`
--
ALTER TABLE `payslip_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payslip_transaction_items`
--
ALTER TABLE `payslip_transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_list`
--
ALTER TABLE `pos_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31786;

--
-- AUTO_INCREMENT for table `privilege_groups`
--
ALTER TABLE `privilege_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `privilege_id`
--
ALTER TABLE `privilege_id`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `report_additional_notes`
--
ALTER TABLE `report_additional_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `softdata`
--
ALTER TABLE `softdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `soft_app_styles`
--
ALTER TABLE `soft_app_styles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `soft_color_codes`
--
ALTER TABLE `soft_color_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `soft_sidebar_styles`
--
ALTER TABLE `soft_sidebar_styles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `splitted_payments`
--
ALTER TABLE `splitted_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff_roles`
--
ALTER TABLE `staff_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `track_inactivity`
--
ALTER TABLE `track_inactivity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `valid_colors`
--
ALTER TABLE `valid_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `weekly_chart_reports`
--
ALTER TABLE `weekly_chart_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6473;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
