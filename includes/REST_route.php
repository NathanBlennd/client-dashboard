<?php
/**
 * REST Route.
 *
 * @package client-dashboard
 */

declare(strict_types=1);
namespace blenndiris\clientDashboard;

use WP_REST_Request;

class REST_Route {

	const NAMESPACE = 'client-dashboard/v1';

	public function __construct(
		protected Options $options,
	) {}

	public function check_access( WP_REST_Request $request ) : bool {
		return $request->get_header( 'key' ) === $this->options->client_dashboard_field_key;
	}

	public function list_plugins( WP_REST_Request $request ) : array {
		require_once ABSPATH . 'wp-admin/includes/update.php';
		$plugins = get_plugins();
		$updates = get_plugin_updates();
		foreach( $updates as $update ) {
			$plugins[ $update->update->plugin ][ 'Slug' ] = $update->update->plugin;
			$plugins[ $update->update->plugin ][ 'NewVersion' ] = $update->update->new_version;
		}
		return $plugins;
	}

	public function update_plugin( WP_REST_Request $request ) {
		$status = [
			'slug' => $request->get_header( 'slug' ),
			'version' => $request->get_header( 'version' ),
		];

		$status[ 'status' ] = 'updated';
		return $status;
	}

	public function rest_api_init() : void {
		register_rest_route( SELF::NAMESPACE, 'plugin/list', [
			'methods' => 'GET',
			'callback' => [ $this, 'list_plugins' ],
			'permission_callback' => [ $this, 'check_access' ],
		] );

		register_rest_route( SELF::NAMESPACE, 'plugin/update', [
			'methods' => 'POST',
			'callback' => [ $this, 'update_plugin' ],
			'permission_callback' => [ $this, 'check_access' ],
		] );
	}
}
