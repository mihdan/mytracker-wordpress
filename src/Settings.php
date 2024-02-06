<?php
/**
 * Страница настроек.
 *
 * @package mytarget
 */

namespace VK\MyTracker;

/**
 * Класс Settings
 */
class Settings {
	/**
	 * WP_OSA instance.
	 *
	 * @var WPOSA $wposa
	 */
	private $wposa;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	private $logger;

	/**
	 * Constructor.
	 *
	 * @param WPOSA  $wposa  WPOSA instance.
	 * @param Logger $logger Logger instance.
	 */
	public function __construct( WPOSA $wposa, Logger $logger ) {
		$this->wposa  = $wposa;
		$this->logger = $logger;
	}

	/**
	 * Setup hooks.
	 */
	public function setup_hooks(): void {
		add_action( 'init', [ $this, 'setup_fields' ], 101 );
	}

	/**
	 * Setup setting fields.
	 *
	 * @link https://yandex.ru/support/webmaster/indexnow/key.html
	 */
	public function setup_fields() {

		$this->wposa->add_sidebar_card(
			[
				'id'    => 'attention',
				'title' => __( 'Attention', 'mytracker' ),
				'desc'  => __( 'Uploading statistics into tracker database can take about one to two hours.', 'mytracker' ),
			]
		);

		$this->wposa->add_sidebar_card(
			[
				'id'    => 'documentation',
				'title' => __( 'Documentation', 'mytracker' ),
				'desc'  => function() {
					?>
					<ol>
						<li><a href="https://tracker.my.com/promo" target="_blank">Promo page</a></li>
						<li><a href="https://tracker.my.com/docs/sdk/about" target="_blank">SDK integration</a></li>
						<li><a href="https://tracker.my.com/docs/api/s2s-api/about" target="_blank">S2S API</a></li>
						<li><a href="https://tracker.my.com/account/list/" target="_blank">Account</a></li>
					</ol>
					<?php
				},
			]
		);

		$this->wposa->add_section(
			array(
				'id'    => 'general',
				'title' => __( 'General', 'mytracker' ),
				'desc'  => sprintf(
				/* translators: %s: Official site */
					__( 'VK Analytics is multi-platform analytics and attribution for mobile apps and websites. More details at <a href="%1$s" target="_blank">%1$s</a>.', 'mytracker' ),
					'https://tracker.my.com/'
				),
			)
		);

		$this->wposa->add_field(
			'general',
			array(
				'id'          => 'counter_id',
				'type'        => 'number',
				'name'        => __( 'Counter ID', 'mytracker' ),
				'desc'        => __( 'Web Counter ID in your account MyTracker.', 'mytracker' ),
				'placeholder' => '3308081',
			)
		);

		$this->wposa->add_field(
			'general',
			array(
				'id'      => 'domain',
				'type'    => 'select',
				'name'    => __( 'Domain', 'mytracker' ),
				'options' => [
					'ru'  => __( 'RU domain', 'mytracker' ),
					'com' => __( 'COM domain', 'mytracker' ),
				],
				'desc'    => __( 'To track website visits from regions where the VK services are available, use the RU domain. Select the COM domain if you need to track website visits from regions where the VK services are not available.', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'general',
			array(
				'id'      => 'tracking_user',
				'type'    => 'switch',
				'name'    => __( 'Tracking user', 'mytracker' ),
				'default' => 'off',
				'desc'    => __( 'Allows you to track statistics not only on devices, but also on registered users of your site.', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'general',
			array(
				'id'      => 'tracking_amp',
				'type'    => 'switch',
				'name'    => __( 'AMP Support', 'mytracker' ),
				'default' => 'off',
				'desc'    => __( 'Enables analytics on AMP pages.', 'mytracker' ),
			)
		);

		$this->wposa->add_section(
			array(
				'id'    => 'api',
				'title' => __( 'API', 'mytracker' ),
				'desc'  => __( 'S2S API allows you to send data to the MyTracker server. With this API, you can upload any events (for example, user registration and authorization) Uploaded events is added to the project data and displayed in MyTracker reports.', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'api',
			array(
				'id'   => 'app_id',
				'type' => 'text',
				'name' => __( 'App ID', 'mytracker' ),
				'desc' => __( 'Enter idApp web application from your MyTracker account.', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'api',
			array(
				'id'          => 'api_key',
				'type'        => 'text',
				'name'        => __( 'API Key', 'mytracker' ),
				'desc'        => __( 'Enter S2S API key from your MyTracker account.', 'mytracker' ),
				'placeholder' => '6jT9Firgf35Z2zDEB0v8ZniBgr8WTq0IcZlecewFWZImrs5KXcRbdDMLgdQj05iO',
			)
		);

		$this->wposa->add_field(
			'api',
			array(
				'id'      => 'tracking_sign_in',
				'type'    => 'switch',
				'name'    => __( 'Tracking login', 'mytracker' ),
				'default' => 'off',
				'desc'    => __( 'Tracking user authorization.', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'api',
			array(
				'id'      => 'tracking_sign_up',
				'type'    => 'switch',
				'name'    => __( 'Tracking registration', 'mytracker' ),
				'default' => 'off',
				'desc'    => __( 'Tracking user registration.', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'api',
			array(
				'id'      => 'debugging',
				'type'    => 'switch',
				'name'    => __( 'Debugging', 'mytracker' ),
				'default' => 'off',
				'desc'    => __( 'Debugging API queries.', 'mytracker' ) . ( $this->logger->log_exists() ? sprintf( ' <a href="%s" download="mytracker.log" target="_blank">%s</a>', $this->logger->get_url(), __( 'View log file', 'mytracker' ) ) : '' ),
			)
		);

		if ( $this->logger->log_exists() ) {
			$this->wposa->add_field(
				'api',
				array(
					'id'          => 'remove_log',
					'type'        => 'button',
					'placeholder' => __( 'Remove Log', 'mytracker' ),
					'default'     => 'off',
					'desc'        => __( 'Debugging API queries.', 'mytracker' ),
				)
			);
		}
	}
}
