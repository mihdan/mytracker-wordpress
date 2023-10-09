<?php
/**
 * Bootstrap file for tests
 *
 * @package   wppunk/wpautoload
 * @author    WPPunk
 * @link      https://github.com/wppunk/wpautoload/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 */

use tad\FunctionMocker\FunctionMocker;

/**
 * Plugin test dir.
 */
define( 'PLUGIN_TESTS_DIR', __DIR__ );

/**
 * Plugin main file.
 */
define( 'PLUGIN_MAIN_FILE', realpath( __DIR__ . '/../../mytracker.php' ) );

/**
 * Plugin path.
 */
define( 'PLUGIN_PATH', realpath( dirname( PLUGIN_MAIN_FILE ) ) );

require_once PLUGIN_PATH . '/vendor/autoload.php';

if ( ! defined( 'ABSPATH' ) ) {
	/**
	 * WordPress ABSPATH.
	 */
	define( 'ABSPATH', PLUGIN_PATH . '/../../../' );
}

define( 'VK_ANALYTICS_TEST_VERSION', '7.7.7' );
define( 'VK_ANALYTICS_TEST_NAME', 'Mytracker' );

FunctionMocker::init(
	[
		'blacklist'             => [
			realpath( PLUGIN_PATH ),
		],
		'whitelist'             => [
			realpath( PLUGIN_PATH . '/mytracker.php' ),
			realpath( PLUGIN_PATH . '/src' ),
		],
		'redefinable-internals' => [
			'define',
			'defined',
			'constant',
			'function_exists',
			'ini_get',
			'mb_strtolower',
			'phpversion',
			'realpath',
			'time',
			'error_log',
			'rename',
		],
	]
);

WP_Mock::bootstrap();
