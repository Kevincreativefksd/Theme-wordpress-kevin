<?php
/**
 * Zona Ekonomi: functions and definitions.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZE_VERSION', '1.0.0' );
define( 'ZE_DIR', get_template_directory() );
define( 'ZE_URI', get_template_directory_uri() );

$ze_inc_files = array(
	'options',
	'setup',
	'enqueue',
	'customizer',
	'template-functions',
	'template-tags',
	'breadcrumbs',
	'performance',
	'compatibility',
);

foreach ( $ze_inc_files as $ze_inc_file ) {
	require_once ZE_DIR . '/inc/' . $ze_inc_file . '.php';
}

unset( $ze_inc_files, $ze_inc_file );
