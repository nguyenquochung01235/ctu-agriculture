-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 05, 2022 lúc 02:30 PM
-- Phiên bản máy phục vụ: 10.4.22-MariaDB
-- Phiên bản PHP: 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Cơ sở dữ liệu: `agriculture`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_07_24_024209_tbl_user', 1),
(6, '2022_07_24_024738_tbl_hoptacxa', 1),
(7, '2022_07_24_024944_tbl_xavien', 1),
(8, '2022_07_24_025249_tbl_nhacungcapvattu', 1),
(9, '2022_07_24_025420_tbl_thuonglai', 1),
(10, '2022_07_24_025743_tbl_rolexavien', 1),
(11, '2022_07_24_030047_xavien_rolexavien', 1),
(12, '2022_07_24_031259_tbl_thuadat', 1),
(13, '2022_07_24_100912_tbl_danhmucquydinh', 1),
(14, '2022_07_24_101134_tbl_category_vattu', 1),
(15, '2022_07_28_124253_tbl_gionglua', 1),
(16, '2022_07_28_124556_tbl_lichmuavu', 1),
(17, '2022_07_28_124957_tbl_hoatdongmuavu', 1),
(18, '2022_07_28_125307_tbl_nhatkydongruong', 1),
(19, '2022_07_28_125649_tbl_danhgiacuoimua', 1),
(22, '2022_07_28_132312_tbl_giaodich_luagiong', 1),
(23, '2022_07_28_134039_tbl_hopdongmuaban', 1),
(28, '2022_09_11_140235_tbl_menu_client', 2),
(29, '2022_09_15_142609_tbl_account', 3),
(30, '2022_09_15_142728_user_account', 3),
(31, '2022_07_28_130543_tbl_giaodichmuaban_vattu', 4),
(32, '2022_07_28_134842_tbl_giaodichmuaban_lua', 4),
(33, '2022_07_28_135132_tbl_lohang_lua', 4),
(34, '2022_08_04_031722_tbl_vattusudung', 4),
(35, '2022_10_30_030333_tbl_notification', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_account`
--

CREATE TABLE `tbl_account` (
  `id_account` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_account`
--

INSERT INTO `tbl_account` (`id_account`, `name`, `path`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Xã Viên', 'farmer', '1', NULL, NULL),
(2, 'Thương Lái', 'trader', '2', NULL, NULL),
(3, 'Nhà Cung Cấp Vật Tư', 'shop', '3', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_category_vattu`
--

CREATE TABLE `tbl_category_vattu` (
  `id_category_vattu` bigint(20) UNSIGNED NOT NULL,
  `id_danhmucquydinh` bigint(20) UNSIGNED NOT NULL,
  `name_category_vattu` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_category_vattu`
--

INSERT INTO `tbl_category_vattu` (`id_category_vattu`, `id_danhmucquydinh`, `name_category_vattu`, `active`, `created_at`, `updated_at`) VALUES
(1, 8, 'Thuốc trừ sâu Amixta Top', 1, '2022-10-22 09:40:48', '2022-10-22 09:40:48'),
(3, 8, 'Phân Ure 20-20-15', 1, '2022-10-22 20:58:24', '2022-10-25 06:16:52'),
(4, 8, 'Thuốc trừ sâu Amixta Top 2', 1, '2022-10-22 20:58:26', '2022-10-22 20:58:26'),
(5, 8, 'Thuốc trừ sâu Amixta Top 3', 1, '2022-10-22 20:58:29', '2022-10-22 20:58:29'),
(6, 8, 'Thuốc trừ sâu Amixta Top 4', 1, '2022-10-22 20:58:31', '2022-10-22 20:58:31'),
(7, 8, 'Thuốc trừ sâu Amixta Top 5', 1, '2022-10-22 20:58:34', '2022-10-22 20:58:34'),
(8, 8, 'Thuốc trừ sâu Amixta Top 6', 1, '2022-10-22 20:58:36', '2022-10-22 20:58:36'),
(9, 8, 'Thuốc trừ sâu Amixta Top 6', 1, '2022-10-22 20:58:37', '2022-10-22 20:58:37'),
(10, 8, 'Thuốc trừ sâu Amixta Top 6', 1, '2022-10-22 20:58:38', '2022-10-22 20:58:38'),
(11, 8, 'Thuốc trừ sâu Amixta Top 6', 1, '2022-10-22 20:58:39', '2022-10-22 20:58:39'),
(12, 8, 'Thuốc trừ sâu Amixta Top 6', 1, '2022-10-22 20:58:40', '2022-10-22 20:58:40'),
(13, 8, 'Thuốc trừ sâu Amixta Top 6', 1, '2022-10-22 20:58:41', '2022-10-22 20:58:41'),
(14, 8, 'Thuốc trừ sâu Amixta Top 6', 1, '2022-10-22 20:58:41', '2022-10-22 20:58:41'),
(15, 8, 'Thuốc trừ sâu Amixta Top 6', 1, '2022-10-22 20:58:42', '2022-10-22 20:58:42'),
(16, 8, 'Meeting discurd api', 1, '2022-10-25 06:19:54', '2022-10-25 06:19:54'),
(17, 8, 'Meeting discurd api', 1, '2022-10-25 06:22:18', '2022-10-25 06:22:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_danhgiacuoimua`
--

CREATE TABLE `tbl_danhgiacuoimua` (
  `id_danhgiacuoimua` bigint(20) UNSIGNED NOT NULL,
  `id_lichmuavu` bigint(20) UNSIGNED NOT NULL,
  `id_xavien` bigint(20) UNSIGNED NOT NULL,
  `id_thuadat` bigint(20) UNSIGNED NOT NULL,
  `giong` bigint(20) NOT NULL,
  `phanbon` bigint(20) NOT NULL,
  `xangdau` bigint(20) NOT NULL,
  `vattukhac` bigint(20) NOT NULL,
  `lamdat` bigint(20) NOT NULL,
  `gieosa` bigint(20) NOT NULL,
  `lamco` bigint(20) NOT NULL,
  `bomtuoi` bigint(20) NOT NULL,
  `thuhoach` bigint(20) NOT NULL,
  `rahat` bigint(20) NOT NULL,
  `phoisay` bigint(20) NOT NULL,
  `vanchuyen` bigint(20) NOT NULL,
  `thuyloiphi` bigint(20) NOT NULL,
  `tongsanluong` bigint(20) NOT NULL,
  `giaban` double NOT NULL,
  `khokhan` text COLLATE utf8_unicode_ci NOT NULL,
  `kiennghi` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_danhmucquydinh`
--

CREATE TABLE `tbl_danhmucquydinh` (
  `id_danhmucquydinh` bigint(20) UNSIGNED NOT NULL,
  `id_thuonglai` bigint(20) UNSIGNED NOT NULL,
  `name_danhmucquydinh` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_danhmucquydinh`
--

INSERT INTO `tbl_danhmucquydinh` (`id_danhmucquydinh`, `id_thuonglai`, `name_danhmucquydinh`, `active`, `created_at`, `updated_at`) VALUES
(1, 16, 'Danh Mục Sử Dụng Thuốc Theo Tiêu Chuẩn ISO 9001 - Xuất Khẩu Châu Âu', 1, '2022-10-08 21:26:33', '2022-10-08 21:26:33'),
(5, 16, 'Danh Mục Sử Dụng Thuốc Theo Tiêu Chuẩn ISO 9001 - Xuất Khẩu Châu Âu 2', 1, '2022-10-08 21:38:48', '2022-10-08 21:38:48'),
(8, 16, 'Danh Mục Sử Dụng Thuốc Theo Tiêu Chuẩn ISO 9001 - Xuất Khẩu Châu Âu 1', 1, '2022-10-22 06:43:49', '2022-10-22 06:49:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_giaodichmuaban_lua`
--

CREATE TABLE `tbl_giaodichmuaban_lua` (
  `id_giaodichmuaban_lua` bigint(20) UNSIGNED NOT NULL,
  `id_xavien` bigint(20) UNSIGNED NOT NULL,
  `id_thuonglai` bigint(20) UNSIGNED NOT NULL,
  `id_lichmuavu` bigint(20) UNSIGNED NOT NULL,
  `name_lohang` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `soluong` bigint(20) NOT NULL,
  `price_lohang` bigint(20) NOT NULL,
  `img_lohang` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_giaodich` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `hoptacxa_xacnhan` int(11) NOT NULL,
  `nhacungcap_xacnhan` int(11) NOT NULL,
  `xavien_xacnhan` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_giaodichmuaban_vattu`
--

CREATE TABLE `tbl_giaodichmuaban_vattu` (
  `id_giaodichmuaban_vattu` bigint(20) UNSIGNED NOT NULL,
  `id_xavien` bigint(20) UNSIGNED NOT NULL,
  `id_nhacungcapvattu` bigint(20) UNSIGNED NOT NULL,
  `id_lichmuavu` bigint(20) UNSIGNED NOT NULL,
  `id_category_vattu` bigint(20) UNSIGNED NOT NULL,
  `soluong` int(11) NOT NULL,
  `price` bigint(20) NOT NULL,
  `description_giaodich` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `hoptacxa_xacnhan` int(11) NOT NULL,
  `nhacungcap_xacnhan` int(11) NOT NULL,
  `xavien_xacnhan` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_giaodich_luagiong`
--

CREATE TABLE `tbl_giaodich_luagiong` (
  `id_giaodich_luagiong` bigint(20) UNSIGNED NOT NULL,
  `id_xavien` bigint(20) UNSIGNED NOT NULL,
  `id_nhacungcapvattu` bigint(20) UNSIGNED NOT NULL,
  `id_lichmuavu` bigint(20) UNSIGNED NOT NULL,
  `id_gionglua` bigint(20) UNSIGNED NOT NULL,
  `soluong` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `hoptacxa_xacnhan` int(11) NOT NULL,
  `nhacungcap_xacnhan` int(11) NOT NULL,
  `xavien_xacnhan` int(11) NOT NULL,
  `description_giaodich` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_gionglua`
--

CREATE TABLE `tbl_gionglua` (
  `id_gionglua` bigint(20) UNSIGNED NOT NULL,
  `name_gionglua` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_gionglua`
--

INSERT INTO `tbl_gionglua` (`id_gionglua`, `name_gionglua`, `created_at`, `updated_at`) VALUES
(1, 'ST-21', NULL, NULL),
(2, 'ST-25', NULL, NULL),
(3, 'MOM', NULL, NULL),
(4, '504', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_hoatdongmuavu`
--

CREATE TABLE `tbl_hoatdongmuavu` (
  `id_hoatdongmuavu` bigint(20) UNSIGNED NOT NULL,
  `id_lichmuavu` bigint(20) UNSIGNED NOT NULL,
  `name_hoatdong` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_hoatdong` text COLLATE utf8_unicode_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `status` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `attach` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_hoatdongmuavu`
--

INSERT INTO `tbl_hoatdongmuavu` (`id_hoatdongmuavu`, `id_lichmuavu`, `name_hoatdong`, `description_hoatdong`, `date_start`, `date_end`, `status`, `attach`, `created_at`, `updated_at`) VALUES
(26, 55, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 'upcoming', 'attached', '2022-10-22 22:16:30', '2022-10-22 22:16:44'),
(27, 55, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 'upcoming', 'attached', '2022-10-22 22:16:31', '2022-10-22 22:16:44'),
(28, 55, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 'upcoming', 'attached', '2022-10-22 22:16:32', '2022-10-22 22:16:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_hopdongmuaban`
--

CREATE TABLE `tbl_hopdongmuaban` (
  `id_hopdongmuaban` bigint(20) UNSIGNED NOT NULL,
  `id_thuonglai` bigint(20) UNSIGNED NOT NULL,
  `id_hoptacxa` bigint(20) UNSIGNED NOT NULL,
  `id_lichmuavu` bigint(20) UNSIGNED NOT NULL,
  `id_danhmucquydinh` bigint(20) UNSIGNED NOT NULL,
  `id_gionglua` bigint(20) UNSIGNED NOT NULL,
  `title_hopdongmuaban` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_hopdongmuaban` longtext COLLATE utf8_unicode_ci NOT NULL,
  `thuonglai_xacnhan` int(11) NOT NULL,
  `hoptacxa_xacnhan` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_hopdongmuaban`
--

INSERT INTO `tbl_hopdongmuaban` (`id_hopdongmuaban`, `id_thuonglai`, `id_hoptacxa`, `id_lichmuavu`, `id_danhmucquydinh`, `id_gionglua`, `title_hopdongmuaban`, `description_hopdongmuaban`, `thuonglai_xacnhan`, `hoptacxa_xacnhan`, `status`, `created_at`, `updated_at`) VALUES
(26, 16, 56, 57, 1, 1, 'Hợp Đồng Mua Bán Với Hợp Tác Xã Mỹ Lợi A', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, 0, 'waiting', '2022-11-04 19:39:55', '2022-11-04 19:39:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_hoptacxa`
--

CREATE TABLE `tbl_hoptacxa` (
  `id_hoptacxa` bigint(20) UNSIGNED NOT NULL,
  `name_hoptacxa` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_background` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_hoptacxa`
--

INSERT INTO `tbl_hoptacxa` (`id_hoptacxa`, `name_hoptacxa`, `phone_number`, `email`, `address`, `thumbnail`, `img_background`, `description`, `active`, `created_at`, `updated_at`) VALUES
(56, 'Ký Túc Xá A', '09393939393', 'ktxa@gmail.com', 'Mỹ lợi a, tiền giang', NULL, NULL, NULL, 1, '2022-10-02 06:43:06', '2022-10-23 07:16:01'),
(57, 'Ký Túc Xá B', '09393939397', 'ktxb@gmail.com', 'Ký Túc Xá B, Đại Học Cần Thơ', NULL, NULL, NULL, 0, '2022-10-02 06:44:17', '2022-10-02 06:44:17'),
(58, 'Anh Em MLA', '0967105247', 'aemla@gmail.com', 'Mỹ lợi a, tiền giang', NULL, NULL, NULL, 0, '2022-10-09 02:40:10', '2022-10-09 02:40:10'),
(59, 'Anh Em MLA2', '0980000009', 'tumlum2@gmail.com', 'Mỹ lợi a, tiền giang', NULL, NULL, NULL, 0, '2022-10-29 07:38:02', '2022-10-29 07:38:02'),
(60, 'Anh Em MLA3', '0980000000', 'tumlum3@gmail.com', 'Mỹ lợi a, tiền giang', NULL, NULL, NULL, 0, '2022-10-29 07:39:14', '2022-10-29 07:39:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_lichmuavu`
--

CREATE TABLE `tbl_lichmuavu` (
  `id_lichmuavu` bigint(20) UNSIGNED NOT NULL,
  `id_hoptacxa` bigint(20) UNSIGNED NOT NULL,
  `id_gionglua` bigint(20) UNSIGNED NOT NULL,
  `name_lichmuavu` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_lichmuavu`
--

INSERT INTO `tbl_lichmuavu` (`id_lichmuavu`, `id_hoptacxa`, `id_gionglua`, `name_lichmuavu`, `date_start`, `date_end`, `status`, `created_at`, `updated_at`) VALUES
(55, 56, 1, 'Vụ mùa Đông Xuân 2023', '2023-01-01', '2023-04-01', 'upcoming', '2022-10-10 02:11:59', '2022-10-23 07:23:09'),
(56, 56, 1, 'Vụ mùa Đông Xuân 2023 Lần 3', '2022-01-01', '2023-04-01', 'finish', '2022-10-10 02:12:03', '2022-10-10 02:12:03'),
(57, 56, 1, 'Vụ mùa Đông Xuân 2023 Lần 3', '2022-01-01', '2023-04-01', 'start', '2022-10-19 07:10:30', '2022-10-19 07:10:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_menu_client`
--

CREATE TABLE `tbl_menu_client` (
  `id_menu` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `href` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_menu_client`
--

INSERT INTO `tbl_menu_client` (`id_menu`, `title`, `href`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Tin Tức', 'tin-tuc', 0, '2022-09-11 14:15:44', '2022-09-11 14:15:44'),
(2, 'Tra Cứu Nông Nghiệp', 'tra-cuu-nong-nghiep', 0, '2022-09-11 14:15:44', '2022-09-11 14:15:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_nhacungcapvattu`
--

CREATE TABLE `tbl_nhacungcapvattu` (
  `id_nhacungcapvattu` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `name_daily` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_background` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_nhatkydongruong`
--

CREATE TABLE `tbl_nhatkydongruong` (
  `id_nhatkydongruong` bigint(20) UNSIGNED NOT NULL,
  `id_lichmuavu` bigint(20) UNSIGNED NOT NULL,
  `id_thuadat` bigint(20) UNSIGNED NOT NULL,
  `id_xavien` bigint(20) UNSIGNED NOT NULL,
  `id_hoatdongmuavu` bigint(20) UNSIGNED DEFAULT NULL,
  `name_hoatdong` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `status` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hoptacxa_xacnhan` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_nhatkydongruong`
--

INSERT INTO `tbl_nhatkydongruong` (`id_nhatkydongruong`, `id_lichmuavu`, `id_thuadat`, `id_xavien`, `id_hoatdongmuavu`, `name_hoatdong`, `description`, `date_start`, `date_end`, `status`, `type`, `hoptacxa_xacnhan`, `created_at`, `updated_at`) VALUES
(190, 55, 7, 35, 26, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 1, 'inside', NULL, '2022-10-22 22:16:44', '2022-10-26 07:28:40'),
(191, 55, 7, 35, 27, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 0, 'inside', NULL, '2022-10-22 22:16:44', '2022-10-22 22:16:44'),
(192, 55, 7, 35, 28, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 0, 'inside', NULL, '2022-10-22 22:16:44', '2022-10-22 22:16:44'),
(193, 55, 8, 36, 26, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 0, 'inside', NULL, '2022-10-22 22:16:44', '2022-10-22 22:16:44'),
(194, 55, 8, 36, 27, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 0, 'inside', NULL, '2022-10-22 22:16:44', '2022-10-22 22:16:44'),
(195, 55, 8, 36, 28, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 0, 'inside', NULL, '2022-10-22 22:16:44', '2022-10-22 22:16:44'),
(196, 55, 9, 37, 26, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 0, 'inside', NULL, '2022-10-22 22:16:44', '2022-10-22 22:16:44'),
(197, 55, 9, 37, 27, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 0, 'inside', NULL, '2022-10-22 22:16:44', '2022-10-22 22:16:44'),
(198, 55, 9, 37, 28, 'Meeting 5/10', 'Discurd about api', '2023-01-20', '2023-01-25', 0, 'inside', NULL, '2022-10-22 22:16:44', '2022-10-22 22:16:44'),
(199, 55, 7, 35, NULL, 'Bón phân RPK lần 2', 'Bón phân dậm lúa lần 2', '2023-01-15', '2023-01-16', 0, 'outside', NULL, '2022-10-22 22:17:33', '2022-10-22 22:17:33'),
(200, 55, 7, 36, NULL, 'Bón phân RPK lần 2', 'Bón phân dậm lúa lần 2', '2023-01-15', '2023-01-16', 0, 'outside', 0, '2022-10-30 06:32:43', '2022-10-30 06:32:43'),
(201, 55, 8, 36, NULL, 'Bón phân RPK lần 2', 'Bón phân dậm lúa lần 2', '2023-01-15', '2023-01-16', 0, 'outside', 0, '2022-10-30 06:35:20', '2022-10-30 06:35:20'),
(202, 55, 8, 36, NULL, 'Bón phân RPK lần 2', 'Bón phân dậm lúa lần 2', '2023-01-15', '2023-01-16', 0, 'outside', 0, '2022-10-30 06:42:16', '2022-10-30 06:42:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_notification`
--

CREATE TABLE `tbl_notification` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_notification`
--

INSERT INTO `tbl_notification` (`id`, `message`, `status`, `user`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Test message', 1, 57, 'api/link/message', '2022-10-30 04:00:16', '2022-10-30 06:21:18'),
(2, 'Bạn vừa được tạo một hợp đồng với thương lái NAME_THUONGLAI', 0, 57, '/hopdongmuaban', '2022-11-02 08:47:03', '2022-11-02 08:47:03'),
(3, 'Bạn vừa được tạo một hợp đồng với thương lái NAME_THUONGLAI', 0, 57, '/hopdongmuaban', '2022-11-02 08:47:36', '2022-11-02 08:47:36'),
(4, 'Bạn vừa được tạo một hợp đồng với thương lái NAME_THUONGLAI', 1, 57, '/hopdongmuaban', '2022-11-02 08:48:04', '2022-11-03 06:58:48'),
(5, 'Bạn vừa được tạo một hợp đồng với thương lái NAME_THUONGLAI', 0, 57, '/hopdongmuaban', '2022-11-03 06:56:29', '2022-11-03 06:56:29'),
(6, 'Bạn vừa được tạo một hợp đồng với thương lái{\"name_thuonglai\":\"Th\\u01b0\\u01a1ng L\\u00e1i A\"}', 0, 57, '/hopdongmuaban', '2022-11-04 19:17:02', '2022-11-04 19:17:02'),
(7, 'Bạn vừa được tạo một hợp đồng với thương lái: Thương Lái A', 0, 57, '/hopdongmuaban', '2022-11-04 19:18:31', '2022-11-04 19:18:31'),
(8, 'Hợp đồng số 24vừa được cập nhật bởi thương lái ', 0, 16, '/hopdongmuaban', '2022-11-04 19:23:15', '2022-11-04 19:23:15'),
(9, 'Hợp đồng số 24vừa được cập nhật bởi thương lái ', 0, 16, '/hopdongmuaban', '2022-11-04 19:23:26', '2022-11-04 19:23:26'),
(10, 'Hợp đồng số 24vừa được cập nhật bởi thương lái ', 0, 16, '/hopdongmuaban', '2022-11-04 19:24:21', '2022-11-04 19:24:21'),
(11, 'Hợp đồng số 24 vừa được cập nhật bởi thương lái ', 0, 57, '/hopdongmuaban', '2022-11-04 19:29:59', '2022-11-04 19:29:59'),
(12, 'Bạn vừa được tạo một hợp đồng với thương lái: Thương Lái A', 0, 57, '/hopdongmuaban', '2022-11-04 19:35:26', '2022-11-04 19:35:26'),
(13, 'Hợp đồng số 25 đã bị xóa bởi thương thương lái: Thương Lái A', 0, 57, '/hopdongmuaban', '2022-11-04 19:35:50', '2022-11-04 19:35:50'),
(14, 'Bạn vừa được tạo một hợp đồng với thương lái: Thương Lái A', 0, 57, '/hopdongmuaban', '2022-11-04 19:39:55', '2022-11-04 19:39:55'),
(15, 'Hợp đồng số 26 vừa được xác nhận bởi thương lái ', 0, 57, '/hopdongmuaban', '2022-11-04 19:50:23', '2022-11-04 19:50:23'),
(16, 'Hợp đồng số 26 vừa được xác nhận bởi thương lái ', 0, 57, '/hopdongmuaban', '2022-11-05 06:03:52', '2022-11-05 06:03:52'),
(17, 'Hợp đồng số 26 vừa được xác nhận bởi thương lái ', 0, 57, '/hopdongmuaban', '2022-11-05 06:23:26', '2022-11-05 06:23:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_rolexavien`
--

CREATE TABLE `tbl_rolexavien` (
  `id_role` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_rolexavien`
--

INSERT INTO `tbl_rolexavien` (`id_role`, `role`, `created_at`, `updated_at`) VALUES
(1, 'xavien', NULL, NULL),
(2, 'chunhiem', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_thuadat`
--

CREATE TABLE `tbl_thuadat` (
  `id_thuadat` bigint(20) UNSIGNED NOT NULL,
  `id_xavien` bigint(20) UNSIGNED NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_thuadat`
--

INSERT INTO `tbl_thuadat` (`id_thuadat`, `id_xavien`, `address`, `location`, `thumbnail`, `description`, `active`, `created_at`, `updated_at`) VALUES
(7, 35, 'Hưng lợi cần thơ', '1000\'E - 2000\'G', 'abc', 'description', 0, '2022-10-02 07:05:06', '2022-10-02 07:05:06'),
(8, 36, 'Hưng lợi cần thơ', '1000\'E - 2000\'G', 'abc', 'description', 0, '2022-10-02 07:05:20', '2022-10-02 07:05:20'),
(9, 37, 'Hưng lợi cần thơ', '1000\'E - 2000\'G', 'abc', 'description', 0, '2022-10-02 07:05:31', '2022-10-02 07:05:31'),
(10, 38, 'Hưng lợi cần thơ', '1000\'E - 2000\'G', 'abc', 'description', 0, '2022-10-02 07:05:44', '2022-10-02 07:05:44'),
(11, 39, 'Hưng lợi cần thơ', '1000\'E - 2000\'G', 'abc', 'description', 0, '2022-10-02 07:05:53', '2022-10-02 07:05:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_thuonglai`
--

CREATE TABLE `tbl_thuonglai` (
  `id_thuonglai` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `name_thuonglai` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_background` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_thuonglai`
--

INSERT INTO `tbl_thuonglai` (`id_thuonglai`, `id_user`, `name_thuonglai`, `thumbnail`, `img_background`, `description`, `active`, `created_at`, `updated_at`) VALUES
(16, 64, 'Thương Lái A', NULL, NULL, NULL, 1, '2022-10-08 21:25:43', '2022-10-08 21:25:43'),
(17, 65, 'Tùm lum', NULL, NULL, NULL, 1, '2022-10-09 07:09:05', '2022-10-09 07:09:05'),
(18, 67, 'Tùm lum 3', NULL, NULL, NULL, 1, '2022-10-29 07:38:33', '2022-10-29 07:38:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date NOT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `fullname`, `email`, `phone_number`, `password`, `remember_token`, `address`, `wallet`, `avatar`, `dob`, `active`, `created_at`, `updated_at`) VALUES
(57, 'Nguyễn Văn A', 'nguyenvana@gmail.com', '0980000001', '$2y$10$whyI/EejvXQf.jQHlbSZROhoPnbelIIlQpTj4jr5d7qbfhTO4xw9i', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', 'f0945e0c-d064-492d-afcc-3c9f69949b78', NULL, '2000-01-01', 1, '2022-10-02 06:41:25', '2022-10-02 06:41:25'),
(58, 'Lê Văn B', 'levanb@gmail.com', '0980000002', '$2y$10$hxPgaOX5px7p4qlL4Cwni.hzmAxnWgUleWzhZA.UA/nKaR7N4Us52', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', '62eaa7ad-0d4a-4d13-b693-37a6f0e22c34', NULL, '2000-01-01', 1, '2022-10-02 06:41:40', '2022-10-02 06:41:40'),
(59, 'Lê Văn C', 'levanc@gmail.com', '0980000003', '$2y$10$ZAE77gcFPK.lCKKLoW1lqO5Runy2nwge4Rr4YxfO348XV/Viv4CzG', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', '5adbbe05-d844-4b30-ae4e-a230982f8cce', NULL, '2000-01-01', 1, '2022-10-02 06:41:56', '2022-10-02 06:41:56'),
(60, 'Hồ Thị D', 'hothid@gmail.com', '0980000004', '$2y$10$ydA2KnjfZ3q8P3O2yZM4oOGcXkSmUvjCW7FxGi6zNnQYOclas4krG', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', '70b18abd-8019-4b85-9ff0-841533679916', NULL, '2000-01-01', 1, '2022-10-02 06:42:17', '2022-10-02 06:42:17'),
(61, 'Nguyễn Văn F', 'nguyenvanf@gmail.com', '0980000005', '$2y$10$KiWIsPZA/Smtl37J.rvX7.4EM7fm1LiIwv.M5tNFCWNS89lSYwsea', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', 'f5c5165f-06b1-4041-aab6-8676948cbb88', NULL, '2000-01-01', 1, '2022-10-02 06:42:44', '2022-10-02 06:42:44'),
(62, 'Nguyễn Văn G', 'nguyenvang@gmail.com', '0980000006', '$2y$10$MI6nLvBWggzHBjT4JfFIZuRaT6dPCAQOubU8NPYI1n5O/UaarukOa', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', '180cd3a0-7f94-4f56-a6eb-d041641a43b1', NULL, '2000-01-01', 1, '2022-10-02 20:13:59', '2022-10-02 20:13:59'),
(63, 'Nguyễn Quốc Hưng', 'nguyenquochung@gmail.com', '0967105247', '$2y$10$JE0alXadENori0.ImPnAhuMleNoROwYw4vvUQpLM5UGe3YKmFQgv2', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', '6b14a188-03f7-441a-96af-52fb85e97c6a', NULL, '2000-01-01', 1, '2022-10-08 19:30:04', '2022-10-08 19:30:04'),
(64, 'Thương Lái A', 'thuonglaia@gmail.com', '0980000010', '$2y$10$eyrcUUcAbYFDBN/3QSjUZ.mlm0yVtA8v08B7fez8oOq/vUzd3JTd6', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', 'd2ab22ab-ddf2-4732-b1c1-3507c2dd0f63', NULL, '2000-01-01', 1, '2022-10-08 21:25:43', '2022-10-08 21:25:43'),
(65, 'Tùm lum', 'tumlum@gmail.com', '0980000020', '$2y$10$xut6lzgydv0SG4N5/FAWm.NMpvgLnXeb2vOJMt5hVhyiePirocah6', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', 'ed1cf8be-33b8-46b0-ad7e-7bde90dd5447', NULL, '2000-01-01', 1, '2022-10-09 07:09:05', '2022-10-09 07:09:05'),
(66, 'Tùm lum 2', 'tumlum2@gmail.com', '0980000009', '$2y$10$zmkHgQolIVFwJhv.AIvVg.CFmdUlYgWm2jkDsABCQ8CrK7i.B2VgS', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', '876691db-02c6-45d8-b4e2-95a6f807384c', NULL, '2000-01-01', 1, '2022-10-28 08:35:21', '2022-10-28 08:35:21'),
(67, 'Tùm lum 3', 'tumlum3@gmail.com', '0980000000', '$2y$10$.lgg6wV3y4fwbiCc7aUbdeyOunlPC66FZS8ptsXYgftKQ7EiGZzbe', NULL, 'Hưng Lợi, Ninh Kiều, Cần Thơ', 'ccd0da42-15ad-4b0d-ba60-7fc93f4ca17f', NULL, '2000-01-01', 1, '2022-10-29 07:38:33', '2022-10-29 07:38:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_vattusudung`
--

CREATE TABLE `tbl_vattusudung` (
  `id_vattusudung` bigint(20) UNSIGNED NOT NULL,
  `id_nhatkydongruong` bigint(20) UNSIGNED NOT NULL,
  `id_giaodichmuaban_vattu` bigint(20) UNSIGNED NOT NULL,
  `soluong` int(11) NOT NULL,
  `timeuse` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_xavien`
--

CREATE TABLE `tbl_xavien` (
  `id_xavien` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_hoptacxa` bigint(20) UNSIGNED DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_background` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_xavien`
--

INSERT INTO `tbl_xavien` (`id_xavien`, `id_user`, `id_hoptacxa`, `thumbnail`, `img_background`, `description`, `active`, `created_at`, `updated_at`) VALUES
(35, 57, 56, NULL, NULL, NULL, 1, '2022-10-02 06:41:25', '2022-10-02 06:43:06'),
(36, 58, 56, NULL, NULL, NULL, 1, '2022-10-02 06:41:40', '2022-10-02 06:58:09'),
(37, 59, 56, NULL, NULL, NULL, 1, '2022-10-02 06:41:56', '2022-10-02 06:58:14'),
(38, 60, 56, NULL, NULL, NULL, 0, '2022-10-02 06:42:17', '2022-10-22 22:04:47'),
(39, 61, 57, NULL, NULL, NULL, 1, '2022-10-02 06:42:44', '2022-10-02 06:44:17'),
(40, 62, NULL, NULL, NULL, NULL, 1, '2022-10-02 20:13:59', '2022-10-02 20:15:03'),
(41, 63, 58, NULL, NULL, NULL, 1, '2022-10-08 19:30:04', '2022-10-09 02:40:10'),
(42, 65, NULL, NULL, NULL, NULL, 1, '2022-10-09 07:09:05', '2022-10-09 07:09:05'),
(43, 66, 59, NULL, NULL, NULL, 1, '2022-10-28 08:35:21', '2022-10-29 07:38:02'),
(44, 67, 60, NULL, NULL, NULL, 1, '2022-10-29 07:38:33', '2022-10-29 07:39:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_account`
--

CREATE TABLE `user_account` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id_user` bigint(20) UNSIGNED NOT NULL,
  `account_id_account` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_account`
--

INSERT INTO `user_account` (`id`, `user_id_user`, `account_id_account`) VALUES
(13, 57, 1),
(14, 58, 1),
(15, 59, 1),
(16, 60, 1),
(17, 61, 1),
(18, 62, 1),
(19, 63, 1),
(20, 64, 2),
(21, 65, 1),
(22, 65, 2),
(23, 66, 1),
(24, 67, 1),
(25, 67, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xavien_rolexavien`
--

CREATE TABLE `xavien_rolexavien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `xavien_id_xavien` bigint(20) UNSIGNED NOT NULL,
  `rolexavien_id_role` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `xavien_rolexavien`
--

INSERT INTO `xavien_rolexavien` (`id`, `xavien_id_xavien`, `rolexavien_id_role`, `created_at`, `updated_at`) VALUES
(12, 35, 2, NULL, NULL),
(13, 39, 2, NULL, NULL),
(14, 36, 1, NULL, NULL),
(15, 37, 1, NULL, NULL),
(16, 38, 1, NULL, NULL),
(18, 41, 2, NULL, NULL),
(19, 43, 2, NULL, NULL),
(20, 44, 2, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `tbl_account`
--
ALTER TABLE `tbl_account`
  ADD PRIMARY KEY (`id_account`);

--
-- Chỉ mục cho bảng `tbl_category_vattu`
--
ALTER TABLE `tbl_category_vattu`
  ADD PRIMARY KEY (`id_category_vattu`),
  ADD KEY `tbl_category_vattu_id_danhmucquydinh_index` (`id_danhmucquydinh`);

--
-- Chỉ mục cho bảng `tbl_danhgiacuoimua`
--
ALTER TABLE `tbl_danhgiacuoimua`
  ADD PRIMARY KEY (`id_danhgiacuoimua`),
  ADD KEY `tbl_danhgiacuoimua_id_lichmuavu_index` (`id_lichmuavu`),
  ADD KEY `tbl_danhgiacuoimua_id_xavien_index` (`id_xavien`),
  ADD KEY `tbl_danhgiacuoimua_id_thuadat_index` (`id_thuadat`);

--
-- Chỉ mục cho bảng `tbl_danhmucquydinh`
--
ALTER TABLE `tbl_danhmucquydinh`
  ADD PRIMARY KEY (`id_danhmucquydinh`),
  ADD KEY `tbl_danhmucquydinh_id_thuonglai_index` (`id_thuonglai`);

--
-- Chỉ mục cho bảng `tbl_giaodichmuaban_lua`
--
ALTER TABLE `tbl_giaodichmuaban_lua`
  ADD PRIMARY KEY (`id_giaodichmuaban_lua`),
  ADD KEY `tbl_giaodichmuaban_lua_id_xavien_index` (`id_xavien`),
  ADD KEY `tbl_giaodichmuaban_lua_id_thuonglai_index` (`id_thuonglai`),
  ADD KEY `tbl_giaodichmuaban_lua_id_lichmuavu_index` (`id_lichmuavu`);

--
-- Chỉ mục cho bảng `tbl_giaodichmuaban_vattu`
--
ALTER TABLE `tbl_giaodichmuaban_vattu`
  ADD PRIMARY KEY (`id_giaodichmuaban_vattu`),
  ADD KEY `tbl_giaodichmuaban_vattu_id_xavien_index` (`id_xavien`),
  ADD KEY `tbl_giaodichmuaban_vattu_id_nhacungcapvattu_index` (`id_nhacungcapvattu`),
  ADD KEY `tbl_giaodichmuaban_vattu_id_lichmuavu_index` (`id_lichmuavu`),
  ADD KEY `tbl_giaodichmuaban_vattu_id_category_vattu_index` (`id_category_vattu`);

--
-- Chỉ mục cho bảng `tbl_giaodich_luagiong`
--
ALTER TABLE `tbl_giaodich_luagiong`
  ADD PRIMARY KEY (`id_giaodich_luagiong`),
  ADD KEY `tbl_giaodich_luagiong_id_xavien_index` (`id_xavien`),
  ADD KEY `tbl_giaodich_luagiong_id_nhacungcapvattu_index` (`id_nhacungcapvattu`),
  ADD KEY `tbl_giaodich_luagiong_id_lichmuavu_index` (`id_lichmuavu`),
  ADD KEY `tbl_giaodich_luagiong_id_gionglua_index` (`id_gionglua`);

--
-- Chỉ mục cho bảng `tbl_gionglua`
--
ALTER TABLE `tbl_gionglua`
  ADD PRIMARY KEY (`id_gionglua`);

--
-- Chỉ mục cho bảng `tbl_hoatdongmuavu`
--
ALTER TABLE `tbl_hoatdongmuavu`
  ADD PRIMARY KEY (`id_hoatdongmuavu`),
  ADD KEY `tbl_hoatdongmuavu_id_lichmuavu_index` (`id_lichmuavu`);

--
-- Chỉ mục cho bảng `tbl_hopdongmuaban`
--
ALTER TABLE `tbl_hopdongmuaban`
  ADD PRIMARY KEY (`id_hopdongmuaban`),
  ADD KEY `tbl_hopdongmuaban_id_thuonglai_index` (`id_thuonglai`),
  ADD KEY `tbl_hopdongmuaban_id_hoptacxa_index` (`id_hoptacxa`),
  ADD KEY `tbl_hopdongmuaban_id_lichmuavu_index` (`id_lichmuavu`),
  ADD KEY `tbl_hopdongmuaban_id_danhmucquydinh_index` (`id_danhmucquydinh`),
  ADD KEY `tbl_hopdongmuaban_id_gionglua_index` (`id_gionglua`);

--
-- Chỉ mục cho bảng `tbl_hoptacxa`
--
ALTER TABLE `tbl_hoptacxa`
  ADD PRIMARY KEY (`id_hoptacxa`);

--
-- Chỉ mục cho bảng `tbl_lichmuavu`
--
ALTER TABLE `tbl_lichmuavu`
  ADD PRIMARY KEY (`id_lichmuavu`),
  ADD KEY `tbl_lichmuavu_id_hoptacxa_index` (`id_hoptacxa`),
  ADD KEY `tbl_lichmuavu_id_gionglua_index` (`id_gionglua`);

--
-- Chỉ mục cho bảng `tbl_menu_client`
--
ALTER TABLE `tbl_menu_client`
  ADD PRIMARY KEY (`id_menu`);

--
-- Chỉ mục cho bảng `tbl_nhacungcapvattu`
--
ALTER TABLE `tbl_nhacungcapvattu`
  ADD PRIMARY KEY (`id_nhacungcapvattu`),
  ADD KEY `tbl_nhacungcapvattu_id_user_index` (`id_user`);

--
-- Chỉ mục cho bảng `tbl_nhatkydongruong`
--
ALTER TABLE `tbl_nhatkydongruong`
  ADD PRIMARY KEY (`id_nhatkydongruong`),
  ADD KEY `tbl_nhatkydongruong_id_lichmuavu_index` (`id_lichmuavu`),
  ADD KEY `tbl_nhatkydongruong_id_thuadat_index` (`id_thuadat`),
  ADD KEY `tbl_nhatkydongruong_id_xavien_index` (`id_xavien`),
  ADD KEY `tbl_nhatkydongruong_id_hoatdongmuavu_index` (`id_hoatdongmuavu`);

--
-- Chỉ mục cho bảng `tbl_notification`
--
ALTER TABLE `tbl_notification`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_rolexavien`
--
ALTER TABLE `tbl_rolexavien`
  ADD PRIMARY KEY (`id_role`);

--
-- Chỉ mục cho bảng `tbl_thuadat`
--
ALTER TABLE `tbl_thuadat`
  ADD PRIMARY KEY (`id_thuadat`),
  ADD KEY `tbl_thuadat_id_xavien_index` (`id_xavien`);

--
-- Chỉ mục cho bảng `tbl_thuonglai`
--
ALTER TABLE `tbl_thuonglai`
  ADD PRIMARY KEY (`id_thuonglai`),
  ADD KEY `tbl_thuonglai_id_user_index` (`id_user`);

--
-- Chỉ mục cho bảng `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Chỉ mục cho bảng `tbl_vattusudung`
--
ALTER TABLE `tbl_vattusudung`
  ADD PRIMARY KEY (`id_vattusudung`),
  ADD KEY `tbl_vattusudung_id_nhatkydongruong_index` (`id_nhatkydongruong`),
  ADD KEY `tbl_vattusudung_id_giaodichmuaban_vattu_index` (`id_giaodichmuaban_vattu`);

--
-- Chỉ mục cho bảng `tbl_xavien`
--
ALTER TABLE `tbl_xavien`
  ADD PRIMARY KEY (`id_xavien`),
  ADD KEY `tbl_xavien_id_user_index` (`id_user`),
  ADD KEY `tbl_xavien_id_hoptacxa_index` (`id_hoptacxa`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_account_user_id_user_index` (`user_id_user`),
  ADD KEY `user_account_account_id_account_index` (`account_id_account`);

--
-- Chỉ mục cho bảng `xavien_rolexavien`
--
ALTER TABLE `xavien_rolexavien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `xavien_rolexavien_xavien_id_xavien_index` (`xavien_id_xavien`),
  ADD KEY `xavien_rolexavien_rolexavien_id_role_index` (`rolexavien_id_role`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_account`
--
ALTER TABLE `tbl_account`
  MODIFY `id_account` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_category_vattu`
--
ALTER TABLE `tbl_category_vattu`
  MODIFY `id_category_vattu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `tbl_danhgiacuoimua`
--
ALTER TABLE `tbl_danhgiacuoimua`
  MODIFY `id_danhgiacuoimua` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_danhmucquydinh`
--
ALTER TABLE `tbl_danhmucquydinh`
  MODIFY `id_danhmucquydinh` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `tbl_giaodichmuaban_lua`
--
ALTER TABLE `tbl_giaodichmuaban_lua`
  MODIFY `id_giaodichmuaban_lua` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_giaodichmuaban_vattu`
--
ALTER TABLE `tbl_giaodichmuaban_vattu`
  MODIFY `id_giaodichmuaban_vattu` bigint(20) UNSIGNED NOT NULL  ;

--
-- AUTO_INCREMENT cho bảng `tbl_giaodich_luagiong`
--
ALTER TABLE `tbl_giaodich_luagiong`
  MODIFY `id_giaodich_luagiong` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_gionglua`
--
ALTER TABLE `tbl_gionglua`
  MODIFY `id_gionglua` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tbl_hoatdongmuavu`
--
ALTER TABLE `tbl_hoatdongmuavu`
  MODIFY `id_hoatdongmuavu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `tbl_hopdongmuaban`
--
ALTER TABLE `tbl_hopdongmuaban`
  MODIFY `id_hopdongmuaban` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `tbl_hoptacxa`
--
ALTER TABLE `tbl_hoptacxa`
  MODIFY `id_hoptacxa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `tbl_lichmuavu`
--
ALTER TABLE `tbl_lichmuavu`
  MODIFY `id_lichmuavu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT cho bảng `tbl_menu_client`
--
ALTER TABLE `tbl_menu_client`
  MODIFY `id_menu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tbl_nhacungcapvattu`
--
ALTER TABLE `tbl_nhacungcapvattu`
  MODIFY `id_nhacungcapvattu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `tbl_nhatkydongruong`
--
ALTER TABLE `tbl_nhatkydongruong`
  MODIFY `id_nhatkydongruong` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT cho bảng `tbl_notification`
--
ALTER TABLE `tbl_notification`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `tbl_rolexavien`
--
ALTER TABLE `tbl_rolexavien`
  MODIFY `id_role` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tbl_thuadat`
--
ALTER TABLE `tbl_thuadat`
  MODIFY `id_thuadat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tbl_thuonglai`
--
ALTER TABLE `tbl_thuonglai`
  MODIFY `id_thuonglai` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT cho bảng `tbl_vattusudung`
--
ALTER TABLE `tbl_vattusudung`
  MODIFY `id_vattusudung` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_xavien`
--
ALTER TABLE `tbl_xavien`
  MODIFY `id_xavien` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `user_account`
--
ALTER TABLE `user_account`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `xavien_rolexavien`
--
ALTER TABLE `xavien_rolexavien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_category_vattu`
--
ALTER TABLE `tbl_category_vattu`
  ADD CONSTRAINT `tbl_category_vattu_id_danhmucquydinh_foreign` FOREIGN KEY (`id_danhmucquydinh`) REFERENCES `tbl_danhmucquydinh` (`id_danhmucquydinh`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_danhgiacuoimua`
--
ALTER TABLE `tbl_danhgiacuoimua`
  ADD CONSTRAINT `tbl_danhgiacuoimua_id_lichmuavu_foreign` FOREIGN KEY (`id_lichmuavu`) REFERENCES `tbl_lichmuavu` (`id_lichmuavu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_danhgiacuoimua_id_thuadat_foreign` FOREIGN KEY (`id_thuadat`) REFERENCES `tbl_thuadat` (`id_thuadat`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_danhgiacuoimua_id_xavien_foreign` FOREIGN KEY (`id_xavien`) REFERENCES `tbl_xavien` (`id_xavien`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_danhmucquydinh`
--
ALTER TABLE `tbl_danhmucquydinh`
  ADD CONSTRAINT `tbl_danhmucquydinh_id_thuonglai_foreign` FOREIGN KEY (`id_thuonglai`) REFERENCES `tbl_thuonglai` (`id_thuonglai`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_giaodichmuaban_lua`
--
ALTER TABLE `tbl_giaodichmuaban_lua`
  ADD CONSTRAINT `tbl_giaodichmuaban_lua_id_lichmuavu_foreign` FOREIGN KEY (`id_lichmuavu`) REFERENCES `tbl_lichmuavu` (`id_lichmuavu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_giaodichmuaban_lua_id_thuonglai_foreign` FOREIGN KEY (`id_thuonglai`) REFERENCES `tbl_thuonglai` (`id_thuonglai`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_giaodichmuaban_lua_id_xavien_foreign` FOREIGN KEY (`id_xavien`) REFERENCES `tbl_xavien` (`id_xavien`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_giaodichmuaban_vattu`
--
ALTER TABLE `tbl_giaodichmuaban_vattu`
  ADD CONSTRAINT `tbl_giaodichmuaban_vattu_id_category_vattu_foreign` FOREIGN KEY (`id_category_vattu`) REFERENCES `tbl_category_vattu` (`id_category_vattu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_giaodichmuaban_vattu_id_lichmuavu_foreign` FOREIGN KEY (`id_lichmuavu`) REFERENCES `tbl_lichmuavu` (`id_lichmuavu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_giaodichmuaban_vattu_id_nhacungcapvattu_foreign` FOREIGN KEY (`id_nhacungcapvattu`) REFERENCES `tbl_nhacungcapvattu` (`id_nhacungcapvattu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_giaodichmuaban_vattu_id_xavien_foreign` FOREIGN KEY (`id_xavien`) REFERENCES `tbl_xavien` (`id_xavien`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_giaodich_luagiong`
--
ALTER TABLE `tbl_giaodich_luagiong`
  ADD CONSTRAINT `tbl_giaodich_luagiong_id_gionglua_foreign` FOREIGN KEY (`id_gionglua`) REFERENCES `tbl_gionglua` (`id_gionglua`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_giaodich_luagiong_id_lichmuavu_foreign` FOREIGN KEY (`id_lichmuavu`) REFERENCES `tbl_lichmuavu` (`id_lichmuavu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_giaodich_luagiong_id_nhacungcapvattu_foreign` FOREIGN KEY (`id_nhacungcapvattu`) REFERENCES `tbl_nhacungcapvattu` (`id_nhacungcapvattu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_giaodich_luagiong_id_xavien_foreign` FOREIGN KEY (`id_xavien`) REFERENCES `tbl_xavien` (`id_xavien`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_hoatdongmuavu`
--
ALTER TABLE `tbl_hoatdongmuavu`
  ADD CONSTRAINT `tbl_hoatdongmuavu_id_lichmuavu_foreign` FOREIGN KEY (`id_lichmuavu`) REFERENCES `tbl_lichmuavu` (`id_lichmuavu`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_hopdongmuaban`
--
ALTER TABLE `tbl_hopdongmuaban`
  ADD CONSTRAINT `tbl_hopdongmuaban_id_danhmucquydinh_foreign` FOREIGN KEY (`id_danhmucquydinh`) REFERENCES `tbl_danhmucquydinh` (`id_danhmucquydinh`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_hopdongmuaban_id_gionglua_foreign` FOREIGN KEY (`id_gionglua`) REFERENCES `tbl_gionglua` (`id_gionglua`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_hopdongmuaban_id_hoptacxa_foreign` FOREIGN KEY (`id_hoptacxa`) REFERENCES `tbl_hoptacxa` (`id_hoptacxa`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_hopdongmuaban_id_lichmuavu_foreign` FOREIGN KEY (`id_lichmuavu`) REFERENCES `tbl_lichmuavu` (`id_lichmuavu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_hopdongmuaban_id_thuonglai_foreign` FOREIGN KEY (`id_thuonglai`) REFERENCES `tbl_thuonglai` (`id_thuonglai`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_lichmuavu`
--
ALTER TABLE `tbl_lichmuavu`
  ADD CONSTRAINT `tbl_lichmuavu_id_gionglua_foreign` FOREIGN KEY (`id_gionglua`) REFERENCES `tbl_gionglua` (`id_gionglua`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_lichmuavu_id_hoptacxa_foreign` FOREIGN KEY (`id_hoptacxa`) REFERENCES `tbl_hoptacxa` (`id_hoptacxa`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_nhacungcapvattu`
--
ALTER TABLE `tbl_nhacungcapvattu`
  ADD CONSTRAINT `tbl_nhacungcapvattu_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_nhatkydongruong`
--
ALTER TABLE `tbl_nhatkydongruong`
  ADD CONSTRAINT `tbl_nhatkydongruong_id_hoatdongmuavu_foreign` FOREIGN KEY (`id_hoatdongmuavu`) REFERENCES `tbl_hoatdongmuavu` (`id_hoatdongmuavu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_nhatkydongruong_id_lichmuavu_foreign` FOREIGN KEY (`id_lichmuavu`) REFERENCES `tbl_lichmuavu` (`id_lichmuavu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_nhatkydongruong_id_thuadat_foreign` FOREIGN KEY (`id_thuadat`) REFERENCES `tbl_thuadat` (`id_thuadat`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_nhatkydongruong_id_xavien_foreign` FOREIGN KEY (`id_xavien`) REFERENCES `tbl_xavien` (`id_xavien`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_thuadat`
--
ALTER TABLE `tbl_thuadat`
  ADD CONSTRAINT `tbl_thuadat_id_xavien_foreign` FOREIGN KEY (`id_xavien`) REFERENCES `tbl_xavien` (`id_xavien`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_thuonglai`
--
ALTER TABLE `tbl_thuonglai`
  ADD CONSTRAINT `tbl_thuonglai_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_vattusudung`
--
ALTER TABLE `tbl_vattusudung`
  ADD CONSTRAINT `tbl_vattusudung_id_giaodichmuaban_vattu_foreign` FOREIGN KEY (`id_giaodichmuaban_vattu`) REFERENCES `tbl_giaodichmuaban_vattu` (`id_giaodichmuaban_vattu`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_vattusudung_id_nhatkydongruong_foreign` FOREIGN KEY (`id_nhatkydongruong`) REFERENCES `tbl_nhatkydongruong` (`id_nhatkydongruong`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_xavien`
--
ALTER TABLE `tbl_xavien`
  ADD CONSTRAINT `tbl_xavien_id_hoptacxa_foreign` FOREIGN KEY (`id_hoptacxa`) REFERENCES `tbl_hoptacxa` (`id_hoptacxa`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_xavien_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_account`
--
ALTER TABLE `user_account`
  ADD CONSTRAINT `user_account_account_id_account_foreign` FOREIGN KEY (`account_id_account`) REFERENCES `tbl_account` (`id_account`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_account_user_id_user_foreign` FOREIGN KEY (`user_id_user`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `xavien_rolexavien`
--
ALTER TABLE `xavien_rolexavien`
  ADD CONSTRAINT `xavien_rolexavien_rolexavien_id_role_foreign` FOREIGN KEY (`rolexavien_id_role`) REFERENCES `tbl_rolexavien` (`id_role`) ON DELETE CASCADE,
  ADD CONSTRAINT `xavien_rolexavien_xavien_id_xavien_foreign` FOREIGN KEY (`xavien_id_xavien`) REFERENCES `tbl_xavien` (`id_xavien`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
