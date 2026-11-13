<?php namespace Dpay\AuthorizeNet\Services\Refunds;

// AuthorizeNet Library
use net\authorize\api\contract\v1\TransactionRequestType as ANetTransactionRequest;
use net\authorize\api\contract\v1\CreateTransactionResponse as ANetResponse;
// Dpay
use Dpay\Abstracts\Api\Services\Refunds\ACrudRefundTraits;
use Dpay\AuthorizeNet\Services\AbstractService;
use Dpay\Data\Refund as DpayRefund;
use Dpay\AuthorizeNet\Services\Refunds\Data\RefundResponse;
use Dpay\AuthorizeNet\Services\Charges\Util\TransactionData;
use Dpay\Util\ChargeStatus;

/**
 * Service to Refund using Authorize.net API
 *
 * @property string 	     $id		   API Refund ID
 * @property DpayRefund		 $dpayRefund   Dpay Refund (Request)
 * @property RefundResponse  $response     Refund Response
 */
abstract class AbstractCrudRefund extends AbstractService
{
    use ACrudRefundTraits;

    public const ACTION = 'refund';
    public const ANET_TRANSACTION_TYPE = 'refundTransaction';
    public const ANET_TRANSACTION_MESSAGE_ERROR_CODES = [
        '311' => 'This transaction has already been refunded'
    ];
    public const REFUND_STATUS_ON_SUCCESS = 'refunded';

    protected string $id;
    public RefundResponse $response;
    protected DpayRefund $dpayRefund;

    protected ANetResponse|null $apiResponse = null;
    protected ANetTransactionRequest|null $apiRequest = null;

    /* =============================================================
        Inits @see ACrudRefundTraits
    ============================================================= */
    /* =============================================================
        Interface Contracts @see ACrudRefundTraits
    ============================================================= */
    /**
     * Process Request
     */
    public function process(): bool
    {
        if ($this->initDpayRefund() === false) {
            return false;
        }
        $refund = $this->processRefund();
        $this->response = $refund;
        $this->id	    = $refund->transactionid;

        if (empty($refund)) {
            if ($this->errorMsg) {
                return false;
            }
            $this->errorMsg = 'Unable to ' . static::ACTION . ' Credit Charge';
            return false;
        }
        if ($refund->success === false) {
            $this->errorMsg = $refund->errorMsg;
            return false;
        }
        return true;
    }

    /**
     * Return Response data as Dpay Refund
     */
    public function getDpayRefundResponseData(): DpayRefund
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

    public function getResponseData(): RefundResponse
    {
        return $this->response;
    }

    public function getApiRequest(): ANetTransactionRequest|null
    {
        return $this->apiRequest;
    }

    public function getApiResponse(): ANetResponse|null
    {
        return $this->apiResponse;
    }

    /* =============================================================
        Contracts
    ============================================================= */
    protected function processRefund(): RefundResponse
    {
        $this->apiRequest = $this->createTransactionRequest();
        $this->apiResponse = $this->api()->sendChargeTransaction($this->apiRequest);
        return $this->processTransactionResponse($this->apiResponse);
    }

    protected function createTransactionRequest(): ANetTransactionRequest
    {
        $rqst = new ANetTransactionRequest();
        $rqst->setTransactionType(static::ANET_TRANSACTION_TYPE);
        $rqst->setRetail(TransactionData::transRetailInfoType($this->dpayRefund->charge));
        $rqst->addToTransactionSettings(TransactionData::settingTypeDuplicateWindow());

        if ($this->dpayRefund->transactionid) {
            $rqst->setRefTransId($this->dpayRefund->transactionid);
        }
        return $rqst;
    }

    protected function processTransactionResponse(?ANetResponse $apiResponse = null): RefundResponse
    {
        $response = new RefundResponse();

        if (!$apiResponse instanceof ANetResponse) {
            $response->success = false;
            $response->errorMsg = 'No Response';
            return $response;
        }

        if ($apiResponse->getMessages()->getResultCode() != 'Ok') {
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
        $response->status        = ChargeStatus::Refunded->value;
        return $response;
    }

    protected function processTransactionResponseError(ANetResponse $apiResponse): RefundResponse
    {
        $response = new RefundResponse();
        $response->success = false;

        $tresponse = $apiResponse->getTransactionResponse();

        if ($tresponse === null || $tresponse->getMessages() === null) {
            $response->errorCode = $apiResponse->getMessages()->getMessage()[0]->getCode();
            $response->errorMsg  = $apiResponse->getMessages()->getMessage()[0]->getText();
            return $response;
        }

        $errors = $tresponse->getErrors();

        if ($errors) {
            $response->errorCode = $errors[0]->getErrorCode();
            $response->errorMsg  = $errors[0]->getErrorText();
            return $response;
        }

        if ($tresponse->getTransId() == 0) {
            foreach ($tresponse->getMessages() as $code => $message) {
                if (array_key_exists((string) $code, array_keys(static::ANET_TRANSACTION_MESSAGE_ERROR_CODES))) {
                    $response->errorCode = $code;
                    $response->errorMsg  = $message->getDescription();
                    return $response;
                }
            }
        }
        return $response;
    }
}
