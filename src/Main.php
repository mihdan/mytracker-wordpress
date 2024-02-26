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

		do_action( 'vk/analytics/init', $this );
	}

	/**
	 * Загрузка зависимостей.
	 *
	 * @return void
	 * @throws ConfigException Обработка исключений.
	 * @throws InjectionException Обработка исключений.
	 */
	private function load_requirements(): void {

		$this->wposa = $this->make( WPOSA::class );
		$this->wposa->set_plugin_name( Utils::get_plugin_name() );
		$this->wposa->set_plugin_version( Utils::get_plugin_version() );
		$this->wposa->set_plugin_slug( Utils::get_plugin_slug() );
		$this->wposa->set_plugin_prefix( Utils::get_plugin_prefix() );
		$this->wposa->setup_hooks();

		( $this->make( Logger::class ) )->setup_hooks();
		( $this->make( Settings::class ) )->setup_hooks();
		( $this->make( Code::class ) )->setup_hooks();
		( $this->make( S2S::class ) )->setup_hooks();
	}

	/**
	 * Инициализация хуков.
	 *
	 * @return void
	 */
	private function setup_hooks(): void {
		add_filter( 'plugin_action_links', [ $this, 'add_settings_link' ], 10, 2 );
		add_filter( 'admin_footer_text', [ $this, 'add_footer_text' ] );
		add_action( 'wp_head', [ $this, 'add_generator_text' ] );
	}

	/**
	 * Выводит информацию в метатеги о себе.
	 *
	 * @return void
	 */
	public function add_generator_text(): void {
		?>
		<meta name="generator" content="<?php echo esc_attr( Utils::get_plugin_name() ); ?> <?php echo esc_attr( Utils::get_plugin_version() ); ?>" />
		<?php
	}

	/**
	 * Добавляет текст в футер админки.
	 *
	 * @param string|null $text Текст по умолчанию.
	 *
	 * @return string
	 */
	public function add_footer_text( ?string $text ): string {
		if ( get_current_screen()->base !== 'settings_page_' . Utils::get_plugin_prefix() ) {
			return $text;
		} else {
			return __( 'Спасибо за творчество с <a href="https://vk.team" target="_blank">VK Team</a>', 'mytracker' );
		}
	}

	/**
	 * Add plugin action links
	 *
	 * @param array  $actions     Default actions.
	 * @param string $plugin_file Plugin file.
	 *
	 * @return array
	 */
	public function add_settings_link( array $actions, string $plugin_file ): array {
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
