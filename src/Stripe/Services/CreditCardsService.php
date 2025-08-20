<?php namespace Dpay\Stripe\Services;
// Stripe
use Stripe\Card as StripeCreditCard;
// DTOs
use Dpay\Data\Charge as ChargeDTO;
use Dpay\Data\CreditCard as CreditCardDTO;
use Dpay\Data\PaymentResponse as Response;
// App Database
use Lib\Logs\Database\CreditCards as ApiCreditCardsTable;
// Stripe Services
use Dpay\Stripe\Api\Services\CreditCards as CreditCardServices;

/**
 * CreditCardsService
 * Service for charge customer setup
 * 
 * @property string    $errorMsg
 * @property Response  $lastResponse
 * @property ChargeDTO $charge
 */
class CreditCardsService extends AbstractService {
	public string $errorMsg;
	public Response $lastResponse;

	protected ChargeDTO $charge;

/* =============================================================
	Public
============================================================= */
	public function process() : bool
	{
		return $this->setupCreditCard();
	}

/* =============================================================
	Internal Processing
============================================================= */
	/**
	 * Create CreditCard
	 * @return bool
	 */
	private function setupCreditCard() : bool
	{
		$this->setChargeCardAidFromLogTable();

		if (empty($this->charge->card->aid) === false) {
			return true;
		}

		if ($this->createCreditCard($this->charge->card) === false) {
			$this->lastResponse = $this->responseCreditCardCreateFailed();
			return false;
		}
		return true;
	}

/* =============================================================
	API Services
============================================================= */
	/**
	 * Create CreditCard
	 * @param  CreditCardDTO $card
	 * @return bool
	 */
	private function createCreditCard(CreditCardDTO $card) : bool
	{
		$SERVICE = new CreditCardServices\CreateCreditCard();
		$SERVICE->setDpayCreditCard($card);
		
		if ($SERVICE->process() === false) {
			$this->errorMsg = $SERVICE->errorMsg;
			return false;
		}

		/** @var StripeCreditCard */
		$sCreditCard = $SERVICE->sCreditCard;

		if (empty($sCreditCard->id)) {
			$this->errorMsg = $SERVICE->errorMsg;
			return false;
		}
		$card->aid = $sCreditCard->id;
		$card->brand = $sCreditCard->brand;
		$this->insertCreditCardLogDb($card);
		return true;
	}

/* =============================================================
	App Database
============================================================= */
	/**
	 * Set Charge Card ID
	 * @return bool
	 */
	private function setChargeCardAidFromLogTable() : bool
	{
		$charge = $this->charge;

		// Validate Customer Exists
		$TABLE	= ApiCreditCardsTable::instance();
		$loggedCard = $TABLE->findOneByCustidLast4($charge->custid, $charge->card->last4());
		if (empty($loggedCard)) {
			return false;
		}
		$charge->card->aid     = $loggedCard->cardid;
		$charge->card->acustid = $loggedCard->acustid;
		return true;
	}

	/**
	 * Insert CreditCard Log Database Record
	 * @param  CreditCardDTO $card
	 * @return bool
	 */
	private function insertCreditCardLogDb(CreditCardDTO $card) : bool
	{
		$TABLE	= ApiCreditCardsTable::instance();
		$r = $TABLE->newRecord();
		$r->custid  = $card->custid;
		$r->acustid = $card->acustid;
		$r->cardid  = $card->aid;
		$r->last4   = $card->last4();
		$r->issuer  = $card->brand;
		return $TABLE->insert($r);
	}

/* =============================================================
	Responses
============================================================= */
	/**
	 * Return that CreditCard was not found
	 * @return Response
	 */
	private function responseCreditCardNotFound() : Response
	{
		$rqst = $this->rqst;
		$response = new Response();
		$response->ordn = $rqst->getOrdernbr();
		$response->setApproved(false);
		$response->errorMsg = "Can't find CreditCard " . $rqst->getCustid() . " in database";
		return $response;
	}

	/**
	 * Return that API call is not set up
	 * @return Response
	 */
	private function responseCreditCardCreateFailed() : Response
	{
		$rqst = $this->rqst;
		$response = new Response();
		$response->ordn = $rqst->getOrdernbr();
		$response->setApproved(false);
		$response->errorMsg = "Can't create Stripe CreditCard for " . $rqst->getCustid();
		if ($this->errorMsg) {
			$response->errorMsg = $this->errorMsg;
		}
		return $response;
	}
}
