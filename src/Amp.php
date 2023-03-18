<?php
/**
 * Поддержка АМР.
 *
 * @package mytracker
 */

namespace VK\MyTracker;

/**
 * Класс Amp
 */
class Amp {
	/**
	 * Settings instance.
	 *
	 * @var WPOSA $wposa
	 */
	private WPOSA $wposa;

	/**
	 * Конструктор.
	 *
	 * @param WPOSA $wposa экземпляр класса WPOSA.
	 */
	public function __construct( WPOSA $wposa ) {
		$this->wposa = $wposa;
	}

	/**
	 * Инициализация хуков.
	 *
	 * @return void
	 */
	public function setup_hooks(): void {
		// Ничего не делаем, если нет активных АМР-плагинов.
		if ( ! $this->is_amp_plugin_active() ) {
			return;
		}

		// amp_is_request for AMP
		// ampforwp_is_amp_endpoint

		add_filter('amp_post_template_data',function($data) {
			//var_dump($data);die;

			if ( empty( $data['amp_component_scripts']['amp-analytics'] ) ) {
				$data['amp_component_scripts']['amp-analytics'] = 'https://cdn.ampproject.org/v0/amp-analytics-latest.js';
			}

			return $data;
		});

		add_action( 'amp_post_template_footer', function (){
			$counter_id = $this->wposa->get_option( 'counter_id', 'general', 0 );
			?>
			<amp-analytics type="topmailru" id="topmailru">
				<script type="application/json">
					{
						"vars": {
							"id": "<?php echo esc_attr( $counter_id ); ?>"
						}
					}
				</script>
			</amp-analytics>
			<?php
		});

		add_action( 'amp_meta_', function () {
			echo 11111;
		} );
	}

	/**
	 * Проверяет активность хотябы одного AMP плагина на сайте.
	 *
	 * @return bool
	 */
	public function is_amp_plugin_active(): bool {
		// Ничего не делаем, если фича не активна.
		if ( $this->wposa->get_option( 'tracking_amp', 'general', 'no' ) === 'no' ) {
			return false;
		}

		if ( has_action( 'amp_init' ) ) {
			//return true;
		}

		// Официальный плагин AMP.
		if ( function_exists( 'amp_is_enabled' ) ) {
			return true;
		}

		// Плагин AMP for WP.
		if ( function_exists( 'ampforwp_init' ) ) {
			return true;
		}

		return false;
	}
}
