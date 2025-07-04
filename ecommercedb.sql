-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 25, 2025 at 06:02 AM
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
-- Database: `ecommercedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `username`, `email`, `password`) VALUES
(1, 'admin1', 'admin1@gmail.com', '$2y$10$5ncMA6flDweVjwaPLBzPy.pnWfYfGe8oxyNlkOaGKvjbS60x0sYVS'),
(2, 'hok', 'hok@gmail.com', '$2y$10$WoOYvWFGQvC5WvQBLH809uevM00N92dp5JwPyqmacG7kxzrAPeA6W'),
(3, 'admintest', 'admintest@gmail.com', '$2y$10$Kx5EPEdv1GtltLwhbp5cBevASB65RHYBTM0w2lD8P/KydViO4CwWi');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `categoryImage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `name`, `categoryImage`) VALUES
(1, 'Keyboard', 'Keyboard.jpg'),
(2, 'Keycaps', 'Keycaps.jpg'),
(3, 'Switches', 'Switches.jpg'),
(4, 'Mouse', 'Mouse.jpg'),
(5, 'Mousepad', 'Mousepad.jpg'),
(6, 'Controller', 'Controller.jpg'),
(7, 'Headset', 'Headset.jpg'),
(8, 'Accessories', 'Accessories.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

CREATE TABLE `orderdetail` (
  `orderDetailID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `productID` int(11) DEFAULT NULL,
  `productName` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unitPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetail`
--

INSERT INTO `orderdetail` (`orderDetailID`, `orderID`, `productID`, `productName`, `quantity`, `unitPrice`) VALUES
(1, 1, 13, 'SCUF Envision Pro Jankz', 1, 129.99),
(2, 1, 59, 'Razer Stream Controller X', 1, 148.95),
(3, 1, 24, 'Epomaker Cypher 81', 1, 89.99),
(4, 2, 40, 'ATTACK SHARK X3', 2, 39.99),
(5, 2, 49, 'Red & Black Shrine Sunset', 2, 8.99),
(6, 2, 9, 'Xiaomi Mi Computer Monitor Light Bar', 1, 45.74),
(7, 3, 26, 'Epomaker TH66', 1, 36.00),
(8, 3, 40, 'ATTACK SHARK X3', 1, 39.99),
(9, 3, 62, 'Akko V3 Creamy Purple Pro Switch', 2, 11.99),
(10, 3, 57, 'KiiBOOM Taro Cream Milk Switches Set', 2, 15.99),
(11, 3, 10, 'Monka Contra GT-96 Wireless Gaming Controller', 1, 34.99),
(12, 4, 60, 'GameSir Nova Wireless Switch Pro Controller', 2, 29.99),
(13, 4, 5, 'UGREEN Revodok USB C Hub', 1, 14.53),
(14, 4, 9, 'Xiaomi Mi Computer Monitor Light Bar', 1, 50.47),
(15, 5, 16, 'HyperX Cloud III', 1, 89.99),
(16, 5, 3, 'NUOXI H9 Cooling Pad', 1, 13.99),
(17, 6, 3, 'NUOXI H9 Cooling Pad', 1, 13.99),
(18, 7, 38, 'VXE Dragonfly R1', 1, 17.99),
(19, 8, 60, 'GameSir Nova Wireless Switch Pro Controller', 2, 29.99),
(20, 8, 18, 'Razer BlackShark V2 X', 4, 32.99),
(21, 9, 54, 'Epomaker Sea Salt Switch Set', 3, 16.99),
(22, 9, 48, 'Japanese Mountain Sunset', 2, 8.13),
(23, 10, 16, 'HyperX Cloud III', 5, 89.99),
(24, 11, 27, 'EPOMAKER x AULA F75', 2, 59.99),
(25, 11, 9, 'Xiaomi Mi Computer Monitor Light Bar', 1, 50.47),
(26, 11, 11, 'PlayStation 5 Controller', 1, 69.99),
(27, 12, 16, 'HyperX Cloud III', 1, 89.99),
(28, 12, 14, 'Xbox Wireless Controller', 1, 59.99),
(29, 12, 58, 'LEOBOG Reaper Switch Set', 2, 26.99),
(30, 12, 38, 'VXE Dragonfly R1', 1, 17.99),
(31, 13, 26, 'Epomaker TH66', 1, 36.00),
(32, 13, 44, 'Logitech G305 LightSpeed', 1, 29.99),
(33, 13, 62, 'Akko V3 Creamy Purple Pro Switch', 3, 11.99),
(34, 14, 5, 'UGREEN Revodok USB C Hub', 1, 14.53),
(35, 14, 33, 'Epomaker Matcha Keycaps', 1, 22.79),
(36, 15, 47, 'Black Topographic Contour', 1, 11.99),
(37, 16, 9, 'Xiaomi Mi Computer Monitor Light Bar', 1, 50.47),
(38, 17, 9, 'Xiaomi Mi Computer Monitor Light Bar', 1, 50.47);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phoneNumber` varchar(50) NOT NULL,
  `shippingFee` decimal(10,2) NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL,
  `paymentMethod` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `orderDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `email`, `fullName`, `address`, `phoneNumber`, `shippingFee`, `totalAmount`, `paymentMethod`, `status`, `orderDate`) VALUES
(1, 2, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 36.00, 404.93, 'Cash on Delivery', 'Completed', '2025-05-10 15:08:39'),
(2, 2, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 14.00, 157.70, 'Cash on Delivery', 'Cancelled', '2025-05-10 15:10:36'),
(3, 2, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 16.00, 182.94, 'Cash on Delivery', 'Completed', '2025-05-10 16:39:27'),
(4, 2, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 12.00, 136.98, 'QR Code', 'Completed', '2025-05-10 16:47:23'),
(5, 2, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 10.00, 113.98, 'QR Code', 'Completed', '2025-05-10 17:44:30'),
(6, 2, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 1.00, 14.99, 'Cash on Delivery', 'Cancelled', '2025-05-10 17:47:21'),
(7, 2, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 1.00, 18.99, 'Cash on Delivery', 'Cancelled', '2025-05-10 18:18:38'),
(8, 1, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 19.00, 210.94, 'Cash on Delivery', 'Completed', '2025-05-10 19:16:06'),
(9, 1, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 6.00, 73.22, 'Cash on Delivery', 'Cancelled', '2025-05-12 09:30:10'),
(10, 1, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 44.00, 493.95, 'Cash on Delivery', 'Pending', '2025-05-17 12:58:56'),
(11, 1, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 24.00, 264.44, 'Cash on Delivery', 'Completed', '2025-05-19 09:15:53'),
(12, 2, 'hoknino@gmail.com', 'hok', 'Kandal', '012345678', 22.00, 243.95, 'Cash on Delivery', 'Completed', '2025-05-22 14:29:22'),
(13, 2, 'hoknino@gmail.com', 'hok', 'Kandal', '018777555', 10.00, 111.96, 'Cash on Delivery', 'Pending', '2025-05-24 19:35:37'),
(14, 1, 'hoknino@gmail.com', 'hok', 'Battambang', '069420696', 3.00, 40.32, 'QR Code', 'Pending', '2025-05-25 08:48:23'),
(15, 1, 'hoknino@gmail.com', 'hok', 'Battambang', '069420696', 1.00, 12.99, 'Cash on Delivery', 'Pending', '2025-05-25 10:14:05'),
(16, 1, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '089982534', 5.00, 55.47, 'Cash on Delivery', 'Pending', '2025-05-25 10:29:42'),
(17, 1, 'hok@gmail.com', 'Mork Chonghok', 'Phnom Penh', '012345678', 5.00, 55.47, 'Cash on Delivery', 'Pending', '2025-05-25 10:55:22');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `shortDesc` text NOT NULL,
  `stock` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `mainImage` varchar(255) NOT NULL,
  `productDetail` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `categoryID`, `name`, `shortDesc`, `stock`, `price`, `discount`, `mainImage`, `productDetail`) VALUES
