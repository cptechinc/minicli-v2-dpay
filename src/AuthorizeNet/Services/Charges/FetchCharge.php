<?php namespace Dpay\AuthorizeNet\Services\Charges;
// Authorize.net Library
use net\authorize\api\contract\v1\GetTransactionDetailsResponse as ANetResponse;
use net\authorize\api\contract\v1\TransactionDetailsType as ANetTransaction;
// Lib
use Dpay\Abstracts\Api\Services\Charges\FetchChargeInterface;
use Dpay\AuthorizeNet\Services\Charges\Data\ChargeResponse;
use Dpay\AuthorizeNet\Services\Charges\Util\ChargeStatus;
use Dpay\Data\Charge as DpayCharge;

/**
 * Service to Get Charge from Authorize.net API
 * 
 * @property string          $id          Charge ID / URL
 * @property DpayCharge      $dpayCharge  Dpay Charge
 * @property ANetTransaction $aCharge
 * @property ChargeResponse  $response
 */
class FetchCharge extends AbstractCrudCharge implements FetchChargeInterface {
	public ChargeResponse $response;
	protected DpayCharge $dpayCharge;
	protected ANetTransaction $aCharge;

/* =============================================================
	Interface Contracts
============================================================= */
	/**
	 * Process Request
	 * @return bool
	 */
	public function process() : bool
	{
		if (empty($this->id)) {
			return false;
		}
		$charge = $this->processCharge();

		if (empty($charge)) {
			if ($this->errorMsg) {
				return false;
			}
			$this->errorMsg = "Unable to " . static::ACTION . " Credit Charge {$this->dpayCharge->custid}";
			return false;
		}
		if ($charge->success === false) {
			$this->errorMsg = $charge->errorMsg;
			return false;
		}
		$this->response = $charge;
		$this->id	    = $charge->transactionid;
		return true;
	}

	/**
	 * Return Response data as Dpay Charge
	 * @return DpayCharge
	 */
	public function getDpayChargeResponseData() : DpayCharge
	{
		$apiCharge = $this->aCharge;

		$data = new DpayCharge();
		$data->transactionid = $this->response->transactionid;
		$data->custid        = $apiCharge->getCustomer()->getId();
		$data->amount        = $apiCharge->getAuthAmount();
		$data->transactiontype = static::ACTION;
		$data->ordernbr        = $apiCharge->getOrder()->getInvoiceNumber();
		$data->status          = ChargeStatus::find($apiCharge->getTransactionStatus())->value;
		return $data;
	}

/* =============================================================
	Internal Processing
============================================================= */
	protected function processCharge() : ChargeResponse
	{
		$response = $this->api()->fetchTransactionDetails($this->id);
		return $this->processTransactionDetailsResponse($response);
	}

	protected function processTransactionDetailsResponse(ANetResponse $apiResponse = null) : ChargeResponse
	{
		$response = new ChargeResponse();

		if ($apiResponse === null) {
			$response->success = false;
			$response->errorMsg = 'No Response';
			return $response;
		}

		if ($apiResponse->getMessages()->getResultCode() != "Ok") {
			return $this->processTransactionDetailsResponseError($apiResponse);
		}
		$transaction = $apiResponse->getTransaction();
		$this->aCharge = $transaction;

		$response->success = true;
		$response->transactionid = $transaction->getTransId();
		$response->authCode      = $transaction->getAuthCode();
		$response->status        = $transaction->getTransactionStatus();
		return $response;
	}

	protected function processTransactionDetailsResponseError(ANetResponse $apiResponse) : ChargeResponse
	{
		$response = new ChargeResponse();
		$response->success = false;

		$transaction = $apiResponse->getTransaction();

		if ($transaction === null) {
			$response->errorCode = $apiResponse->getMessages()->getMessage()[0]->getCode();
			$response->errorMsg  = 'ANET: Transaction not found';
			return $response;
		}
		return $response;
	}

}