<?php
/**
 * Encapsulates the utility functions not required by user.
 */
class TraklightApiWrapperBase {

	public $useStaging = false;
	protected $curl;

	public function baseUrl() {
		$sd = ($this->useStaging) ? 'staging':'apps';
		return "https://{$this->subdomain}.{$sd}.traklight.com";
	}

	protected function get($url, $params=Array()) {
		$params = array_merge($params, Array('ak' => $this->authKey));
		$query_str = http_build_query($params);
		$url = $this->baseUrl().$url.'?'.$query_str;
		$this->curl = curl_init($url);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		$resp = curl_exec($this->curl);
		$this->checkRespErrors($resp);
		curl_close($this->curl);
		$resp = json_decode($resp,true);
		return $resp['data'];
	}

	protected function post($url, $params=Array()) {
		$json_data = json_encode($params);
		$url = $this->baseUrl().$url.'?ak='.$this->authKey;
		$this->curl = curl_init($url);
		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($json_data))
		);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
		$resp = curl_exec($this->curl);
		$this->checkRespErrors($resp);
		$resp = json_decode($resp,true);

		return $resp['data'];
	}

	protected function checkRespErrors($resp) {
		if(curl_errno($this->curl)) {
			throw new TraklightException(
				curl_error($this->curl),
				curl_errno($this->curl)
			);
		}
		$info = curl_getinfo($this->curl);
		if($info['http_code'] == 200) return;
		$obj = json_decode($resp);
		if($obj) {
			throw new TraklightException(
				"({$info['http_code']}) {$obj->error->developerMessage}",
				$info['http_code']
			);
		} else {
			throw new TraklightException(
				"Server returned status {$info['http_code']}",
				$info['http_code']
			);
		}
	}
}

class TraklightException extends Exception {

	public function __construct($message, $code) {
		parent::__construct($message, $code);
	}

};
