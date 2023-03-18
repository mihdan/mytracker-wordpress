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
	public $wposa;

	/**
	 * Constructor.
	 *
	 * @param WPOSA $wposa WPOSA instance.
	 */
	public function __construct( WPOSA $wposa ) {
		$this->wposa = $wposa;
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

		$this->wposa->add_section(
			array(
				'id'    => 'general',
				'title' => __( 'General', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'general',
			array(
				'id'          => 'counter_id',
				'type'        => 'number',
				'name'        => __( 'Counter ID', 'mytracker' ),
				'desc'        => __( 'Идентификатор счётчика от Рейтинга Mail.Ru', 'mytracker' ),
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
				'desc'    => __( 'С помощью RU домена вы можете отслеживать посещения сайта из регионов, в которых доступны VK сервисы. Если необходимо отслеживать посещения сайта из регионов, в которых доступ к сервисам VK ограничен, выберите COM домен.', 'mutracker' ),
			)
		);

		$this->wposa->add_field(
			'general',
			array(
				'id'      => 'tracking_user',
				'type'    => 'switch',
				'name'    => __( 'Tracking user', 'mytracker' ),
				'default' => 'off',
				'desc'    => __( 'Позволяет отслеживать статистики не только по устройствам, но и по пользователям сайта', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'general',
			array(
				'id'      => 'tracking_sign_in',
				'type'    => 'switch',
				'name'    => __( 'Tracking login', 'mytracker' ),
				'default' => 'off',
				'desc'    => __( 'Сбор данных об авторизации пользователей', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'general',
			array(
				'id'      => 'tracking_sign_up',
				'type'    => 'switch',
				'name'    => __( 'Tracking registration', 'mytracker' ),
				'default' => 'off',
				'desc'    => __( 'Сбор данных о регистрации пользователей', 'mytracker' ),
			)
		);

		$this->wposa->add_field(
			'general',
			array(
				'id'      => 'tracking_amp',
				'type'    => 'switch',
				'name'    => __( 'AMP Support', 'mytracker' ),
				'default' => 'off',
				'desc'    => __( 'Включает аналитику на AMP-страницах', 'mytracker' ),
			)
		);
	}
}