(1, 8, 'Keychron Premium Coiled Aviator Cable', 'Premium coiled cable with aviator connector for secure and stylish keyboard connections', 8, 28.99, 0.00, 'Keychron-Coiled-Cable.jpg', 'Aviator Port: 5 Pin (GX12) | Connection Type: type-C cable + type-A to type-C adapter | Straight Cable Compatible Keyboards: Q, Q Pro, Q Max, V, V max, 81 Pro, K4 Pro, K10 Pro, Lemokey L, P and low-profile keyboards | Angled Cable Compatible Keyboards: K series (excluding low-profile keyboards), K2 Pro, K6 Pro, K8 Pro, K12 Pro, K14 Pro | Coil Length: 46 cm | Normal Length: 90 cm | Total Length: 136 cm'),
(2, 8, 'Logitech Brio 4K', 'Ultra HD Business Webcam for Crystal-Clear Video', 10, 159.99, 20.00, 'Logitech-Brio-4K.jpg', '4K Ultra HD Business Webcam | Up to 30 frame per second 4K | Dual integrated omnidirectional mics with noise-canceling technology | Powered by both optical and infrared sensors | Brio retains a high frame rate (up to 90 fps) | The Logi Tune Desktop app | Certified for Business'),
(3, 8, 'NUOXI H9 Cooling Pad', '15.6 inches Laptop Cooling LED Stand with 6-Core Fans', 13, 13.99, 0.00, 'NUOXI-H9-Cooling-Pad.jpg', 'Support up to 15.6 inches laptop | 6 core fans cooling | Fan come with blue LED lighting | Adjustable speed fan | One USB passthrough port'),
(4, 8, 'NUOXI N3 Laptop Stand', 'Lightweight and portable aluminum stand for comfortable laptop use anywhere', 20, 14.99, 0.00, 'NUOXI-N3-Laptop-Stand.jpg', 'Aluminum built quality | Foldable and easy to carry | Adjustable stand | Come with carrying bag'),
(5, 8, 'UGREEN Revodok USB C Hub', '5-in-1 USB C Multiport Adapter', 4, 14.53, 0.00, 'UGREEN-5-Usb-C-Hub.jpg', '5-in-1 Connectivity | 100W Pass-Through Charging: Equipped with a 100W USB-C PD port | 4K Stunning Display | 5Gbps Files Transfer | Broad Compatibility'),
(6, 8, 'UGREEN Bluetooth Adapter', '5.0 Bluetooth Receiver Compatible with Desktop Laptop Mouse Keyboard Printer Speaker', 11, 9.99, 0.00, 'UGREEN-Bluetooth-Adapter.jpg', 'Bluetooth 5.0 Adapter for PC | Universal Compatibility | Faster Speed, Farther Coverage | Multiple Connection | Mini Size and Convenient'),
(7, 8, 'UGREEN Ethernet Cable', 'NW101 CAT 6 U/UTP Round Ethernet Patch Cable Gigabit RJ45 Network Wire LAN', 18, 10.59, 0.00, 'UGREEN-Ethernet-Cable.png', 'Model: NW101 | Cable length: 1m, 2m, 3m, 5m, 10m | Standard: Cat 6 UP | Specification 10,100,1000 Base-T | Backward Compatibility | Bandwidth up to 1 Gbps | 26AWG | Reinforced RJ45 connectors'),
(8, 8, 'UGREEN HDMI To HDMI Cable', '40412 supports 4K, Full HD, 3D, high-speed connectivity', 12, 7.99, 0.00, 'UGREEN-HDMI-HDMI-Cable.jpg', 'High speed HDMI Cable with Ethernet | Data transfer rate up to 10.2 Gb/s | Supports Dolby TrueHD and DTS-HD Master AudioTM | HDCP compliant | HDMI Ethernet Channel | Support 4K resolution'),
(9, 8, 'Xiaomi Mi Computer Monitor Light Bar', 'Easy installation with wireless remote for extra desktop lighting', 4, 60.99, 17.25, 'Xiaomi-Mi-Computer-Monitor-Light-Bar.webp', 'Space-saving monitor light | No screen glare with asymmetric design | Ra95 high-quality lighting | Wireless 2.4GHz remote for adjustments | USB Type-C powered with auto on'),
(10, 6, 'Monka Contra GT-96 Wireless Gaming Controller', 'PS4 Wireless Gaming Controller', 4, 34.99, 0.00, 'Monka-Contra-GT-96-Controller.webp', 'Dual vibration effects | Precise operation | 6-axis sensor | Multi-platform compatible | 2-point capacitive touch pad'),
(11, 6, 'PlayStation 5 Controller', 'DualSense wireless controller with immersive feedback and modern design', 3, 69.99, 0.00, 'PlayStation-5-Controller.webp', 'Compatible with PS5/PC/Android/iOS | Haptic feedback & adaptive triggers | Built-in mic & speaker | 6-axis motion sensor | USB-C charging | Touchpad | Rechargeable battery'),
(12, 6, 'Razer Wolverine V3 Pro Wireless Gaming Controller', 'Licensed for Xbox Series, Xbox One, Windows PC', 4, 199.99, 22.50, 'Razer-Wolverine-V3-Pro.jpg', '4 back buttons & 2 bumpers | Adjustable hypertriggers | Hall effect thumbsticks | Hyperspeed wireless (2.4GHz) | Mecha-tactile buttons & 8-way D-pad | Custom profiles via app | PC tournament mode (1000Hz) | Includes case & 10ft cable'),
(13, 6, 'SCUF Envision Pro Jankz', 'High-performance remappable buttons controller, instant triggers, iCUE integration', 16, 129.99, 0.00, 'SCUF-Envision-Pro-Jankz.png', 'Four remappable rear paddles for faster reactions | Five G-Keys with iCUE macro support | Two side SAX buttons for extra control | OMRON mechanical switches for instant clicks | SlipStream ultra-fast wireless | iCUE required for initial setup'),
(14, 6, 'Xbox Wireless Controller', 'Textured triggers and bumpers - Hybrid D-pad - Button mapping', 12, 59.99, 0.00, 'Xbox-Wireless-Controller.webp', 'Compatible with Xbox One & Windows 10 | Bluetooth for PC/tablet gaming | Textured grip for better control | Twice the wireless range | Enhanced comfort and feel'),
(15, 7, 'Corsair HS35', 'Stereo Gaming Headset works with PC, iOS, Android', 11, 49.99, 20.00, 'Corsair-HS35.jpg', '20Hz-20kHz response | 32 Ohms impedance | Multi-platform (PC, Xbox, PS4, Switch, mobile) | 50mm drivers | Detachable mic with noise reduction | Plush memory foam & adjustable cups | On-ear volume & mute controls'),
(16, 7, 'HyperX Cloud III', 'Wired Gaming Headset, Angled 53mm Drivers, DTS Spatial Audio', 12, 89.99, 0.00, 'HyperX-Cloud-III.webp', 'HyperX comfort & durability | 53mm drivers for great audio | 10mm noise-cancelling mic with LED mute | Multi-platform (3.5mm, USB-C, USB-A)'),
(17, 7, 'Monka Echo HG9069W', 'Wireless Stereo Gaming Headset', 6, 34.99, 0.00, 'MONKA-Echo-HG9069W.webp', 'Wireless Stereo Gaming Headset | Soft-cushioned leather ear cups for a supremely comfortable fit | Effortless in-line audio control | Illuminating RGB light display | Omnidirectional mic for crystal clear communication'),
(18, 7, 'Razer BlackShark V2 X', 'Gaming Headset 7.1 Surround Sound - 50mm Drivers - Memory Foam Cushion', 16, 32.99, 0.00, 'Razer-BlackShark-V2-X.jpg', '7.1 surround sound (Windows 10 64-bit) | Triforce 50mm drivers | Passive noise cancellation | Lightweight, breathable foam cushions | Bendable HyperClear mic | Cross-platform compatibility'),
(19, 7, 'SteelSeries New Arctis Nova 3', 'Signature Arctis Sound - ClearCast Gen 2 Mic', 13, 74.59, 0.00, 'Steelseries-Arctis-Nova-3.jpg', 'Custom Nova Acoustic System | 360° Spatial Audio | ComfortMAX System with AirWeave Memory Foam | AI-powered ClearCast Gen 2 mic | Dynamic RGB lighting with 16.8M colors | Multi-platform support (USB-C)'),
(20, 1, 'Ajazz AK680 Max', '68% Top-mounted Mechanical Keyboard With Magnetic Switch', 19, 59.99, 25.00, 'Ajazz-AK680-Max.webp', 'Compact 68% layout with Magnetic Switch | Aluminum Plate & Hot-swappable PCB | Type-C Wired Connectivity | High Polling Rate & Ultra-low Latency | South-facing RGB Backlight Available'),
(21, 1, 'Ajazz AK820 Pro', '75% Gasket-mounted Bluetooth 5.1/2.4G Wireless & Type-C Wired Mechanical Keyboard', 7, 62.10, 0.00, 'Ajazz-AK820-Pro.webp', 'TFT Screen: The Interactive Interface | A Popular 75% Compact Design | Triple Modes: Type-C Wired, Bluetooth 5.0 & 2.4GHz Available | Gasket-mounted & Flex-cut PC Plate and PCB | South-facing LEDs with 1.6M RGB & Mac/Win Compatibility'),
(22, 1, 'Ajazz AKL680', '65% Low Profile Bluetooth5.1/2.4Ghz Wireless Dual Connections Mechanical Keyboard', 16, 35.99, 0.00, 'Ajazz-AKL680.webp', '68 keys, compact layout | Low Profile Version | Bluetooth 5.1/2.4GHz Wireless | ABS Plastic Shell | Windows/Mac OS Compatibility'),
(23, 1, 'MG108B Bun Wonderland', 'Akko x MonsGeek Bun Wonderland Keyboard', 14, 79.99, 0.00, 'Akko-MG-108B-MAO-Bun-Wonderland.png', 'PBT Dye-Sub Keycaps with durable legends | Multi-modes: Bluetooth 5.0, 2.4GHz (with receiver), and Type-C wired | Adjustable 3-level height stand | Linear switch: 45 ± 5gf force, 3.1mm total travel, 1.9 ± 0.5mm pre-travel'),
(24, 1, 'Epomaker Cypher 81', '75% Gasket Mount Mechanical Keyboard with TFT Screen', 14, 89.99, 0.00, 'Epomaker-Cypher-81.webp', 'Popular 75% Compact Gaming Keyboard | Tri-Mode Wireless & Mac/WIN Compatible | 0.85inch TFT Screen for live updates and customization | Per-key South-facing RGB Backlight | Gasket-Mount Keyboard with PC Plate'),
(25, 1, 'EPOMAKER RT100', '95% Retro Mechanical Keyboard with Knob and Mini Display', 9, 115.99, 30.00, 'EPOMAKER-RT100.webp', '97-key (ISO-UK: 98 Keys) Mechanical Keyboard with a Numpad & a Knob | Gasket-mounted & Ergonomic Design | Bluetooth 5.0 & 2.4G Wireless, Type-C Wired Connection | Smart Mini TV for Customizable Display | South-Facing LEDs & Windows / MacOS Compatible'),
(26, 1, 'Epomaker TH66', '65% Hot Swappable RGB 2.4Ghz/Bluetooth 5.0/Wired Mechanical Gaming Keyboard', 16, 36.00, 0.00, 'EPOMAKER-TH66.webp', '65% 66 Keys Hot-swappable Mechanical Gaming Keyboard | Upgraded Gateron Pro Mechanical Switches & EVA Dampeners | Three Modes of Connectivity: Bluetooth 5.0 & 2.4GHz & Type-C Cable | South Facing RGB backlights | PBT Keycaps in MDA Profile & Dye-sub Technique | Software for Win & Mac'),
(27, 1, 'EPOMAKER x AULA F75', '75% Gasket Wireless Mechanical Keyboard', 8, 59.99, 0.00, 'EPOMAKER-x-AULA-F75.webp', 'Optimized 75% Compact Layout | Gasket Structure & Hot-Swap Functionality | Vibrant 16.8 Million Color RGB Illumination | Three-Way Connectivity for Every Scenario | Extended Use with a 4000mAh Battery'),
(28, 1, 'EPOMAKER x LEOBOG Hi75', 'Sleek 75% Wired Mechanical Keyboard & Kit', 13, 89.99, 20.00, 'EPOMAKER-x-Leobog-Hi75.webp', '81 keys, compact layout | Full Key Hot-Swap Capability | Advanced Gaming-Grade Chip | Gasket-Mounted & NKRO | Durable Aluminum Alloy Build'),
(29, 1, 'RK Royal KLUDGE M75', 'Gasket Mount 75% Wireless Keyboard', 12, 84.99, 0.00, 'RK-Royal-KLUDGE-M75.webp', '3750mAh built-in battery | Tri-Mode connectivity | OLED Smart Display & Knob | Hot Swappable Switch | Cherry Profile PBT Keycaps | South-facing RGB Lighting Design'),
(30, 2, 'Epomaker Earl Grey Keycaps', '168 Keys Cherry Profile Dye Sublimation PBT Full Keycaps Set', 8, 20.59, 0.00, 'EPOMAKER-Earl-Grey-Keycaps.webp', 'Cherry profile keycaps full set | Earl Grey color scheme | PBT high-quality material | Full-size set, suitable for ANSI layout | Dye sublimation technique'),
(31, 2, 'EPOMAKER Lavender Keycaps Set', '133 Keys Cherry Profile Dye Sublimation PBT Shine-through Keycaps Set', 17, 24.99, 0.00, 'EPOMAKER-Lavender-Keycaps.webp', 'Twilight-inspired gradient design | Cherry profile: ergonomic, comfortable typing experience | Double shot PBT material: durable, high-quality build | Side-printed & RGB shine through | For ANSI layouts'),
(32, 2, 'Epomaker Mango Dessert Keycaps Set', '134 Keys PBT XDA Keycaps set', 11, 35.99, 0.00, 'EPOMAKER-Mango-Dessert-Keycaps.webp', 'XDA profile keycaps | 134 keys for various keyboards | High-quality PBT material | Compatible with Cherry, Gateron, Kailh, Otemu, MX structure'),
(33, 2, 'Epomaker Matcha Keycaps', '124 Keys XDA Profile Dye Sublimation PBT Full Keycaps Set', 13, 29.99, 24.00, 'EPOMAKER-Matcha-Keycaps.webp', 'XDA profile keycaps full set | Epomaker Matcha color scheme | PBT high-quality material | Full-size set, suitable for ANSI layout | Dye sublimation technique | Compatible with 60%, 65%, 75%, TKL, 1800 compact, full-size keyboards'),
(34, 2, 'Epomaker Meow Sushi keycaps Set', '143 Keys MOA Profile Dye Sublimation PBT Keycaps Set', 5, 29.99, 0.00, 'EPOMAKER-Meow-Sushi-Keycaps.webp', 'Themed graphics blending culinary art and feline cuteness | Durable, high-quality PBT material | Vivid and long-lasting dye-sublimation print | Ergonomic MOA profile for enhanced touch and feel | 143-key set offering extensive compatibility'),
(35, 2, 'EPOMAKER Pixel-Plush Keycap Set', '141 Keys MOA Profile Dye Sublimation PBT Full Keycaps Set', 7, 39.99, 30.00, 'EPOMAKER-Pixel-Plush-Keycap.webp', 'Enchanting purple-white gaming theme | Long-lasting, high-quality PBT material in MOA profile | Vivid dye-sublimation printing | Fully compatible with Cherry MX switches and clones | 141 keys for extensive compatibility'),
(36, 2, 'SKYLOONG Dark Fairy Pudding Keycaps Set', '120 Keys GK7-profile PBT Top & PC Sides Keycaps Set', 19, 39.00, 0.00, 'SKYLOONG-Dark-Fairy-Pudding-Keycaps.webp', 'PBT top & PC sides | Dark fairy color-gradient scheme | MX structure stem, compatible with most switches'),
(37, 4, 'Ajazz AJ159 Apex', 'Wired & Wireless Gaming Mouse', 6, 34.99, 0.00, 'Ajazz-AJ159-Apex.jpg', 'Triple modes connection & high polling rate | 400mAh battery with low-power consumption | Magnetic charging base | PAW3395 sensor | 100 million clicks micro switch'),
(38, 4, 'VXE Dragonfly R1', 'Lightweight Wireless Gaming Mouse', 13, 23.99, 25.00, 'ATK-VXE-Dragonfly-R1-Pro.jpg', 'Sensor: PAW3395 SE / PAW3395 | MCU: BEKEN(compx) / Nordic 52840 | Polling Rate: 125-4000Hz (Adjustable) | Weight: 48g-55g (Varies by Model) | Coating: Ice-feeling | Dimensions: 120.6mm x 64mm x 37.8mm'),
(39, 4, 'ATTACK SHARK R1', '59g SUPERLIGHT Mouse, PixArt PAW3311 Gaming Sensor, Bluetooth/2.4G Wireless/Wired', 16, 29.99, 0.00, 'Attack-Shark-R1.webp', '59g Ultra-Light | 3311 Gaming Sensor | BK3633 Three Modes | 20 Million Clicks | 65 Hours Battery | Driver Software | Smart Sleep | Ergonomic Design | Wide Applicability'),
(40, 4, 'ATTACK SHARK X3', '49g SUPERLIGHT, PixArt PAW3395 Gaming Sensor, BT/2.4G Wireless/Wired, 6 Adjustable DPI up to 26000, 200 hrs Battery', 17, 39.99, 0.00, 'Attack-Shark-X3.webp', '49g Super Light | 3395 Gaming Sensor | BK3633 Tri-modes | 80 Million Clicks | 200 Hours Battery | TTC Encoder | Self-developed Driver | Smart Sleep | Ergonomic Design | Wide Applicability'),
(41, 4, 'ATTACK SHARK X6', '49g SUPERLIGHT, Magnetic Charging Dock, PixArt PAW3395 Gaming Sensor, BT/2.4G Wireless/Wired Gaming Mouse, 26000 DPI', 12, 48.99, 0.00, 'Attack-Shark-X6.webp', '49g Super Light | Charging Dock | 3395 Gaming Sensor | BK3633 Tri-modes | 80 Million Clicks | 200 Hours Battery | TTC Encoder | Self-developed Driver | Ergonomic Design | Wide Applicability'),
(42, 4, 'ATTACK SHARK X11', 'Gaming Mouse with Magnetic Charging Dock, PixArt PAW3311 Gaming Sensor, BT/2.4G Wireless/Wired', 17, 27.99, 0.00, 'Attack-Shark-X11.webp', 'Charging Dock | 3311 Gaming Sensor | BK3633 Three Modes | 63g Ultra-Light | 20 Million Clicks | 65 Hours Battery | TTC Encoder | Driver Software | Ergonomic Design | Wide Applicability'),
(43, 4, 'AULA SC580', 'Wired & Wireless Gaming Mouse with Six-Key Design', 4, 29.99, 22.50, 'Aula-SC580.jpg', 'Triple Mode Connectivity with High Polling Rate | Ergonomic & Lightweight Design | Long-Lasting Rechargeable Battery | New Custom AULA Sensor & 10000 DPI | Six Buttons for Enhanced Functionality'),
(44, 4, 'Logitech G305 LightSpeed', 'LightWeight Programmable 250h Battery Life 12K Sensor', 8, 29.99, 0.00, 'Logitech-G-305-LightSpeed.jpg', 'HERO Gaming Sensor with 12000 DPI, 400 IPS precision | LIGHTSPEED Wireless for lag-free performance | 250 hours battery life on a single AA | 99g lightweight design for high maneuverability | Compact with built-in USB receiver storage'),
(45, 4, 'Logitech G PRO X SUPERLIGHT', 'Ultra-Lightweight, HERO 25K Sensor, Programmable Long Battery Life', 10, 91.99, 0.00, 'Logitech-G-Pro-X-SuperLight.jpg', 'Meticulously designed with esports pros | Ultra-lightweight at under 63g, 25% weight reduction | Powered by Lightspeed for fast performance | Hero Sensor for precise, fast, and consistent control | Large PTFE feet for smooth glide | Compatible with Windows 8+ and macOS 10.11+'),
(46, 5, 'Black White Wavy Pattern', 'Desk Mat for Keyboard and Mouse with Stitched Edges', 13, 9.99, 0.00, 'Black-&-White-Wavy-Pattern.jpg', 'Fashion design with full-width print | 16x29.5-inch oversized size | 100% polyester fabric with smooth surface | 3mm thick anti-slip rubber bottom | Suitable for office, gaming, and learning | Wear-resistant with precision lock edge'),
(47, 5, 'Black Topographic Contour', '31.5x11.8in Xl Mousepad With Stitched Edge', 10, 11.99, 0.00, 'Black-Topographic.jpg', '31.5 x 11.8-inch mouse pad | 3mm thickness | Smooth Lycra surface | Non-slip rubber base | Water-resistant, easy to clean, and durable'),
(48, 5, 'Japanese Mountain Sunset', 'Japanese Mountain Sunrise Desk Mat – Aesthetic Large Mousepad for Anime Enthusiasts and Gamers', 5, 12.50, 35.00, 'Japanese-Mountain-Sunset.jpg', 'Japanese Mountain Sunrise Desk Mat by KaladynDesigns | Non-slip rubber base | Vibrant thermal print | Large surface for gaming & work | Easy to clean | Free U.S. shipping | 14-day returns'),
(49, 5, 'Red & Black Shrine Sunset', 'Japanese Desk Mat, Red & Black Cool Dark Mousepad decor, Shrine Japan art sun', 13, 8.99, 0.00, 'Red-&-Black-Shrine-Sunset.jpg', 'Japanese Wave Sunset Desk Mat | 3mm neoprene | Non-slip bottom | Hemmed edges | Vibrant colors | 4 sizes available | Adds artistic touch to your desk'),
(50, 5, 'Serene Blossom', 'Blossoms Mousepad ,Kawaii Japanese Mousepad', 8, 8.99, 0.00, 'Serene-Blossom.jpg', 'Serene Blossoms Mousepad | Kawaii Japanese design | Durable stitched edges | Smooth polyester surface for speed and control | Anti-slip rubber base | 8K high-quality print | Custom designs available'),
(51, 5, 'Wave Pattern Black White', 'Large Non Slip Rubber Mousepad, Stitched Edges Desk Pad', 20, 9.99, 0.00, 'Wave-Pattern-Black-White.jpg', 'Extended Mouse Pad 11.8\" x 31.5\" | Smooth, durable surface for precise mouse control | Waterproof, easy to clean | Long-lasting design | Perfect for gaming, work, or as a unique gift'),
(52, 3, 'Akko CS Lavender Purple Switch', '45 pcs - 3 pin and fits keycaps with standard MX structure', 6, 9.99, 0.00, 'Akko-Cs-Lavender-Purple.jpg', 'Akko CS Lavender Purple 36gf Tactile Switches | 18mm spring, smooth feedback | LED slot, SMD compatible | 60M keystrokes | MX-style, fits most keycaps'),
(53, 3, 'Epomaker Budgerigar Switch Set', 'Original 35 Pieces of Epomaker Budgerigar Switch Set for Mechanical Keyboard Replacement', 14, 15.99, 20.00, 'EPOMAKER-Budgerigar.webp', 'Factory Lubed & Intact Housings | 5-Pin, Compatible with MX Structure Keycaps & PCBs | Built-in LED Slot & Durable Lifespan | Tactile Type with 55gf Initial Force | Suitable for Customised Keyboards'),
(54, 3, 'Epomaker Sea Salt Switch Set', 'Original 35 Pieces of EPOMAKER Sea Salt Switch Set for Mechanical Keyboard Replacement', 15, 16.99, 0.00, 'EPOMAKER-Sea-Salt.webp', 'POM Stem with PC & Nylon Housing | 5-Pin, Compatible with MX Structure Keycaps | Built-in LED Slot & Durable Lifespan | Heavy Operating Force with Linear Feature | Suitable for customised keyboards'),
(55, 3, 'EPOMAKER Wisteria Switches Set', 'Original 30 Pieces of EPOMAKER Wisteria Mechanical Keyboard Switches Set', 5, 9.99, 0.00, 'EPOMAKER-Wisteria.webp', 'The Inspiration of EPOMAKER Wisteria Switch | POM+PTFE Stem, PC+PA66 Housing | Compatible with MX Structure Keycaps | Factory Lubed Switch Set | Built-in LED Slot & Durable Lifespan'),
(56, 3, 'KiiBOOM Sapphire Switches Set', '35 Pieces of KiiBOOM Sapphire Mechanical Keyboard Switches Set', 13, 15.99, 28.00, 'KiiBOOM Sapphire.webp', 'Compatible with MX Structure Keycaps | Factory Lubed Switch Set | Built-in LED Slot & Durable Lifespan'),
(57, 3, 'KiiBOOM Taro Cream Milk Switches Set', 'Original 35 Pieces of KiiBOOM Taro Cream Milk Mechanical Keyboard Switches Set', 17, 15.99, 0.00, 'KiiBOOM-Taro-Cream-Milk.webp', 'Self-lubricating POM Stem | Compatible with MX Structure Keycaps | Factory Lubed Switch Set | Built-in LED Slot & Durable Lifespan'),
(58, 3, 'LEOBOG Reaper Switch Set', 'Original 100 Pieces of LEOBOG Reaper Shaft Switch Set for Mechanical Keyboard Replacement', 8, 26.99, 0.00, 'LEOBOG-Reaper.webp', 'POM Stem, Nylon/PC Housing | Linear, Factory Lubed | 5 Pins with Durable Lifespan | Compatible with MX Structure Keycaps | Suitable for Customised Keyboards'),
(59, 8, 'Razer Stream Controller X', 'All-in-One Keypad for Streaming, Designed for PC & Mac Compatibility', 16, 148.95, 0.00, 'Razer-Stream-Controller-X.jpg', '15 SwitchBlade Buttons | Multi-Link Macros | Swappable Magnetic Faceplate | Detechable 50° Anti-slip Magnetic Stand | Compatiable With Leading Streaming Software | Designed For Efficient Multi-tasking'),
(60, 6, 'GameSir Nova Wireless Switch Pro Controller', 'Hall Effect Joysticks, RGB LED, Turbo, Motion Control', 12, 29.99, 0.00, 'GameSir-Nova-Wireless-Controller.jpg', 'Compatibility: Switch/Lite/OLED, Windows 10/11, Android, iOS, Steam Deck | Tactile Feedback: PS5-like motor | RGB Lights: 6 colors, 5 modes | Hall Effect Joysticks: No dead zone/drifting | 6-Axis Gyro: Precise tilt control | Macro Buttons: Customizable combos | Battery: 1200mAh, fast USB-C charging'),
(61, 1, 'Epomaker Split 65', 'Ergonomic, Split Design, Reduce Wrist Strain in Long Typing or Gaming Sessions', 5, 119.99, 0.00, 'Epomaker-Split-65.webp', 'Triple Modes Connectivity | NKRO & South-facing RGB Backlight | Top-Mount & Full-Key Hotswappable | QMK/VIA Programmable | 4 Layers of Sound Dampening Material'),
(62, 3, 'Akko V3 Creamy Purple Pro Switch', '45 pcs - 5 pin and fits keycaps with standard MX structure', 9, 11.99, 0.00, 'Akko-V3-Creamy-Purple-Pro.jpg', 'Tactile Switch | Operating Force: 30 ± 5gf | Total Travel: 3.0mm | Pre-Travel: 2.0 ± 0.6mm | Tactile Travel: 0.5mm | Tactile Force: 55gf ± 5gf'),
(63, 5, 'Space Themed Cosmic', 'Astrology Desk Mat, Celestial Desk Mat, Abstract Desk Mat', 8, 8.99, 0.00, 'Space-Themed-Cosmic.jpg', 'Desk Mat by SagesOfThePast | Smooth polyester top | Non-slip rubber base | Scratch-resistant | Supports optical/laser mice | Anti-fray edges | Easy to clean'),
(64, 4, 'Razer Viper V3 HyperSpeed', '82g Lightweight - Up to 280 Hr Battery - 30K DPI Optical Sensor', 12, 59.99, 0.00, 'Razer-Viper-V3-HyperSpeed.jpg', '82g lightweight design | Focus Pro 30K Sensor | 280 hours battery | Hyperspeed wireless | Gen-2 mechanical switches | On-mouse DPI control | #1 selling PC gaming brand | Optimize with Razer Synapse'),
(65, 1, 'YUNZII Keynovo IF98', '96% 1800 Layout Mechanical Keyboard', 18, 89.99, 0.00, 'YUNZII-Keynovo-IF98.webp', 'Hot swappable PCB, compatible with 3 or 5 pins mechanical switches | Gasket mount keyboard | Tri-mode wireless connection, Bluetooth 5.1+2.4GHz+Wired connection | Upgraded pre-lubed screw-in stabilizers | PBT double shot keycaps, cherry profile | Gateron G Pro 5 pins switches (pre-lubed) | RGB & support custom software | Upgrade log LED, can show the battery capacity'),
(66, 3, 'LEOBOG Nimbus Switch V3', 'Original 35 Pieces of LEOBOG Nimbus Switch V3 Set for Mechanical Keyboard Replacement', 6, 12.99, 0.00, 'LEOBOG-Nimbus-Switch-V3.webp', '5 Pin, Linear | POM Stem & Housings | Factory Lubed | Compatible with MX Structure Keycaps | Durable Lifespan: 60 Million Keystrokes');

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `imageID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `subImage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_image`
--

INSERT INTO `product_image` (`imageID`, `productID`, `subImage`) VALUES
(1, 9, 'Xiaomi-Mi-Computer-Monitor-Light-Bar-2.webp'),
(2, 9, 'Xiaomi-Mi-Computer-Monitor-Light-Bar-3.webp'),
(3, 9, 'Xiaomi-Mi-Computer-Monitor-Light-Bar-4.webp'),
(4, 9, 'Xiaomi-Mi-Computer-Monitor-Light-Bar-5.webp'),
(5, 9, 'Xiaomi-Mi-Computer-Monitor-Light-Bar-6.webp'),
(6, 9, 'Xiaomi-Mi-Computer-Monitor-Light-Bar-7.webp'),
(7, 9, 'Xiaomi-Mi-Computer-Monitor-Light-Bar-8.webp'),
(8, 9, 'Xiaomi-Mi-Computer-Monitor-Light-Bar-9.webp'),
(9, 10, 'Monka-Contra-GT-96-Controller-2.webp'),
(10, 10, 'Monka-Contra-GT-96-Controller-3.webp'),
(11, 10, 'Monka-Contra-GT-96-Controller-4.webp'),
(12, 10, 'Monka-Contra-GT-96-Controller-5.webp'),
(13, 11, 'PlayStation-5-Controller-2.webp'),
(14, 11, 'PlayStation-5-Controller-3.webp'),
(15, 11, 'PlayStation-5-Controller-4.webp'),
(16, 11, 'PlayStation-5-Controller-5.webp'),
(17, 12, 'Razer-Wolverine-V3-Pro-2.jpg'),
(18, 12, 'Razer-Wolverine-V3-Pro-3.jpg'),
(19, 12, 'Razer-Wolverine-V3-Pro-4.jpg'),
(20, 12, 'Razer-Wolverine-V3-Pro-5.jpg'),
(21, 12, 'Razer-Wolverine-V3-Pro-6.jpg'),
(22, 12, 'Razer-Wolverine-V3-Pro-7.jpg'),
(23, 13, 'SCUF-Envision-Pro-Jankz-2.png'),
(24, 13, 'SCUF-Envision-Pro-Jankz-3.png'),
(25, 13, 'SCUF-Envision-Pro-Jankz-4.png'),
(26, 14, 'Xbox-Wireless-Controller-2.webp'),
(27, 14, 'Xbox-Wireless-Controller-3.webp'),
(28, 14, 'Xbox-Wireless-Controller-4.webp'),
(29, 14, 'Xbox-Wireless-Controller-5.webp'),
(30, 15, 'Corsair-HS35-2.jpg'),
(31, 15, 'Corsair-HS35-3.jpg'),
(32, 15, 'Corsair-HS35-4.jpg'),
(33, 15, 'Corsair-HS35-5.jpg'),
(34, 16, 'HyperX-Cloud-III-2.webp'),
(35, 16, 'HyperX-Cloud-III-3.webp'),
(36, 16, 'HyperX-Cloud-III-4.webp'),
(37, 16, 'HyperX-Cloud-III-5.webp'),
(38, 16, 'HyperX-Cloud-III-6.webp'),
(39, 16, 'HyperX-Cloud-III-7.webp'),
(40, 17, 'MONKA-Echo-HG9069W-2.webp'),
(41, 17, 'MONKA-Echo-HG9069W-3.webp'),
(42, 17, 'MONKA-Echo-HG9069W-4.webp'),
(43, 17, 'MONKA-Echo-HG9069W-5.webp'),
(44, 18, 'Razer-BlackShark-V2-X-2.jpg'),
(45, 18, 'Razer-BlackShark-V2-X-3.jpg'),
(46, 18, 'Razer-BlackShark-V2-X-4.jpg'),
(47, 18, 'Razer-BlackShark-V2-X-5.jpg'),
(48, 18, 'Razer-BlackShark-V2-X-6.jpg'),
(49, 19, 'Steelseries-Arctis-Nova-3-2.jpg'),
(50, 19, 'Steelseries-Arctis-Nova-3-3.jpg'),
(51, 19, 'Steelseries-Arctis-Nova-3-4.jpg'),
(52, 19, 'Steelseries-Arctis-Nova-3-5.jpg'),
(53, 19, 'Steelseries-Arctis-Nova-3-6.jpg'),
(54, 19, 'Steelseries-Arctis-Nova-3-7.jpg'),
(55, 20, 'Ajazz-AK680-Max-2.webp'),
(56, 20, 'Ajazz-AK680-Max-3.webp'),
(57, 20, 'Ajazz-AK680-Max-4.webp'),
(58, 20, 'Ajazz-AK680-Max-5.webp'),
(59, 20, 'Ajazz-AK680-Max-6.webp'),
(60, 20, 'Ajazz-AK680-Max-7.webp'),
(61, 21, 'Ajazz-AK820-Pro-2.webp'),
(62, 21, 'Ajazz-AK820-Pro-3.webp'),
(63, 21, 'Ajazz-AK820-Pro-4.webp'),
(64, 21, 'Ajazz-AK820-Pro-5.webp'),
(65, 22, 'Ajazz-AKL680-2.webp'),
(66, 22, 'Ajazz-AKL680-3.webp'),
(67, 23, 'Akko-MG-108B-MAO-Bun-Wonderland-2.png'),
(68, 23, 'Akko-MG-108B-MAO-Bun-Wonderland-3.png'),
(69, 23, 'Akko-MG-108B-MAO-Bun-Wonderland-4.png'),
(70, 24, 'Epomaker-Cypher-81-2.webp'),
(71, 24, 'Epomaker-Cypher-81-3.webp'),
(72, 24, 'Epomaker-Cypher-81-4.webp'),
(73, 24, 'Epomaker-Cypher-81-5.webp'),
(74, 24, 'Epomaker-Cypher-81-6.webp'),
(75, 24, 'Epomaker-Cypher-81-7.webp'),
(76, 24, 'Epomaker-Cypher-81-8.webp'),
(77, 25, 'EPOMAKER-RT100-2.webp'),
(78, 25, 'EPOMAKER-RT100-3.webp'),
(79, 25, 'EPOMAKER-RT100-4.webp'),
(80, 25, 'EPOMAKER-RT100-5.webp'),
(81, 25, 'EPOMAKER-RT100-6.webp'),
(82, 25, 'EPOMAKER-RT100-7.webp'),
(83, 25, 'EPOMAKER-RT100-8.webp'),
(84, 25, 'EPOMAKER-RT100-9.webp'),
(85, 25, 'EPOMAKER-RT100-10.webp'),
(86, 26, 'EPOMAKER-TH66-2.webp'),
(87, 26, 'EPOMAKER-TH66-3.webp'),
(88, 26, 'EPOMAKER-TH66-4.webp'),
(89, 26, 'EPOMAKER-TH66-5.webp'),
(90, 26, 'EPOMAKER-TH66-6.webp'),
(91, 26, 'EPOMAKER-TH66-7.webp'),
(92, 27, 'EPOMAKER-x-AULA-F75-2.webp'),
(93, 27, 'EPOMAKER-x-AULA-F75-3.webp'),
(94, 27, 'EPOMAKER-x-AULA-F75-4.webp'),
(95, 27, 'EPOMAKER-x-AULA-F75-5.webp'),
(96, 27, 'EPOMAKER-x-AULA-F75-6.webp'),
(97, 27, 'EPOMAKER-x-AULA-F75-7.webp'),
(98, 27, 'EPOMAKER-x-AULA-F75-8.webp'),
(99, 27, 'EPOMAKER-x-AULA-F75-9.webp'),
(100, 27, 'EPOMAKER-x-AULA-F75-10.webp'),
(101, 28, 'EPOMAKER-x-Leobog-Hi75-2.webp'),
(102, 28, 'EPOMAKER-x-Leobog-Hi75-3.webp'),
(103, 28, 'EPOMAKER-x-Leobog-Hi75-4.webp'),
(104, 28, 'EPOMAKER-x-Leobog-Hi75-5.webp'),
(105, 28, 'EPOMAKER-x-Leobog-Hi75-6.webp'),
(106, 28, 'EPOMAKER-x-Leobog-Hi75-7.webp'),
(107, 29, 'RK-Royal-KLUDGE-M75-2.webp'),
(108, 29, 'RK-Royal-KLUDGE-M75-3.webp'),
(109, 29, 'RK-Royal-KLUDGE-M75-4.webp'),
(110, 30, 'EPOMAKER-Earl-Grey-Keycaps-2.webp'),
(111, 30, 'EPOMAKER-Earl-Grey-Keycaps-3.webp'),
(112, 30, 'EPOMAKER-Earl-Grey-Keycaps-4.webp'),
(113, 30, 'EPOMAKER-Earl-Grey-Keycaps-5.webp'),
(114, 30, 'EPOMAKER-Earl-Grey-Keycaps-6.webp'),
(115, 30, 'EPOMAKER-Earl-Grey-Keycaps-7.webp'),
(116, 31, 'EPOMAKER-Lavender-Keycaps-2.webp'),
(117, 31, 'EPOMAKER-Lavender-Keycaps-3.webp'),
(118, 31, 'EPOMAKER-Lavender-Keycaps-4.webp'),
(119, 31, 'EPOMAKER-Lavender-Keycaps-5.webp'),
(120, 32, 'EPOMAKER-Mango-Dessert-Keycaps-2.webp'),
(121, 32, 'EPOMAKER-Mango-Dessert-Keycaps-3.webp'),
(122, 32, 'EPOMAKER-Mango-Dessert-Keycaps-4.webp'),
(123, 32, 'EPOMAKER-Mango-Dessert-Keycaps-5.webp'),
(124, 32, 'EPOMAKER-Mango-Dessert-Keycaps-6.webp'),
(125, 32, 'EPOMAKER-Mango-Dessert-Keycaps-7.webp'),
(126, 32, 'EPOMAKER-Mango-Dessert-Keycaps-8.webp'),
(127, 33, 'EPOMAKER-Matcha-Keycaps-2.webp'),
(128, 33, 'EPOMAKER-Matcha-Keycaps-3.webp'),
(129, 33, 'EPOMAKER-Matcha-Keycaps-4.webp'),
(130, 33, 'EPOMAKER-Matcha-Keycaps-5.webp'),
(131, 33, 'EPOMAKER-Matcha-Keycaps-6.webp'),
(132, 33, 'EPOMAKER-Matcha-Keycaps-7.webp'),
(133, 33, 'EPOMAKER-Matcha-Keycaps-8.webp'),
(134, 33, 'EPOMAKER-Matcha-Keycaps-9.webp'),
(135, 34, 'EPOMAKER-Meow-Sushi-Keycaps-2.webp'),
(136, 34, 'EPOMAKER-Meow-Sushi-Keycaps-3.webp'),
(137, 34, 'EPOMAKER-Meow-Sushi-Keycaps-4.webp'),
(138, 34, 'EPOMAKER-Meow-Sushi-Keycaps-5.webp'),
(139, 34, 'EPOMAKER-Meow-Sushi-Keycaps-6.webp'),
(140, 35, 'EPOMAKER-Pixel-Plush-Keycap-2.webp'),
(141, 35, 'EPOMAKER-Pixel-Plush-Keycap-3.webp'),
(142, 35, 'EPOMAKER-Pixel-Plush-Keycap-4.webp'),
(143, 36, 'SKYLOONG-Dark-Fairy-Pudding-Keycaps-2.webp'),
(144, 36, 'SKYLOONG-Dark-Fairy-Pudding-Keycaps-3.webp'),
(145, 36, 'SKYLOONG-Dark-Fairy-Pudding-Keycaps-4.webp'),
(146, 36, 'SKYLOONG-Dark-Fairy-Pudding-Keycaps-5.webp'),
(147, 37, 'Ajazz-AJ159-Apex-2.jpg'),
(148, 37, 'Ajazz-AJ159-Apex-3.jpg'),
(149, 37, 'Ajazz-AJ159-Apex-4.jpg'),
(150, 37, 'Ajazz-AJ159-Apex-5.jpg'),
(151, 37, 'Ajazz-AJ159-Apex-6.jpg'),
(152, 37, 'Ajazz-AJ159-Apex-7.jpg'),
(153, 37, 'Ajazz-AJ159-Apex-8.jpg'),
(154, 37, 'Ajazz-AJ159-Apex-9.jpg'),
(155, 38, 'ATK-VXE-Dragonfly-R1-Pro-2.jpg'),
(156, 38, 'ATK-VXE-Dragonfly-R1-Pro-3.jpg'),
(157, 38, 'ATK-VXE-Dragonfly-R1-Pro-4.jpg'),
(158, 38, 'ATK-VXE-Dragonfly-R1-Pro-5.jpg'),
(159, 38, 'ATK-VXE-Dragonfly-R1-Pro-6.jpg'),
(160, 38, 'ATK-VXE-Dragonfly-R1-Pro-7.jpg'),
(161, 38, 'ATK-VXE-Dragonfly-R1-Pro-8.jpg'),
(162, 39, 'Attack-Shark-R1-2.webp'),
(163, 39, 'Attack-Shark-R1-3.webp'),
(164, 39, 'Attack-Shark-R1-4.webp'),
(165, 39, 'Attack-Shark-R1-5.webp'),
(166, 39, 'Attack-Shark-R1-6.webp'),
(167, 39, 'Attack-Shark-R1-7.webp'),
(168, 39, 'Attack-Shark-R1-8.webp'),
(169, 40, 'Attack-Shark-X3-2.webp'),
(170, 40, 'Attack-Shark-X3-3.webp'),
(171, 40, 'Attack-Shark-X3-4.webp'),
(172, 40, 'Attack-Shark-X3-5.webp'),
(173, 40, 'Attack-Shark-X3-6.webp'),
(174, 40, 'Attack-Shark-X3-7.webp'),
(175, 40, 'Attack-Shark-X3-8.webp'),
(176, 40, 'Attack-Shark-X3-9.webp'),
(177, 41, 'Attack-Shark-X6-2.webp'),
(178, 41, 'Attack-Shark-X6-3.webp'),
(179, 41, 'Attack-Shark-X6-4.webp'),
(180, 41, 'Attack-Shark-X6-5.webp'),
(181, 41, 'Attack-Shark-X6-6.webp'),
(182, 42, 'Attack-Shark-X11-2.webp'),
(183, 42, 'Attack-Shark-X11-3.webp'),
(184, 42, 'Attack-Shark-X11-4.webp'),
(185, 42, 'Attack-Shark-X11-5.webp'),
(186, 42, 'Attack-Shark-X11-6.webp'),
(187, 42, 'Attack-Shark-X11-7.webp'),
(188, 43, 'Aula-SC580-2.jpg'),
(189, 43, 'Aula-SC580-3.jpg'),
(190, 43, 'Aula-SC580-4.jpg'),
(191, 43, 'Aula-SC580-5.jpg'),
(192, 43, 'Aula-SC580-6.jpg'),
(193, 43, 'Aula-SC580-7.jpg'),
(194, 43, 'Aula-SC580-8.jpg'),
(195, 43, 'Aula-SC580-9.jpg'),
(196, 44, 'Logitech-G-305-LightSpeed-2.jpg'),
(197, 44, 'Logitech-G-305-LightSpeed-3.jpg'),
(198, 44, 'Logitech-G-305-LightSpeed-4.jpg'),
(199, 44, 'Logitech-G-305-LightSpeed-5.jpg'),
(200, 44, 'Logitech-G-305-LightSpeed-6.jpg'),
(201, 45, 'Logitech-G-Pro-X-Superlight-2.jpg'),
(202, 45, 'Logitech-G-Pro-X-Superlight-3.jpg'),
(203, 45, 'Logitech-G-Pro-X-Superlight-4.jpg'),
(204, 45, 'Logitech-G-Pro-X-Superlight-5.jpg'),
(205, 45, 'Logitech-G-Pro-X-Superlight-6.jpg'),
(206, 45, 'Logitech-G-Pro-X-Superlight-7.jpg'),
(207, 45, 'Logitech-G-Pro-X-Superlight-8.jpg'),
(208, 52, 'Akko-Cs-Lavender-Purple-2.jpg'),
(209, 52, 'Akko-Cs-Lavender-Purple-3.jpg'),
(210, 52, 'Akko-Cs-Lavender-Purple-4.jpg'),
(211, 53, 'EPOMAKER-Budgerigar-2.webp'),
(212, 53, 'EPOMAKER-Budgerigar-3.webp'),
(213, 53, 'EPOMAKER-Budgerigar-4.webp'),
(214, 53, 'EPOMAKER-Budgerigar-5.webp'),
(215, 53, 'EPOMAKER-Budgerigar-6.webp'),
(216, 54, 'EPOMAKER-Sea-Salt-2.webp'),
(217, 54, 'EPOMAKER-Sea-Salt-3.webp'),
(218, 54, 'EPOMAKER-Sea-Salt-4.webp'),
(219, 54, 'EPOMAKER-Sea-Salt-5.webp'),
(220, 55, 'EPOMAKER-Wisteria-2.webp'),
(221, 55, 'EPOMAKER-Wisteria-3.webp'),
(222, 55, 'EPOMAKER-Wisteria-4.webp'),
(223, 55, 'EPOMAKER-Wisteria-5.webp'),
(224, 55, 'EPOMAKER-Wisteria-6.webp'),
(225, 55, 'EPOMAKER-Wisteria-7.webp'),
(226, 55, 'EPOMAKER-Wisteria-8.webp'),
(227, 56, 'KiiBOOM Sapphire-2.webp'),
(228, 56, 'KiiBOOM Sapphire-3.webp'),
(229, 56, 'KiiBOOM Sapphire-4.webp'),
(230, 56, 'KiiBOOM Sapphire-5.webp'),
(231, 57, 'KiiBOOM-Taro-Cream-Milk-2.webp'),
(232, 57, 'KiiBOOM-Taro-Cream-Milk-3.webp'),
(233, 57, 'KiiBOOM-Taro-Cream-Milk-4.webp'),
(234, 58, 'LEOBOG-Reaper-2.webp'),
(235, 58, 'LEOBOG-Reaper-3.webp'),
(236, 58, 'LEOBOG-Reaper-4.webp'),
(237, 58, 'LEOBOG-Reaper-5.webp'),
(238, 58, 'LEOBOG-Reaper-6.webp'),
(239, 58, 'LEOBOG-Reaper-7.webp'),
(240, 58, 'LEOBOG-Reaper-8.webp'),
(241, 59, 'Razer-Stream-Controller-X-2.jpg'),
(242, 59, 'Razer-Stream-Controller-X-3.jpg'),
(243, 59, 'Razer-Stream-Controller-X-4.jpg'),
(244, 59, 'Razer-Stream-Controller-X-5.jpg'),
(245, 59, 'Razer-Stream-Controller-X-6.jpg'),
(246, 59, 'Razer-Stream-Controller-X-7.jpg'),
(247, 60, 'GameSir-Nova-Wireless-Controller-2.jpg'),
(248, 60, 'GameSir-Nova-Wireless-Controller-3.jpg'),
(249, 60, 'GameSir-Nova-Wireless-Controller-4.jpg'),
(250, 60, 'GameSir-Nova-Wireless-Controller-5.jpg'),
(251, 60, 'GameSir-Nova-Wireless-Controller-6.jpg'),
(252, 60, 'GameSir-Nova-Wireless-Controller-7.jpg'),
(253, 60, 'GameSir-Nova-Wireless-Controller-8.jpg'),
(254, 60, 'GameSir-Nova-Wireless-Controller-9.jpg'),
(255, 61, 'Epomaker-Split-65-2.webp'),
(256, 61, 'Epomaker-Split-65-3.webp'),
(257, 61, 'Epomaker-Split-65-4.webp'),
(258, 61, 'Epomaker-Split-65-5.webp'),
(259, 61, 'Epomaker-Split-65-6.webp'),
(260, 61, 'Epomaker-Split-65-7.webp'),
(261, 61, 'Epomaker-Split-65-8.webp'),
(262, 61, 'Epomaker-Split-65-9.webp'),
(263, 62, 'Akko-V3-Creamy-Purple-Pro-2.jpg'),
(264, 62, 'Akko-V3-Creamy-Purple-Pro-3.jpg'),
(265, 62, 'Akko-V3-Creamy-Purple-Pro-4.jpg'),
(266, 62, 'Akko-V3-Creamy-Purple-Pro-5.jpg'),
(267, 62, 'Akko-V3-Creamy-Purple-Pro-6.jpg'),
(268, 64, 'Razer-Viper-V3-HyperSpeed-2.jpg'),
(269, 64, 'Razer-Viper-V3-HyperSpeed-3.jpg'),
(270, 64, 'Razer-Viper-V3-HyperSpeed-4.jpg'),
(271, 64, 'Razer-Viper-V3-HyperSpeed-5.jpg'),
(272, 64, 'Razer-Viper-V3-HyperSpeed-6.jpg'),
(273, 65, 'YUNZII-Keynovo-IF98-2.webp'),
(274, 65, 'YUNZII-Keynovo-IF98-3.webp'),
(275, 65, 'YUNZII-Keynovo-IF98-4.webp'),
(276, 65, 'YUNZII-Keynovo-IF98-5.webp'),
(277, 66, 'LEOBOG-Nimbus-Switch-V3-2.webp'),
(278, 66, 'LEOBOG-Nimbus-Switch-V3-3.webp'),
(279, 66, 'LEOBOG-Nimbus-Switch-V3-4.webp'),
(280, 66, 'LEOBOG-Nimbus-Switch-V3-5.webp'),
(281, 66, 'LEOBOG-Nimbus-Switch-V3-6.webp');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `email`, `password`) VALUES
(1, 'user1', 'user1@gmail.com', '$2y$10$aGtywFFuuiLkso8fPDXz8.QlZFdzdKIqCsszd0xJ9D6HM99dHxZ/i'),
(2, 'Chonghok', 'chonghok@gmail.com', '$2y$10$UGaaFCIZhKOllzQH8Yn2Wu5u6LIQgzQ5hfQhk/wvpIul9fTOKoTWS');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlistID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `FKcartUserID` (`userID`),
  ADD KEY `FKcartProductID` (`productID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`orderDetailID`),
  ADD KEY `FKorderID` (`orderID`),
  ADD KEY `FKorderDetailProductID` (`productID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `FKuserID` (`userID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `FKcategoryID` (`categoryID`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`imageID`),
  ADD KEY `FKproductID` (`productID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlistID`),
  ADD KEY `FKwishlistUserID` (`userID`),
  ADD KEY `FKwishlistProductID` (`productID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orderdetail`
--
ALTER TABLE `orderdetail`
  MODIFY `orderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `imageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlistID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FKcartProductID` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKcartUserID` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `FKorderDetailProductID` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FKorderID` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FKuserID` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FKcategoryID` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON UPDATE CASCADE;

--
-- Constraints for table `product_image`
--
ALTER TABLE `product_image`
  ADD CONSTRAINT `FKproductID` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `FKwishlistProductID` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKwishlistUserID` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
