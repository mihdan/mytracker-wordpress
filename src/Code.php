<?php
/**
 * Выводит код трекера во фронтенде.
 *
 * @package mytracker
 */

namespace VK\MyTracker;

/**
 * Class Code.
 */
class Code {
	private const DOMAINS = [
		'ru'  => 'top-fwz1.mail.ru',
		'com' => 'mytopf.com',
	];

	private const ANALYTICS_ID = 'topmailru';
	/**
	 * Settings instance.
	 *
	 * @var WPOSA $wposa
	 */
	private WPOSA $wposa;

	/**
	 * Идентификатор счётчика.
	 *
	 * @var string $counter_id
	 */
	private int $counter_id;

	/**
	 * Домен счётчика.
	 *
	 * @var string $domain
	 */
	private string $domain;

	/**
	 * Идентификатор пользователя.
	 *
	 * @var int $user_id
	 */
	private int $user_id;

	/**
	 * Конструктор.
	 *
	 * @param WPOSA $wposa экземпляр класса WPOSA.
	 */
	public function __construct( WPOSA $wposa ) {
		$this->wposa      = $wposa;
		$this->counter_id = (int) $this->wposa->get_option( 'counter_id', 'general', 0 );
		$this->domain     = $this->wposa->get_option( 'domain', 'general', 'ru' );
	}

	/**
	 * Получает идентификатор счётчика.
	 *
	 * @return int
	 */
	private function get_counter_id(): int {
		return $this->counter_id;
	}

	/**
	 * Получает идентификатор пользователя.
	 *
	 * @return int
	 */
	private function get_user_id(): int {
		return $this->user_id;
	}

	/**
	 * Получает идентификатор счётчика.
	 *
	 * @return string
	 */
	private function get_domain(): string {
		return self::DOMAINS[ $this->domain ];
	}

	/**
	 * Устанавливает идентификатор текущего пользователя.
	 *
	 * @return void
	 */
	public function set_user_id(): void {
		$this->user_id = get_current_user_id();
	}

	/**
	 * Инициализация хуков.
	 *
	 * @return void
	 */
	public function setup_hooks(): void {
		add_action( 'plugins_loaded', [ $this, 'set_user_id' ] );
		add_action(
			'init',
			function () {
				$url_path = trim( wp_parse_url( add_query_arg( [] ), PHP_URL_PATH ), '/' );

				if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
					// Вывести счетчик на АМР.
					if ( $this->is_tracking_amp_active() ) {
						add_filter( 'amp_analytics_entries', [ $this, 'add_amp' ] );
					}
				} elseif ( function_exists( 'ampforwp_is_amp_inURL' ) && ampforwp_is_amp_inURL( $url_path ) ) {
					// Legacy AMP.
					if ( $this->is_tracking_amp_active() ) {
						add_filter( 'amp_post_template_analytics', [ $this, 'add_amp_legacy' ] );
					}
				} else {
					add_action( 'wp_body_open', [ $this, 'add' ] );
				}
			}
		);
	}

	/**
	 * Добавляет код трекера на все страницы AMP (устаревшие весрии).
	 *
	 * @param array $analytics Массив коинфигураций по умолчанию.
	 *
	 * @return array
	 */
	public function add_amp_legacy( array $analytics ): array {
		$counter_id    = $this->get_counter_id();
		$user_id       = $this->get_user_id();
		$tracking_user = $this->is_tracking_user_active();

		$analytics[ self::ANALYTICS_ID ] = array(
			'attributes'  => array(
				'type' => self::ANALYTICS_ID,
				'id'   => self::ANALYTICS_ID,
			),
			'config_data' => array(
				'vars' => array(
					'id' => $counter_id,
				),
			),
		);

		// Отслеживание пользователя.
		if ( $tracking_user ) {
			$analytics[ self::ANALYTICS_ID ]['config_data']['vars']['userid'] = $user_id;
		}

		return $analytics;
	}

	/**
	 * Добавляет код трекера на все страницы AMP..
	 *
	 * @param array $analytics_entries Массив коинфигураций по умолчанию.
	 *
	 * @return array
	 * @link https://amp-wp.org/documentation/getting-started/analytics/
	 */
	public function add_amp( array $analytics_entries ): array {
		$counter_id    = $this->get_counter_id();
		$user_id       = $this->get_user_id();
		$tracking_user = $this->is_tracking_user_active();

		$analytics_entries[ self::ANALYTICS_ID ] = [
			'type'   => self::ANALYTICS_ID,
			'config' => [
				'vars' => [
					'id' => $counter_id,
				],
			],
		];

		// Отслеживание пользователя.
		if ( $tracking_user ) {
			$analytics_entries[ self::ANALYTICS_ID ]['config']['vars']['userid'] = $user_id;
		}

		$analytics_entries[ self::ANALYTICS_ID ]['config'] = wp_json_encode( $analytics_entries[ self::ANALYTICS_ID ]['config'] );

		return $analytics_entries;
	}

	/**
	 * Вывод сгенерированного кода трекера.
	 *
	 * @return void
	 */
	public function add(): void {
		$counter_id    = $this->get_counter_id();
		$user_id       = $this->get_user_id();
		$domain        = $this->get_domain();
		$tracking_user = $this->is_tracking_user_active();
		?>
		<!-- Top.Mail.Ru counter -->
		<script type="text/javascript">
			var _tmr = window._tmr || (window._tmr = []);

			<?php if ( $tracking_user ) : ?>
				// Отправка UserID.
				_tmr.push({
					type: 'setUserID',
					userid: "<?php echo esc_attr( $user_id ); ?>"
				});
			<?php endif; ?>

			// Отправка lvid.
			_tmr.push({
				type:     'onready',
				callback: function() {
					const
						cookieName = '<?php echo esc_attr( Utils::get_plugin_slug() ); ?>_lvid',
						cookieValue = _tmr.getClientID();

					document.cookie = encodeURIComponent(cookieName) + '=' + encodeURIComponent(cookieValue);
				}
			});

			_tmr.push({
				id: "<?php echo esc_attr( $counter_id ); ?>",
				type: "pageView",
				start: (new Date()).getTime()
			});

			(function (d, w, id) {
				if (d.getElementById(id)) return;
				var ts = d.createElement("script");

				ts.type = "text/javascript";
				ts.async = true;
				ts.id = id;
				ts.src = "https://<?php echo esc_attr( $domain ); ?>/js/code.js";

				var f = function () {
					var s = d.getElementsByTagName("script")[0];
					s.parentNode.insertBefore(ts, s);
				};

				if (w.opera === "[object Opera]") {
					d.addEventListener("DOMContentLoaded", f, false);
				} else {
					f();
				}
			})(document, window, "tmr-code");
		</script>
		<noscript><div><img src="https://<?php echo esc_attr( $domain ); ?>/counter?id=<?php echo esc_attr( $counter_id ); ?>;js=na" style="position:absolute;left:-9999px;" alt="Top.Mail.Ru" /></div></noscript>
		<!-- /Top.Mail.Ru counter -->
		<?php
	}

	/**
	 * Проверяет активность фичи по трекингу АМР.
	 *
	 * @return bool
	 */
	public function is_tracking_amp_active(): bool {
		return $this->wposa->get_option( 'tracking_amp', 'general', 'off' ) === 'on';
	}

	/**
	 * Проверяет активность отслеживания пользователя.
	 *
	 * @return bool
	 */
	public function is_tracking_user_active(): bool {
		return is_user_logged_in() && ( $this->wposa->get_option( 'tracking_user', 'general', 'off' ) === 'on' );
	}
}
