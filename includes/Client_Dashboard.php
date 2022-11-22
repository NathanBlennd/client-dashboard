<?php
/**
 * Client Dashboard.
 *
 * @package client-dashboard
 */

declare(strict_types=1);
namespace blenndiris\clientDashboard;

class Client_Dashboard {

	public function __construct(
		protected Dashboard $dashboard,
		protected Options $options,
		protected REST_Route $rest_route,
		protected Settings $settings,
	) {}

	public function init() {
		add_action( 'rest_api_init', [ $this->rest_route, 'rest_api_init' ] );

		add_action( 'admin_init', [ $this->settings, 'admin_init' ] );
		add_action( 'admin_menu', [ $this->settings, 'add_submenu_page' ] );

		if(
			'dashboard' === $this->options->client_dashboard_field_location
		) {
			add_action( 'admin_init', [ $this->dashboard, 'admin_init' ] );
			add_action( 'admin_menu', [ $this->dashboard, 'add_submenu_page' ] );
		}
	}
}
