<?php

namespace WP_CLI\KonstantinosTsatsarounos;

use WP_CLI;
use WP_CLI\Utils;

if ( ! class_exists( '\WP_CLI' ) ) {
	return;
}

$wpcli_check_autoloader = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $wpcli_check_autoloader ) ) {
	require_once $wpcli_check_autoloader;
}

WP_CLI::add_command( 
	'checkurl', 
	[Check::class, 'single'] 
);


WP_CLI::add_command(
	'check pages',
	[Check::class, 'pages']
);