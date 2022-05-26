<?php

namespace WP_CLI\KonstantinosTsatsarounos;

use WP_CLI;
use WP_CLI_Command;

class Check extends WP_CLI_Command {

	/**
	 * Checks a url if it is responding correctly.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp checkurl http://mydomain.com/hello.php
	 *     Success: The url is valid and working!
	 *
	 * @when before_wp_load
	 *
	 * @param array $args       Indexed array of positional arguments.
	 * @param array $assoc_args Associative array of associative arguments.
	 */
	public function single( $args, $assoc_args ) {
		if (filter_var($args[0], FILTER_VALIDATE_URL) === FALSE) {
			WP_CLI::error('not valid url');
		}

		$client = new HTTPClient();
		$message = "URL Not Exists or Blocked";	
		try {
			$response = $client->request('GET', $args[0]);
			$status_code = $response->getStatusCode();

			$message = "Ends with error {$status_code}";
			if($status_code == 200){
				$message = 'The url is valid and working';
			}
		} catch (\Throwable $th) {			
			
		}
		
		WP_CLI::success( $message );
	}

	/**
	 * Checks each page url and returns a list of failed pages.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp check pages
	 *     Success: [a list of failed pages]
	 *
	 * @when before_wp_load
	 *
	 * @param array $args       Indexed array of positional arguments.
	 * @param array $assoc_args Associative array of associative arguments.
	 */
	public function pages($args, $assoc_args){
		$pages = get_pages();

		$failed_results = [];
		foreach ($pages as $page) {
			$link = get_permalink($page->ID);
			$result = $this->check($link);

			if($result == FALSE){
				WP_CLI::line("{$page->post_name} {$link} FAILED");
				array_push($failed_results, $page);
			}
		}

		WP_CLI::success( "Failed Results: ". count( $failed_results ) );
	}


	/**
	 * 
	 */
	private function check($url){
		$client = new HTTPClient();
		$result = FALSE;

		try {
			$response = $client->request('GET', $url);
			$status_code = $response->getStatusCode();

			if($status_code == 200){
				$result = TRUE;
			}
		} catch (\Throwable $th) {			
			WP_CLI::log( $th->$message );
		}
	}

}
