<?php namespace Dpay\PayTrace\Services\Charges;
// Dpay
use Dpay\Abstracts\Api\Services\Charges\ACrudChargeTraits;
use Dpay\Data\Charge as DpayCharge;
use Dpay\PayTrace\AbstractService;
use Dpay\PayTrace\Config;
use Dpay\PayTrace\Requests;
use Dpay\PayTrace\Services\Charges\Data\ChargeResponse;
use Dpay\Util\ChargeStatus;
use Dpay\Util\Data\HttpResponse;


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
	const API_SUCCESS_RESPONSE_CODES = [];
	const ENDPOINT = '';

	public string $id = '';
	public ChargeResponse $response;
	protected DpayCharge $dpayCharge;
	
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
		$this->id	   = $charge->transactionid;
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
		$data->status          = ChargeStatus::None;
		
		if (array_key_exists($charge->responseCode, static::API_SUCCESS_RESPONSE_CODES)) {
			$data->status = $this->getSuccessfulChargeStatus()->value;
		}
		return $data;
	}

	protected function getSuccessfulChargeStatus() : ChargeStatus
	{
		return ChargeStatus::None;
	}

/* =============================================================
	Contracts
============================================================= */
	/**
	 * Generate Credit Charge Request Data
	 * @param  DpayCharge $charge
	 * @return array
	 */
	protected function generateChargeRequest(DpayCharge $charge) : array
	{
		return [
			'integrator_id' => Config::instance()->integratorID,
			'invoice_id'    => $charge->ordernbr,
			"amount"        => $charge->amount,
			"credit_card"=> [
				 "number"           => $charge->card->cardnbr,
				 "expiration_month" => $charge->card->expiredateMonth(),
				 "expiration_year"  => $charge->card->expiredateYear()
			],
			"csc"             => $charge->card->cvc,
			"billing_address" => [
				"name"           => $charge->card->name,
				"street_address" => $charge->card->address1,
				"city"           => "",
				"state"          => "",
				"zip"            => $charge->card->zipcode
			]
		];
	}

    /**
     * Creates PayTrace Credit Charge
     * @return ChargeResponse
     */
	protected function processCharge() : ChargeResponse
    {
        $data = $this->generateChargeRequest($this->dpayCharge);
        $request = new Requests\TransactionRequest($this->token);
        $response = $request->post(static::ENDPOINT, $data);
        return $this->processHttpResponse($response);
    }

	protected function processHttpResponse(HttpResponse $response) : ChargeResponse
	{
		$charge = new ChargeResponse();

		if (array_key_exists('transaction_id', $response->jsonData)) {
			$charge->transactionid = $response->jsonData['transaction_id'];
		}

		if (array_key_exists('approval_code', $response->jsonData)) {
			$charge->authCode = $response->jsonData['approval_code'];
		}

		if (array_key_exists('response_code', $response->jsonData)) {
			$charge->responseCode = $response->jsonData['response_code'];
		}

		if ($response->jsonData['success'] && array_key_exists('transaction_id', $response->jsonData)) {
			$charge->success = false;
			$charge->errorMsg = 'No response';
			return $charge;
		}

		if ($response->jsonData['success'] === false) {
            $this->processHttpResponseError($response, $charge);
			return $charge;
        }
		$charge->success = true;
		return $charge;
	}

	protected function processHttpResponseError(HttpResponse $response, ChargeResponse $charge) : void
	{
		$json = $response->jsonData;
		$charge->success = false;

		if (array_key_exists('errors', $json)) {
			$errors = $json['errors'];
			$codes  = array_keys($errors);
			$msgs   = array_values($errors);
			$charge->errorMsg  = $msgs[0][0];
			$charge->errorCode = $codes[0][0];
			return;
		}
		$errorCode = array_key_exists('response_code', $json) ? $json['response_code'] : 404;
		$msg  = array_key_exists('status_message', $json) ? $json['status_message'] : 'No Response';
		$charge->errorMsg  = $msg;
		$charge->errorCode = $errorCode;
		return;
	}
}