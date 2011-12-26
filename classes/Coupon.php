<?php
class Coupon {
	private $data;

	public function plunder() {
		$json = self::curl(array(
			'action' => 'plunder',
			'code' => $this->data['code']
		));
		$result = json_decode($json, true);
		return ($result['status']==="ok");
	}

	public function toarray() {
		return $this->data;
	}

	public function __get($key) {
		if(array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
		throw new Exception("No such field ".$key);
	}

	private static function curl($post_fields) {
		global $settings;
		$request = curl_init($settings['coupon_url']);

		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_FAILONERROR, true);
		curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($request, CURLOPT_POST, true);
		$post_fields['secret']='xwsoTv&K+d#va9/di8g0gK#G2%*Eh1Dj.F6Iz9uV!eL8hURnuXYheB-/5d%HH.xFDd$2$iyYm!HtE/HZVf60G3Ym6MToK(U%,HQ8Q&G2Z$,ehD3HJKin6c+tWi4N';
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_fields);

		$json = curl_exec($request);
		if($json === false) {
			throw new Exception("Failed to connect to server");
		}
		return $json;
	}

	public function __construct($code) {
		global $settings;
		if(!isset($settings['coupon_url'])) {
			throw new Exception("Coupon system is disabled. Check coupon URL in settings.php");
		}
		$json = self::curl(array(
			'action' => 'info',
			'code' => $code
		));
		$result = json_decode($json, true);

		if($result['status'] == "error") {
			throw new Exception("Error from server: ".$result['error']);
		}
		$this->data=$result;
		$this->data['code']=$code;
	}
}
