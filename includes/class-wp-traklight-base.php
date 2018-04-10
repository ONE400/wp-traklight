<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Traklight_Base {

	private $tk;

	public function __construct()
	{
		$subdomain = get_option('tl_subdomain');
		$authKey = get_option('tl_ak');
		$server = get_option('tl_server');

		$this->tk = new TraklightApiWrapper($subdomain, $authKey);
		if($server == "staging") $this->tk->useStaging = true;
	}

	public function show()
	{
        ob_start();
			echo $this->load_iframe($sid);
        return ob_get_clean();
	}

	public function load_iframe($sid)
	{
		try {
			$email = wp_get_current_user()->user_email;
			$url = $this->tk->getSessionUrl($email);
			include(dirname(__FILE__).'/templates/iframe.tpl.php');
		} catch(TraklightException $e) {
			echo "Error: ".$e->getMessage();
		}
	}
}

function debug($mixed)
{
	echo '<pre>'.print_r($mixed,true).'</pre>';
}
