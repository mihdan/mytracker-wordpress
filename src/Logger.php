<?php
/**
 * Class Logger.
 *
 * @package mytracker
 */

namespace VK\MyTracker;

/**
 * Логирование данных в текстовый файл.
 */
class Logger {
	/**
	 * Путь к файлу логов.
	 *
	 * @var string $path
	 */
	private string $path;

	/**
	 * Конструктор класса.
	 */
	public function __construct() {
		$this->path = wp_get_upload_dir()['basedir'] . '/mytracker.log';
	}

	/**
	 * Функция получения пути до файла логов.
	 *
	 * @return string
	 */
	public function get_path(): string {
		return $this->path;
	}

	/**
	 * Логирование данных в файл.
	 *
	 * @param mixed $data Данные для логирования.
	 *
	 * @return void
	 */
	public function log( $data ) {
		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$content  = $wp_filesystem->get_contents( $this->get_path() );
		$content .= current_datetime()->format( 'd.m.Y H:i:s' );
		$content .= PHP_EOL;
		$content .= print_r( $data, true ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		$content .= PHP_EOL;

		$wp_filesystem->put_contents( $this->get_path(), $content );
	}

	/**
	 * Инициализация хуков.
	 *
	 * @return void
	 */
	public function setup_hooks(): void {}
}
