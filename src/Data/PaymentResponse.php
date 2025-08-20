<?php namespace Dpay\Data;
// Pauldro Minicli
use Pauldro\Minicli\v2\Util\Data;

/**
 * Response
 * Container for Payment Response data
 * 
 * @property int    $ordn
 * @property string $type           Transaction Type
 * @property bool   $approved       Was Transaction Approved?
 * @property string $errorCode
 * @property string $errorMsg
 * @property string $avsCode
 * @property string $avsMsg
 * @property string $authCode
 * @property string $transactionid
 */
class PaymentResponse extends Data {
	public function __construct() {
		$this->ordn      = '';
		$this->type      = '';
		$this->approved  = false;
		$this->errorCode = '';
		$this->errorMsg  = '';
		$this->avsCode   = '';
		$this->avsMsg    = '';
		$this->authCode  = '';
		$this->transactionid = '';
	}

	/**
	 * Set Approved
	 * @param  bool $approved
	 * @return void
	 */
	public function setApproved(bool $approved = true) : void
	{
		$this->set('approved', $approved);
	}
}
