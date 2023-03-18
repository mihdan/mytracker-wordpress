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

		add_action(
			'init',
			function () {
				$url_path = trim( wp_parse_url( add_query_arg( [] ), PHP_URL_PATH ), '/' );
				if (
					( function_exists( 'ampforwp_is_amp_inURL' ) && ampforwp_is_amp_inURL( $url_path ) )
					||
					function_exists( 'amp_is_request' ) && amp_is_request()
				) {
					// Вывести счетчик на АМР.
					add_action( 'amp_foo', [ $this, 'add' ] );
				} else {
					add_action( 'wp_body_open', [ $this, 'add' ] );
				}
			}
		);

	}

	/**
	 * Вывод сгенерированного кода трекера.
	 *
	 * @return void
	 */
	public function add(): void {
		$counter_id = $this->wposa->get_option( 'counter_id', 'general', 0 );
		$domain     = $this->wposa->get_option( 'domain', 'general', 'ru' );

		$tracking_user         = is_user_logged_in() && $this->wposa->get_option( 'tracking_user', 'general', 'off' ) === 'on';
		$tracking_registration = $this->wposa->get_option( 'tracking_sign_up', 'general', 'off' ) === 'on';
		$tracking_login        = $this->wposa->get_option( 'tracking_sign_in', 'general', 'off' ) === 'on';
		$tracking_amp          = $this->wposa->get_option( 'tracking_amp', 'general', 'off' ) === 'on';

		$domain = self::DOMAINS[ $domain ];
		?>
		<!-- Top.Mail.Ru counter -->
		<script type="text/javascript">
			var _tmr = window._tmr || (window._tmr = []);

			<?php if ( $tracking_user ) : ?>
				_tmr.push({ type: 'setUserID', userid: "<?php echo esc_attr( get_current_user_id() ); ?>" });
			<?php endif; ?>

			_tmr.push({id: "<?php echo esc_attr( $counter_id ); ?>", type: "pageView", start: (new Date()).getTime()});

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
}
