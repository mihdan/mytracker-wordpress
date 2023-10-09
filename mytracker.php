<?php
/**
 * Plugin Name: VK Analytics
 * Description: VK Analytics is multi-platform analytics and attribution for mobile apps and websites.
 * Version: 1.1.0
 * Author: VK Team
 * Author URI: https://vk.team
 * Plugin URI: https://wordpress.org/plugins/mytracker/
 * GitHub Plugin URI: https://github.com/mihdan/vkanalytics-wordpress
 * Requires PHP: 7.4
 * Requires at least: 5.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package vkanalytics
 * @link https://top.mail.ru/help/ru/code/receive
 * @link https://tracker.my.com/docs/sdk/web/api
 * @link https://top.mail.ru/help/ru/api/jsapi
 * @link https://top.mail.ru/help/ru/code/amp
 * @link https://amp-wp.org/ecosystem/
 * @link https://amp-wp.org/documentation/getting-started/analytics/
 */

namespace VK\Analytics;

use Auryn\Injector;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VK_ANALYTICS_VERSION', '1.1.0' );
define( 'VK_ANALYTICS_NAME', 'VK Analytics' );
define( 'VK_ANALYTICS_PREFIX', 'vkanalytics' );
define( 'VK_ANALYTICS_SLUG', 'vkanalytics' );
define( 'VK_ANALYTICS_FILE', __FILE__ );
define( 'VK_ANALYTICS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'VK_ANALYTICS_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'VK_ANALYTICS_DIR_BASENAME', plugin_basename( __FILE__ ) );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

( new Main( new Injector() ) )->init();
