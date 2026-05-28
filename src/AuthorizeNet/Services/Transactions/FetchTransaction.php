<?php namespace Dpay\AuthorizeNet\Services\Transactions;
// Authorize.Net Library
use net\authorize\api\contract\v1\GetTransactionDetailsResponse as ANetTransactionResponse;
// Dpay
use Dpay\AuthorizeNet\Services\AbstractService;
use Dpay\AuthorizeNet\Services\Charges\Util\ChargeStatus;
use Dpay\AuthorizeNet\Services\Transactions\Data\Transaction as TrResponse;

/**
 * Service to Fetch Transaction Data using Authorize.Net API
 * 
 * @property string 	     $id		   Transaction ID
 * @property TrResponse      $response
 */
class FetchTransaction extends AbstractService {
    public string $id = '';
    public TrResponse $response;
    protected ANetTransactionResponse $aResponse;

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getResponseData() : TrResponse
    {
        return $this->response;
    }

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
            $this->errorMsg = "Transaction ID was not provided";
            return false;
        }
        $transaction = $this->fetchTransaction($this->id);

        if (empty($transaction)) {
            if ($this->errorMsg) {
                return false;
            }
            $this->errorMsg = "Unable to find transaction $this->id";
            return false;
        }
        if ($transaction->success === false) {
            $this->errorMsg = $transaction->errorMsg;
            return false;
        }
        $this->response = $transaction;
        return true;
    }

/* =============================================================
    Internal Processing
============================================================= */
    protected function fetchTransaction(string $id) : TrResponse
    {
        $response = $this->api()->fetchTransactionDetails($this->id);
        $this->aResponse = $response;
        return $this->processTransactionResponse($response);
    }

    protected function processTransactionResponse(?ANetTransactionResponse $apiResponse = null) : TrResponse
    {
        $response = new TrResponse();

        if ($apiResponse === null) {
            $response->exists  = false;
            $response->errorMsg = 'Transaction not found';
            return $response;
        }

        if ($apiResponse->getMessages()->getResultCode() != "Ok") {
            $message = $apiResponse->getMessages()->getMessage()[0];
            $response->exists  = false;
            $response->errorCode = $message->getCode();
            $response->errorMsg  = 'ANET: ' . $message->getText();
            if ($response->errorCode === 'E00040') {
                $response->errorMsg = 'ANET: Transaction not found';
            }
            return $response;
        }

        $tDetails = $apiResponse->getTransaction();

        if ($tDetails === null) {
            $response->exists  = false;
            $response->errorMsg = 'Transaction not found';
            return $response;
        }
        $response->exists = true;
        $response->id = $tDetails->getTransId();
        $response->status = ChargeStatus::find($tDetails->getTransactionStatus())->value;
        $response->type = $tDetails->getTransactionType();
        return $response;
    }

}