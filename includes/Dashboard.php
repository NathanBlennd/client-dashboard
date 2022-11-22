<?php
/**
 * Dashboard.
 *
 * @package client-dashboard
 */

declare(strict_types=1);
namespace blenndiris\clientDashboard;

class Dashboard {

	public function __construct(
		protected Options $options,
	){}

	public function admin_init() {
		add_action( 'load-tools_page_client-dashboard', [ $this, 'enqueue_style' ] );
	}

	public function enqueue_style() {
		wp_enqueue_style(
			'client-dashboard',
			plugins_url() . '/client-dashboard/assets/client-dashboard.css',
			[ 'wp-admin' ],
			'0.0.1'
		);

		wp_enqueue_script(
			'client-dashboard',
			plugins_url() . '/client-dashboard/assets/client-dashboard.js',
			[],
			'0.0.1',
			true
		);
	}

	protected function client_html( Client $client ) : void {

		// @TODO - AJAX
		$rest_request = new REST_Request( $client );
		$plugins = $rest_request->get_plugins();
		?>
		<article
			data-id="<?php echo sanitize_title( $client->host ); ?>"
			class="client-dashboard__client"
		>
			<details>
				<summary>
					<h2 class="client-dashboard__host"><?php esc_html_e( $client->host ); ?></h2>
				</summary>
				<table
					class="client-dashboard__table"
					data-json="<?php echo htmlentities( $client->json() ); ?>"
				>
					<thead>
						<tr>
							<th class="client-dashboard__name">Name</th>
							<th class="client-dashboard__version">Version</th>
							<th class="client-dashboard__update">Update</th>
						</tr>
					</thead>
					<tbody class="client-dashboard__plugins">
						<?php foreach( $plugins as $slug => $plugin ) : ?>
							<tr class="client-dashboard__plugin" data-plugin="<?php echo $slug; ?>">
								<td class="client-dashboard__name">
									<?php echo esc_html_e( $plugin->Name ); ?>
								</td>
								<td class="client-dashboard__version">
									<?php echo esc_html_e( $plugin->Version ); ?>
								</td>
								<td class="client-dashboard__update">
									<?php
										if( isset( $plugin->NewVersion ) ) {
											printf(
												'<button class="%1$s" data-slug="%2$s" data-version="%3$s">%3$s</button>',
												'client-dashboard__update-button',
												$plugin->Slug,
												$plugin->NewVersion
											);
										}
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</details>
		</article>
		<?php
	}

	protected function get_clients() : array {
		$return = [];

		$clients = $this->options->client_dashboard_field_clients;
		$clients = explode( "\n", $clients );

		foreach( $clients as $client ) {
			$client = trim( $client );
			$parsed = parse_url( $client );

			$host = $parsed[ 'host' ];
			$key = ltrim( $parsed[ 'path' ], '/' );

			// @TODO - remove endpoint from client object
			$endpoint = $parsed[ 'scheme' ] . '://' . $parsed[ 'host' ] . '/wp-json/client-dashboard/v1/plugin/list';

			$return[] = new Client(
				host: $host,
				endpoint: $endpoint,
				key: $key,
			);
		}

		return $return;
	}

	public function add_submenu_page() : void {
		add_submenu_page(
			'tools.php',
			'Client Dashboard',
			'Client Dashboard',
			'manage_options',
			'client-dashboard',
			[ $this, 'client_dashboard' ]
		);
	}

	public function client_dashboard() : void {
		$clients = $this->get_clients();
		?>
		<section class="client-dashboard">
			<h1 class="client-dashboard__heading">Client Dashboard</h1>
			<input
				id="client-dashboard-search"
				class="client-dashboard__search"
				type="text"
				placeholder="Search.."
			>
			<?php
			foreach( $clients as $client ) {
				$this->client_html( $client );
			}
			?>
		</section>
		<?php
	}

}
