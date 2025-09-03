<?php
// Configuration file for ATMABISWAS website
// This ensures paths work correctly across different hosting environments

// Get the base directory of the website
$base_dir = dirname(__FILE__);

// Define base URL - automatically detects the correct URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$script_dir = dirname($_SERVER['SCRIPT_NAME']);

// Remove trailing slash if present
$script_dir = rtrim($script_dir, '/');

// Define the base URL
define('BASE_URL', $protocol . $host . $script_dir);

// Define common paths
define('LOGIN_PATH', BASE_URL . '/backend/login/prelogin.php');
define('DASHBOARD_PATH', BASE_URL . '/backend/DashBoard/dashboard.php');
define('UPDATE_BLOG_IMAGE_PATH', BASE_URL . '/backend/DashBoard/update_Blog_Image.php');
define('HOME_PATH', BASE_URL . '/index.php');

// Define main page paths
define('NOTICE_PATH', BASE_URL . '/notice.php');
define('CAREER_PATH', BASE_URL . '/career.php');
define('PRESS_PATH', BASE_URL . '/press.php');
define('ABOUTUS_PATH', BASE_URL . '/aboutus.php');
define('CONTACT_PATH', BASE_URL . '/contact.php');
define('EVENTS_PATH', BASE_URL . '/Events.php');
define('SOCIAL_PATH', BASE_URL . '/social.php');

// Define team pages
define('EVE_PATH', BASE_URL . '/eve.php');
define('GENERALBODY_PATH', BASE_URL . '/generalbody.php');
define('SENIOR_MANAGEMENT_PATH', BASE_URL . '/SeniorManagement.php');
define('FOUNDER_PATH', BASE_URL . '/founder.php');

// Define service pages
define('GREEN_ENERGY_PATH', BASE_URL . '/Green_Energy.php');
define('ENTERPRISE_PATH', BASE_URL . '/enterprice.php');
define('AGRICULTURAL_PATH', BASE_URL . '/Agritural.php');
define('READYTOEAT_PATH', BASE_URL . '/readytoeat.php');
define('HEALTH_PATH', BASE_URL . '/health.php');

// For file includes (server paths)
define('BASE_DIR', $base_dir);
define('BACKEND_DIR', BASE_DIR . '/backend');
define('DATABASE_DIR', BACKEND_DIR . '/Database');
