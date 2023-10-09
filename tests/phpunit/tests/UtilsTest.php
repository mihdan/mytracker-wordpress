<?php
/**
 * Class Test_Utils.
 *
 * @package vkanalytics
 */

namespace VK\Analytics;

use PHPUnit\Framework\TestCase;
use tad\FunctionMocker\FunctionMocker;

/**
 * Класс для тестирования утилит.
 */
class UtilsTest extends TestCase {
	/**
	 * Test get_plugin_name().
	 */
	public function test_get_plugin_name() {
		$plugin_name = 'MyTracker';
		$constant    = FunctionMocker::replace( 'constant', $plugin_name );

		self::assertSame( $plugin_name, Utils::get_plugin_name() );
		$constant->wasCalledWithOnce( [ 'VK_ANALYTICS_NAME' ] );
	}

	/**
	 * Test get_plugin_version().
	 */
	public function test_get_plugin_version() {
		$version  = '1.0.0';
		$constant = FunctionMocker::replace( 'constant', $version );

		self::assertSame( $version, Utils::get_plugin_version() );
		$constant->wasCalledWithOnce( [ 'VK_ANALYTICS_VERSION' ] );
	}
}
