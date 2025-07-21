<?php
/**
 *
 * Global config file
 *
 * @author	Logimax Team
 */

 //Check direct script access
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
	header("HTTP/1.0 404 Not Found");
	echo "<h1>Not Found</h1>";
	echo "The requested URL was not found on this server.";
	exit();
}

/**
 * All global constants
 *
 */
class Globals {

	/**
	 * Purchase Plan API version 
	 */
	public static $pp_api_version = "1.0.0";

	/*
	 * DB configuation. host name, username, password, database name
	 */

	public static $hostname = "localhost";
	public static $username = "retaillogimaxind_etailv2_admin";
	public static $password = "ys?vY6lh2TKm";
	public static $database = "retaillogimaxind_etailv2";
    
    
    /**
	* Purchase Plan acclount 
	*/
    public static $default_acno_label = "Transaction Pending";
    
	/**
	 * Timezone for this website
	 */
	public static $timezone = "Asia/Kolkata";
}

//Global declarations 
date_default_timezone_set(Globals::$timezone);

//Check cURL exists
if (! function_exists ( 'curl_version' )) {
    exit ( "Enable cURL in PHP to proceed..." );
}