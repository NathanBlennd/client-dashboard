<?php
/**
 * Client.
 *
 * @package client-dashboard
 */

declare(strict_types=1);
namespace blenndiris\clientDashboard;

// @TODO remove endpoint
// @TODO remove json method
class Client {

	public function __construct(
		protected string $host,
		protected string $endpoint,
		protected string $key,
	) {}

	public function __get( string $value ) : string {
		return $this->$value;
	}

	public function json() : string {
		return json_encode( [
			'host' => $this->host,
			'key' => $this->key,
			'endpoint' => $this->endpoint,
		] );
	}
}
