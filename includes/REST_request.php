<?php
/**
 * REST Request.
 *
 * @package client-dashboard
 */

declare(strict_types=1);
namespace blenndiris\clientDashboard;

class REST_Request {

	public function __construct(
		public Client $client,
	) {
	}

	public function get_plugins() : array {
		$args = [
			'sslverify' => false,
			'headers' => [
				'key' => $this->client->key,
			],
		];

		$plugins = get_transient( 'client-dashboard' );
		if( ! is_array( $plugins ) ) {
			$plugins = [];
		}

		if(
			! isset( $plugins[ $this->client->host ] ) ||
			empty( $plugins[ $this->client->host ] )
		) {
			$get = wp_remote_get( $this->client->endpoint, $args );
			$body = wp_remote_retrieve_body( $get );
			$plugins[ $this->client->host ] = $body;
			set_transient( 'client-dashboard', $plugins, DAY_IN_SECONDS );
		}
		return (array) json_decode( $plugins[ $this->client->host ] );
	}
}
