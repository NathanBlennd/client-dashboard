<?php
/**
 * Options.
 *
 * @package client-dashboard
 */

declare(strict_types=1);
namespace blenndiris\clientDashboard;

class Options {

	protected array $options;

	public function __construct() {
		$options = get_option( 'client_dashboard_options' );
		if( ! is_array( $options ) ) {
			$options = [];
		}
		$this->options = $options;
	}

	public function __get( string $value ) : string {
		return $this->options[ $value ] ?? '';
	}

}
