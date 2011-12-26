<?php

class TransactionContent extends BasicObject {
	public $coupon=null;

	/**
	 * Used by BasicObject to determine the table name.
	 * @returns the table name for the database relation.
	 */
	protected static function table_name() {
		return 'transaction_contents';
	}
}
?>

