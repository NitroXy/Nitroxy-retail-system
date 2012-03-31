<?php

class Transaction extends BasicObject {

	private $contents = array();
	/**
	 * Used by BasicObject to determine the table name.
	 * @returns the table name for the database relation.
	 */
	protected static function table_name() {
		return 'transactions';
	}

	public static function last() {
		$transaction = static::selection(array(
			'@order' => 'transaction_id:desc',
			'@limit' => 1,
		));
		if(count($transaction) == 0) {
			return null;
		}
		return $transaction[0];
	}

	public function add_content($product_id, $price, $count) {
		$content = new TransactionContent();
		if(substr($product_id, 0, 2) == "%%") {
			$coupon = new Coupon($product_id);
			$product = Product::from_ean('Kupong');
			if(!$product) {
				throw new Exception("Unable to find coupon in product table");
			}
			if($count > 1) {
				throw new Exception("Scanna inte kupongen flera gånger, tack.");
			}
			if($coupon->value != $price) {
				throw new Exception("Do not lie to me, son.");
			}
			if($coupon->status == "plundered") {
				throw new Exception("Denna kupong har redan använts.");
			}
			if($coupon->status != "valid") {
				throw new Exception("Oväntad statuskod för kupong: ".$coupon->status);
			}
			$content->coupon=$coupon;
		} else {
			$product = Product::from_id($product_id);
			if(!$product) {
				throw new Exception("Produkten med id {$product_id} finns inte");
			}
			if($product->price != $price && $product_id != 0) {
				throw new Exception("{$product->name} har ändrat pris sen formuläret laddades. Försök igen! Gammalt pris: {$product->price} kr nytt pris {$product_prices[$i]}");
			}
			$product->sell($count);
		}
		$content->product_id = $product->id;
		$content->count = $count;
		$content->amount = $price * $count;
		$content->stock_usage = $count * $product->value;
		$this->amount += $content->amount;
		$this->contents[] = $content;
	}

	public function commit(){
		parent::commit();
		foreach($this->contents as $content) {
			if($content->Product->ean == "Kupong") {
				if($content->coupon->plunder() != "ok") {
					throw new Exception("Oväntat fel vid plundring av kupong.");
				}
			}
			$content->transaction_id = $this->id;
			$content->commit();
		}
	}
}
?>
