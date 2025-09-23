<?php namespace Dpay\AuthorizeNet\Api\Services\Charges;
// AuthorizeNet Library
use net\authorize\api\contract\v1\TransactionRequestType as ANetTransactionRequest;
use net\authorize\api\contract\v1\CreateTransactionResponse as ANetResponse;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\ACrudChargeTraits;
use Dpay\AuthorizeNet\Api\Services\AbstractService;
use Dpay\AuthorizeNet\Api\Services\Charges\Util\TransactionData;
use Dpay\AuthorizeNet\Api\Services\Charges\Data\ChargeResponse;
use Dpay\Data\Charge as DpayCharge;
use Dpay\Util\ChargeStatus;

/**
 * AbstractCrudCharge
 * Service to Create Credit Charge using Stripe API
 * 
 * @property string 	     $id		   API Charge ID
 * @property DpayCharge		 $dpayCharge   Dpay Credit Charge (Request)
 * @property ChargeResponse  $response
 */
abstract class AbstractCrudCharge extends AbstractService {
	use ACrudChargeTraits;

	const ACTION = 'update';
	const ANET_TRANSACTION_TYPE = '';
	const ANET_TRANSACTION_MESSAGE_ERROR_CODES = [
		'311' => 'This transaction has already been captured'
	];

	public string $id = '';
	protected DpayCharge $dpayCharge;
	protected ChargeResponse $response;
	
/* =============================================================
	Inits @see ACrudChargeTraits
============================================================= */
	
	
/* =============================================================
	Interface Contracts @see ACrudChargeTraits
============================================================= */
	/**
	 * Process Request
	 * @return bool
	 */
	public function process() : bool
	{
		if ($this->initDpayCharge() === false) {
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
		$charge = $this->response;

		$data = new DpayCharge();
		$data->transactionid = $charge->transactionid;
		$data->custid        = $this->dpayCharge->custid;
		$data->amount        = $this->dpayCharge->amount;
		$data->transactiontype = static::ACTION;
		$data->ordernbr        = $this->dpayCharge->ordernbr;
		$data->status          = $charge->status;
		return $data;
	}

/* =============================================================
	Contracts
============================================================= */
    protected function processCharge() : ChargeResponse
	{
		$data = $this->createTransactionRequest();
		$response = $this->api()->sendChargeTransaction($data);
		return $this->processTransactionResponse($response);
	}

	protected function createTransactionRequest() : ANetTransactionRequest
	{
		$rqst = new ANetTransactionRequest();
		$rqst->setTransactionType(static::ANET_TRANSACTION_TYPE);
		$rqst->setRetail(TransactionData::transRetailInfoType($this->dpayCharge));
		$rqst->addToTransactionSettings(TransactionData::settingType());

		if ($this->dpayCharge->transactionid) {
			$rqst->setRefTransId($this->dpayCharge->transactionid);
		}
		return $rqst;
	}

	protected function processTransactionResponse(ANetResponse $apiResponse = null) : ChargeResponse
	{
		$response = new ChargeResponse();

		if ($apiResponse === null) {
			$response->success = false;
			$response->errorMsg = 'No Response';
			return $response;
		}

		if ($apiResponse->getMessages()->getResultCode() != "Ok") {
			return $this->processTransactionResponseError($apiResponse);
		}
		$tranResponse = $apiResponse->getTransactionResponse();

		if ($tranResponse->getTransId() == 0) {
			return $this->processTransactionResponseError($apiResponse);
		}

		$response->success = true;
		$response->transactionid = $tranResponse->getTransId();
		$response->authCode      = $tranResponse->getAuthCode();
		$response->avsCode       = $tranResponse->getAvsResultCode();
		$response->status        = $this->getSuccessfulChargeStatus()->value;
		return $response;
	}

	protected function processTransactionResponseError(ANetResponse $apiResponse) : ChargeResponse
	{
		$response = new ChargeResponse();
		$response->success = false;

		$tresponse = $apiResponse->getTransactionResponse();

		if ($tresponse === null || $tresponse->getMessages() === null) {
			$response->errorCode = $apiResponse->getMessages()->getMessage()[0]->getCode();
			$response->errorMsg  = 'ANET: ' .$apiResponse->getMessages()->getMessage()[0]->getText();
			return $response;
		}

		$errors = $tresponse->getErrors();

		if ($errors) {
			$response->errorCode = $errors[0]->getErrorCode();
			$response->errorMsg  = 'ANET: ' . $errors[0]->getErrorText();
			return $response;
		}

		if ($tresponse->getTransId() == 0) {
			foreach ($tresponse->getMessages() as $code => $message) {
				if (array_key_exists($code, array_keys(static::ANET_TRANSACTION_MESSAGE_ERROR_CODES))) {
					$response->errorCode = $code;
					$response->errorMsg  = 'ANET: ' .$message->getDescription();
					return $response;
				}
			}
		}
		return $response;
	}

	protected function getSuccessfulChargeStatus() : ChargeStatus
	{
		return ChargeStatus::None;
	}
}