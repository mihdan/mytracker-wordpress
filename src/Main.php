<?php
/**
 * Основной файл плагина.
 *
 * @package mytracker
 */

namespace VK\MyTracker;

use Auryn\Injector;
use Auryn\InjectionException;
use Auryn\ConfigException;

/**
 * Main class.
 */
class Main {
	/**
	 * DIC container.
	 *
	 * @var Injector $injector
	 */
	private Injector $injector;

	/**
	 * Settings instance.
	 *
	 * @var WPOSA $wposa
	 */
	private WPOSA $wposa;

	/**
	 * Конструктор класса.
	 *
	 * @param Injector $injector Экземпляр класса.
	 */
	public function __construct( Injector $injector ) {
		$this->injector = $injector;
	}

	/**
	 * Make a class from DIC.
	 *
	 * @param string $class_name Full class name.
	 * @param array  $args List of arguments.
	 *
	 * @return mixed
	 *
	 * @throws InjectionException If a cyclic gets detected when provisioning.
	 * @throws ConfigException If $nameOrInstance is not a string or an object.
	 */
	public function make( string $class_name, array $args = [] ) {
		return $this->injector->share( $class_name )->make( $class_name, $args );
	}

	/**
	 * Инициализация плагина.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->load_requirements();
		$this->setup_hooks();

		do_action( 'vk/mytracker/init', $this );
	}

	/**
	 * Загрузка зависимостей.
	 *
	 * @return void
	 * @throws ConfigException Обработка исключений.
	 * @throws InjectionException Обработка исключений.
	 */
	private function load_requirements(): void {

		$wposa = $this->make(
			WPOSA::class,
			[
				':plugin_name'    => Utils::get_plugin_name(),
				':plugin_version' => Utils::get_plugin_version(),
				':plugin_slug'    => Utils::get_plugin_slug(),
				':plugin_prefix'  => Utils::get_plugin_prefix(),
			]
		);

		$this->wposa = $wposa;
		$this->wposa->setup_hooks();

		( $this->make( Settings::class ) )->setup_hooks();
		( $this->make( Code::class ) )->setup_hooks();
		//( $this->make( Amp::class ) )->setup_hooks();
	}

	/**
	 * Инициализация хуков.
	 *
	 * @return void
	 */
	private function setup_hooks(): void {
		add_filter( 'plugin_action_links', [ $this, 'add_settings_link' ], 10, 2 );
		add_filter( 'admin_footer_text', [ $this, 'add_footer_text' ] );
	}

	/**
	 * Добавляет текст в футер админки.
	 *
	 * @param string $text Текст по умолчанию.
	 *
	 * @return string
	 */
	public function add_footer_text( string $text ): string {
		if ( get_current_screen()->base !== 'settings_page_' . Utils::get_plugin_prefix() ) {
			return $text;
		} else {
			return __( 'Спасибо за творчество с <a href="https://vk.team" target="_blank">VK Team</a>', 'mytracker' );
		}
	}

	/**
	 * Add plugin action links
	 *
	 * @param array  $actions Default actions.
	 * @param string $plugin_file Plugin file.
	 *
	 * @return array
	 */
	public function add_settings_link( $actions, $plugin_file ) {
		if ( Utils::get_plugin_basename() === $plugin_file ) {
			$actions[] = sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'options-general.php?page=' . Utils::get_plugin_slug() ),
				esc_html__( 'Settings', 'mytracker' )
			);
		}

		return $actions;
	}
}
