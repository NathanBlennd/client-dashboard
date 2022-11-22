<?php
/**
 * Settings.
 *
 * @package client-dashboard
 */

declare(strict_types=1);
namespace blenndiris\clientDashboard;

class Settings {

	public function __construct(
		protected Options $options,
	) {
	}

	public function admin_init() {

		add_action( 'load-settings_page_client-dashboard-settings', [ $this, 'enqueue_style' ] );
		add_filter( 'plugin_action_links_client-dashboard/client-dashboard.php', [ $this,  'add_settings_link' ] );


		register_setting( 'client_dashboard', 'client_dashboard_options' );

		add_settings_section(
			'client_dashboard',
			__( 'Settings', 'client-dashboard' ),
			[ $this, 'section_callback' ],
			'client_dashboard'
		);

		add_settings_field(
			'client_dashboard_field_location',
			__( 'Location', 'client-dashboard' ),
			[ $this, 'field_location_callback' ],
			'client_dashboard',
			'client_dashboard',
			[
				'label_for' => 'client_dashboard_field_location',
				'class'     => 'client_dashboard_row',
			]
		);

		add_settings_field(
			'client_dashboard_field_key',
			__( 'Key', 'client-dashboard' ),
			[ $this, 'field_key_callback' ],
			'client_dashboard',
			'client_dashboard',
			[
				'label_for' => 'client_dashboard_field_key',
				'class'     => 'client_dashboard_row',
			]
		);

		if(
			'dashboard' === $this->options->client_dashboard_field_location
		) {
			add_settings_field(
				'client_dashboard_clients_key',
				__( 'Clients', 'client-dashboard' ),
				[ $this, 'field_clients_callback' ],
				'client_dashboard',
				'client_dashboard',
				[
					'label_for' => 'client_dashboard_field_clients',
					'class'     => 'client_dashboard_row',
				]
			);
		}
	}

	public function add_settings_link( $links ) {
		$url = esc_url( add_query_arg(
			'page',
			'client-dashboard-settings',
			get_admin_url() . 'options-general.php'
		) );
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
		array_unshift(
			$links,
			$settings_link
		);
		return $links;
	}

	public function enqueue_style() {
		wp_enqueue_style( 'client-dashboard-settings', plugins_url() . '/client-dashboard/assets/client-dashboard-settings.css', [ 'wp-admin' ] );
	}


	public function section_callback( $args ) {
	}

	public function field_location_callback( $args ) {
		$label = $args[ 'label_for' ];
		?>
		<select
			id="<?php echo esc_attr( $args[ 'label_for' ] ); ?>"
			name="client_dashboard_options[<?php echo esc_attr( $args[ 'label_for' ] ); ?>]">

			<option value="none" <?php echo selected( $this->options->$label, 'none', false ); ?>>
				<?php esc_html_e( 'None', 'client-dashboard' ); ?>
			</option>
			<option value="client" <?php echo selected( $this->options->$label, 'client', false ); ?>>
				<?php esc_html_e( 'Client', 'client-dashboard' ); ?>
			</option>
			<option value="dashboard" <?php echo selected( $this->options->$label, 'dashboard', false ); ?>>
				<?php esc_html_e( 'Dashboard', 'client-dashboard' ); ?>
			</option>
		</select>
		<?php
	}

	protected function key( string $key ) {
		if( '' === $key ) {
			for( $i = 0; $i <= 3; $i++ ) {
				if( '' !== $key ) {
					$key .= '-';
				}
				$number = (string) random_int( 10000000000, 99999999999 );
				$number = base_convert( $number, 10,26 );

				$key .= $number;
			}
		}
		return $key;
	}

	public function field_key_callback( $args ) {
		$label = $args[ 'label_for' ];
		$key = $this->options->$label;
		if( ! $key ) {
			$key = $this->key;
		}
		?>
		<input
			id="<?php echo esc_attr( $args[ 'label_for' ] ); ?>"
			name="client_dashboard_options[<?php echo esc_attr( $args[ 'label_for' ] ); ?>]"
			value="<?php echo $key; ?>"
			type="text"
			hidden
		>
		<input
			disabled
			value="<?php echo trailingslashit( get_site_url() ) . $key; ?>"
			type="text"
		>
		<?php
	}

	public function field_clients_callback( $args ) {
		$label = $args[ 'label_for' ];
		?>
		<textarea
			id="<?php echo esc_attr( $args[ 'label_for' ] ); ?>"
			name="client_dashboard_options[<?php echo esc_attr( $args[ 'label_for' ] ); ?>]"
			rows="5"
		><?php echo $this->options->$label; ?></textarea>
		<?php
	}

	public function add_submenu_page() {
		add_submenu_page(
			'options-general.php',
			'Client Dashboard',
			'Client Dashboard',
			'manage_options',
			'client-dashboard-settings',
			[ $this, 'client_dashboard_html' ]
		);
	}

	public function client_dashboard_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'client_dashboard' );
				do_settings_sections( 'client_dashboard' );
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

}
