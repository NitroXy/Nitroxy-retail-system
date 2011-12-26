<?php
class CouponC extends Controller {
	public function info($params) {
		$this->_access_type('script');

		try {
			$c = new Coupon($_POST['code']);
			$json=json_encode($c->toarray());
		} catch(Exception $e) {
			$json=json_encode(array("status" => "error", "error" => $e->getMessage()));
		}
		echo $json;
	}
}
