<?php namespace Dpay\AuthorizeNet;
// AuthorizeNet Library
use net\authorize\api\contract\v1\GetTransactionDetailsRequest;
use net\authorize\api\contract\v1\GetTransactionDetailsResponse;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\controller\GetTransactionDetailsController;

/**
 * RequestTransactionDetails
 * 
 * Request Transaction Data from API
 * 
 * @method GetTransactionDetailsResponse getResponse()
 * 
 * @property bool                          $useSandbox
 * @property MerchantAuthenticationType    $authentication
 * @property GetTransactionDetailsRequest  $request
 * @property GetTransactionDetailsResponse $response
 * @property string                        $transactionid   Transaction ID
 */
class RequestTransactionDetails extends AbstractRequest {
	const TRANSACTION_STATUSES = [
		'pending-settlement' => 'capturedPendingSettlement'
	];
	
	protected string $transactionid;
	
	public function __construct(MerchantAuthenticationType $auth, string $transactionid, $useSandbox = false) {
		$this->authentication = $auth;
		$this->transactionid  = $transactionid;
		$this->useSandbox     = $useSandbox;
	}

/* =============================================================
	Authorize.Net SDK
============================================================= */
	protected function createRequest() : GetTransactionDetailsRequest
	{
		$t = new GetTransactionDetailsRequest();
		$t->setMerchantAuthentication($this->authentication);
		$t->setRefId('ref' . time());
		$t->setTransid($this->transactionid);
		return $t;
	}

	protected function createRequestController() : GetTransactionDetailsController {
		return new GetTransactionDetailsController($this->request);
	}
}