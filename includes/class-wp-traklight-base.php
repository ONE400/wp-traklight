<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Traklight_Base {

	private $url;

	public function __construct()
	{
		//$subdomain = 'legalproplan';
		$subdomain = get_option('tl_subdomain');
		$server = get_option('tl_server');
		$staging_url = "https://{$subdomain}.staging.traklight.com";
		$prod_url = "https://{$subdomain}.apps.traklight.com";
		$this->url = ($server == 'staging') ? $staging_url : $prod_url;
	}

	public function show()
	{
        ob_start();
			$sid = $this->get_session();
			if($sid) echo $this->load_iframe($sid);
        return ob_get_clean();
	}

	public function load_iframe($sid)
	{
		$url1 = $this->url."/api/v1/login/session/{$sid}";
		$url2 = $this->url;
		include(dirname(__FILE__).'/templates/iframe.tpl.php');
	}

	public function get_session()
	{
		$api_url = $this->url.'/api/v1/login/';
		$params = Array(
			'ak'	=> get_option('tl_ak'),
			'email' => wp_get_current_user()->user_email,
			//'email' => 'kellewic+1@gmail.com',
		);

		$r = wp_remote_get( $api_url.'?'.http_build_query($params) );

		if($r['response']['code'] != 200)
		{
			error_log('WP Traklight Error: '.print_r($r['body'],true));
			$body = json_decode($r['body'],true);
			echo $body['error']['developerMessage'];
			return false;
		}
		else
		{
			$body = json_decode($r['body'],true);
			$sid = $body['data']['sid'];
			return $sid;
		}
	}
}

function debug($mixed)
{
	echo '<pre>'.print_r($mixed,true).'</pre>';
}
