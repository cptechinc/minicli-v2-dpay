<?php namespace Dpay\AuthorizeNet;
// Authorize.Net SDK
use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\CreateTransactionResponse;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\CreateTransactionController;

/**
 * RequestChargeTransaction
 * 
 * Sends Charge Request to API
 * 
 * @method CreateTransactionResponse|null getResponse()
 * 
 * @property bool                        $useSandbox
 * @property MerchantAuthenticationType  $authentication
 * @property CreateTransactionRequest    $request
 * @property CreateTransactionResponse   $response
 * @property TransactionRequestType      $transaction
 */
class RequestChargeTransaction extends AbstractRequest {
	protected MerchantAuthenticationType $authentication;
	protected TransactionRequestType $transaction;
	protected TransactionRequestType $request;
	protected CreateTransactionResponse $response;

	public function __construct(MerchantAuthenticationType $auth, TransactionRequestType $tran, $useSandbox = false) {
		$this->authentication = $auth;
		$this->transaction    = $tran;
		$this->useSandbox     = $useSandbox;
	}

/* =============================================================
	Authorize.Net SDK
============================================================= */
	protected function createRequest() : CreateTransactionRequest
	{
		$t = new CreateTransactionRequest();
		$t->setMerchantAuthentication($this->authentication);
		$t->setRefId('ref' . time());
		$t->setTransactionRequest($this->transaction);
		return $t;
	}

	protected function createRequestController() : CreateTransactionController {
		return new CreateTransactionController($this->request);
	}
}