<?php
/**
 * Client Dashboard plugin for WordPress
 *
 * @package   client-dashboard
 * @link      https://github.com/NathanBlennd/client-dashboard
 * @author    Nathan Johnson <nathan@blennd.com>
 * @copyright 2022 Blennd
 * @license   GPL v2 or later
 *
 * Plugin Name:       Client Dashboard by Blennd
 * Description:       Easily access information about client sites through a unified dashboard.
 * Version:           0.1.0
 * Plugin URI:        https://github.com/NathanBlennd/client-dashboard
 * Author:            Blennd
 * Author URI:        https://github.com/NathanBlennd/
 * Requires at least: 5.9
 * Requires PHP:      8.0
 * Text Domain:       client-dashboard
 * Domain Path:       /languages/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

declare(strict_types=1);
namespace blenndiris\clientDashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/includes/autoload.php';

$options = new Options();
$client_dashboard = new Client_Dashboard(
	dashboard: new Dashboard( options: $options ),
	options: $options,
	rest_route: new REST_Route( options: $options ),
	settings: new Settings( options: $options ),
);
add_action( 'plugins_loaded', [ $client_dashboard, 'init' ] );
