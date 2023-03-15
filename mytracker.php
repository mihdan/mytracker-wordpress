<?php
/**
 * Plugin Name: MyTracker
 * Description: Мультиплатформенная система аналитики и атрибуции для мобильных приложений и сайтов.
 * Version: 1.0.0
 * Author: VK Team
 * Author URI: https://vk.team
 * Plugin URI: https://wordpress.org/plugins/mytracker/
 * GitHub Plugin URI: https://github.com/mihdan/mytracker
 * Requires PHP: 7.4
 * Requires at least: 5.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package mytracker
 * @link https://top.mail.ru/help/ru/code/receive
 * @link https://tracker.my.com/docs/sdk/web/api
 */

namespace VK\MyTracker;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VK_MYTRACKER_VERSION', '1.0.0' );
define( 'VK_MYTRACKER_NAME', 'MyTracker' );
define( 'VK_MYTRACKER_PREFIX', 'mytracker' );
define( 'VK_MYTRACKER_SLUG', 'mytracker' );
define( 'VK_MYTRACKER_FILE', __FILE__ );
define( 'VK_MYTRACKER_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'VK_MYTRACKER_DIR_PATH', plugin_dir_path( __FILE__ ) );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

( new Main() )->init();
