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
	 * URL к файлу логов.
	 *
	 * @var string $path
	 */
	private string $url;

	/**
	 * Конструктор класса.
	 */
	public function __construct() {
		$this->path = wp_get_upload_dir()['basedir'] . '/mytracker.log';
		$this->url  = wp_get_upload_dir()['baseurl'] . '/mytracker.log';
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
	 * Функция получения URL до файла логов.
	 *
	 * @return string
	 */
	public function get_url(): string {
		return $this->url;
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
	public function setup_hooks(): void {
		add_action( 'wp_ajax_mytracker_api_remove_log', [ $this, 'ajax_remove_log' ] );
	}

	/**
	 * Проверяет существование файла с логами.
	 *
	 * @return bool
	 */
	public function log_exists(): bool {
		return file_exists( $this->get_path() );
	}

	/**
	 * Удаляет файл с логами.
	 *
	 * @return bool
	 */
	public function remove_log(): bool {
		if ( $this->log_exists() ) {
			return unlink( $this->get_path() );
		} else {
			return false;
		}
	}

	/**
	 * Удаляет лог файл по ajax-запросу.
	 *
	 * @return void
	 */
	public function ajax_remove_log() {
		$nonce = ! empty( $_POST['nonce'] ) ? wp_unslash( $_POST['nonce'] ) : '';// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( ! $this->log_exists() ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'The log file does not exist', 'mytracker' ),
				]
			);
		}

		if ( ! wp_verify_nonce( $nonce ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'Invalid nonce key', 'mytracker' ),
				]
			);
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				[
					'success' => false,
					'message' => __( 'User has no rights to delete a file', 'mytracker' ),
				]
			);
		}

		// Удаляет файл логов.
		$this->remove_log();

		wp_send_json_success(
			[
				'success' => true,
				'message' => __( 'Log file deleted successfully', 'mytracker' ),
			]
		);
	}
}
