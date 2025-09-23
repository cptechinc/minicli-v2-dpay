<?php namespace Dpay\AuthorizeNet\Api\Services\Refunds;
// AuthorizeNet Library
use net\authorize\api\contract\v1\TransactionRequestType as ANetTransactionRequest;
use net\authorize\api\contract\v1\CreateTransactionResponse as ANetResponse;
// Dpay
use Dpay\Abstracts\Api\Services\Refunds\ACrudRefundTraits;
use Dpay\AuthorizeNet\Api\Services\AbstractService;
use Dpay\Data\Refund as DpayRefund;
use Dpay\AuthorizeNet\Api\Services\Refunds\Data\RefundResponse;
use Dpay\AuthorizeNet\Api\Services\Charges\Util\TransactionData;
use Dpay\Util\ChargeStatus;

/**
 * Service to Refund using Authorize.net API
 * 
 * @property string 	     $id		   API Refund ID
 * @property DpayRefund		 $dpayRefund   Dpay Refund (Request)
 * @property RefundResponse  $response     Refund Response
 */
abstract class AbstractCrudRefund extends AbstractService {
	use ACrudRefundTraits;

	const ACTION = 'refund';
    const ANET_TRANSACTION_TYPE = 'refundTransaction';
    const ANET_TRANSACTION_MESSAGE_ERROR_CODES = [
		'311' => 'This transaction has already been captured'
	];
    const REFUND_STATUS_ON_SUCCESS = 'refunded';

	protected string $id;
	public RefundResponse $response;
	protected DpayRefund $dpayRefund;
	
/* =============================================================
	Inits @see ACrudRefundTraits
============================================================= */
	
/* =============================================================
	Interface Contracts @see ACrudRefundTraits
============================================================= */
	/**
	 * Process Request
	 * @return bool
	 */
	public function process() : bool
	{
		if ($this->initDpayRefund() === false) {
			return false;
		}
		$refund = $this->processRefund();

		if (empty($refund)) {
			if ($this->errorMsg) {
				return false;
			}
			$this->errorMsg = "Unable to " . static::ACTION . " Credit Charge";
			return false;
		}
		if ($refund->success === false) {
			$this->errorMsg = $refund->errorMsg;
			return false;
		}
		$this->response = $refund;
		$this->id	    = $refund->transactionid;
		return true;
	}

	/**
	 * Return Response data as Dpay Refund
	 * @return DpayRefund
	 */
	public function getDpayRefundResponseData() : DpayRefund
	{
		$refund = $this->response;

		$data = new DpayRefund();
		$data->refundid       = $refund->transactionid;
		$data->transactionid  = $refund->transactionid;
		$data->amount         = $this->dpayRefund->charge->amount;
		$data->status         = $refund->status;
        $data->charge->transactionid = $refund->transactionid;
		return $data;
	}

/* =============================================================
	Contracts
============================================================= */
    protected function processRefund() : RefundResponse
	{
		$data = $this->createTransactionRequest();
		$response = $this->api()->sendChargeTransaction($data);
		return $this->processTransactionResponse($response);
	}

    protected function createTransactionRequest() : ANetTransactionRequest
	{
		$rqst = new ANetTransactionRequest();
		$rqst->setTransactionType(static::ANET_TRANSACTION_TYPE);
		$rqst->setRetail(TransactionData::transRetailInfoType($this->dpayRefund->charge));
		$rqst->addToTransactionSettings(TransactionData::settingType());

		if ($this->dpayRefund->transactionid) {
			$rqst->setRefTransId($this->dpayRefund->transactionid);
		}
		return $rqst;
	}

    protected function processTransactionResponse(ANetResponse $apiResponse = null) : RefundResponse
	{	
		$response = new RefundResponse();

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
		$response->status        = ChargeStatus::Refunded;
		return $response;
	}

	protected function processTransactionResponseError(ANetResponse $apiResponse) : RefundResponse
	{
		$response = new RefundResponse();
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
}