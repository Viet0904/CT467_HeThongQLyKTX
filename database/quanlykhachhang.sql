-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 17, 2023 lúc 04:25 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlykhachhang`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` varchar(120) NOT NULL,
  `UserName` varchar(120) NOT NULL,
  `MobileNumber` varchar(10) NOT NULL,
  `Email` varchar(120) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `Role` varchar(50) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `Role`) VALUES
(1, 'Admin', 'admin', '0897955556', 'admin@gmail.com', '$2y$10$tXO3HlmD75tFWJ.rpIkybu4wABn.cFNGV44p5dA17O2HZynN6Mr12', 'admin'),
(2, 'test', 'test', '0123456789', 'test@gmail.com', '$2y$10$tXO3HlmD75tFWJ.rpIkybu4wABn.cFNGV44p5dA17O2HZynN6Mr12', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblclient`
--

CREATE TABLE `tblclient` (
  `ClientID` int(10) NOT NULL,
  `ContactName` varchar(120) NOT NULL,
  `Address` varchar(200) NOT NULL,
  `Cellphnumber` varchar(10) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `Notes` mediumtext DEFAULT NULL,
  `Role` varchar(50) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblclient`
--

INSERT INTO `tblclient` (`ClientID`, `ContactName`, `Address`, `Cellphnumber`, `Email`, `Password`, `CreationDate`, `Notes`, `Role`) VALUES
(1, 'Trương Huỳnh Tú Như', 'Bạc Liêu', '0435435435', 'abc@gmail.com', '$2y$10$568GdqbRiLe5MEj3RxEnEuixThCAbG2tilxXxxA4gV.7770nAlVO.', '2023-10-22 04:40:11', NULL, 'user'),
(2, 'Lê Gia Lãm', 'Cà Mau', '0545454545', 'lgl@gmail.com', '$2y$10$568GdqbRiLe5MEj3RxEnEuixThCAbG2tilxXxxA4gV.7770nAlVO.', '2023-10-22 05:24:39', NULL, 'user'),
(3, 'Đỗ Thành Tài', 'Kiên Giang', '0787987789', 'dtt@gmail.com', '$2y$10$568GdqbRiLe5MEj3RxEnEuixThCAbG2tilxXxxA4gV.7770nAlVO.', '2023-10-22 05:26:50', NULL, 'user'),
(4, 'Nguyễn Quốc Việt', 'An Giang', '0545121245', 'nqv@gmail.com', '$2y$10$568GdqbRiLe5MEj3RxEnEuixThCAbG2tilxXxxA4gV.7770nAlVO.', '2023-10-22 05:29:59', NULL, 'user'),
(5, 'Dương Thiên Tấn', 'An Giang', '0346546546', 'dtht@gmail.com', '$2y$10$568GdqbRiLe5MEj3RxEnEuixThCAbG2tilxXxxA4gV.7770nAlVO.', '2023-10-23 10:42:52', NULL, 'user'),
(6, 'Dương Quốc Lợi', 'Trà Vinh', '0935477803', 'dql@gmail.com', '$2y$10$568GdqbRiLe5MEj3RxEnEuixThCAbG2tilxXxxA4gV.7770nAlVO.', '2023-11-02 16:00:24', NULL, 'user'),
(7, 'Nguyễn Hồng Tuấn Phát', 'Cần Thơ', '0478941230', 'nhtp@gmail.com', '$2y$10$xbXwWtMyoQqtDtH34LJzOutrRHRWH2q3rwIARFOv/eanF1QdnK38W', '2023-11-17 15:12:11', '                            ', 'user'),
(8, 'Lê Thị Tiến', 'Kiên Giang', '0478975230', 'ltt@gmail.com', '$2y$10$Z7f6prIeKb4M3aIkxvvcDu7xJ3QRtrYdseVq18UzxI0KL2lT6Ynim', '2023-11-17 15:12:44', '                            ', 'user');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblinvoice`
--

CREATE TABLE `tblinvoice` (
  `InvoiceID` int(11) NOT NULL,
  `ClientID` int(10) DEFAULT NULL,
  `ServiceId` int(10) DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblinvoice`
--

INSERT INTO `tblinvoice` (`InvoiceID`, `ClientID`, `ServiceId`, `PostingDate`) VALUES
(1, 2, 2, '2023-10-22 04:51:43'),
(2, 2, 3, '2023-10-22 04:51:43'),
(3, 1, 4, '2023-10-22 21:20:40'),
(4, 1, 6, '2023-10-22 21:20:40'),
(5, 4, 1, '2023-10-23 00:16:45'),
(6, 4, 2, '2023-10-23 00:16:45'),
(7, 4, 3, '2023-10-23 00:16:45'),
(8, 1, 1, '2023-10-23 23:56:11'),
(9, 1, 2, '2023-10-23 23:56:12'),
(10, 1, 4, '2023-10-23 23:56:36'),
(11, 1, 5, '2023-10-23 23:56:36'),
(12, 1, 1, '2023-10-24 00:09:12'),
(13, 1, 2, '2023-10-24 00:09:12'),
(14, 1, 1, '2023-08-17 20:46:54'),
(15, 1, 2, '2023-08-17 20:46:54'),
(16, 1, 2, '2023-08-25 22:04:51'),
(17, 1, 3, '2023-08-25 22:04:51'),
(18, 1, 9, '2023-08-26 10:53:27'),
(19, 1, 10, '2023-08-26 10:53:27'),
(20, 6, 1, '2023-08-27 09:00:56'),
(21, 6, 3, '2023-08-27 09:00:56'),
(22, 6, 8, '2023-08-27 09:00:56'),
(23, 6, 9, '2023-08-27 09:00:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblservices`
--

CREATE TABLE `tblservices` (
  `ID` int(10) NOT NULL,
  `ServiceName` varchar(200) NOT NULL,
  `ServicePrice` varchar(200) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tblservices`
--

INSERT INTO `tblservices` (`ID`, `ServiceName`, `ServicePrice`, `CreationDate`) VALUES
(1, 'Website Developments', '121', '2023-10-22 13:42:29'),
(2, 'SEO Service', '30', '2023-10-21 22:56:17'),
(3, 'SMO Services', '150', '2023-10-22 01:22:19'),
(4, 'Web designing', '120', '2023-10-22 03:14:15'),
(5, 'Network Service', '180', '2023-10-21 18:30:00'),
(6, 'Broadband Services', '120', '2023-10-21 18:30:00'),
(7, 'Drupal Development Services', '90', '2023-10-22 07:46:05'),
(8, 'CakePHP Development Services', '56', '2023-10-22 07:46:30'),
(9, 'Magento Development Services.', '113', '2023-10-22 07:47:41'),
(10, 'Web Development', '356', '2023-11-01 05:02:54'),
(11, 'On Page SEO', '250', '2023-11-02 15:58:59');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `tblclient`
--
ALTER TABLE `tblclient`
  ADD PRIMARY KEY (`ClientID`);

--
-- Chỉ mục cho bảng `tblinvoice`
--
ALTER TABLE `tblinvoice`
  ADD PRIMARY KEY (`InvoiceID`),
  ADD KEY `ClientID` (`ClientID`),
  ADD KEY `ServiceId` (`ServiceId`);

--
-- Chỉ mục cho bảng `tblservices`
--
ALTER TABLE `tblservices`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tblclient`
--
ALTER TABLE `tblclient`
  MODIFY `ClientID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `tblinvoice`
--
ALTER TABLE `tblinvoice`
  MODIFY `InvoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `tblservices`
--
ALTER TABLE `tblservices`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tblinvoice`
--
ALTER TABLE `tblinvoice`
  ADD CONSTRAINT `tblinvoice_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `tblclient` (`ClientID`),
  ADD CONSTRAINT `tblinvoice_ibfk_2` FOREIGN KEY (`ServiceId`) REFERENCES `tblservices` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
