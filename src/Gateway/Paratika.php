<?php

namespace melih360\ParatikaPosPhp\Gateway;

use melih360\ParatikaPosPhp\Client\HttpClient;
use melih360\ParatikaPosPhp\Exceptions\RequestErrorException;
use melih360\ParatikaPosPhp\Models\Account;
use melih360\ParatikaPosPhp\Models\Card;
use melih360\ParatikaPosPhp\Models\Customer;
use melih360\ParatikaPosPhp\Models\Order;

class Paratika
{
    public string $url;
    protected string $transactionType;
    protected Order $order;
    protected Card $card;
    protected Customer $customer;

    public function __construct(
        protected Account $account = new Account()
    )
    {
        $config = config('paratika');
        $environment = $this->account->getIsTestMode() ? 'test' : 'prod';
        $this->url = $config[$environment]['url'];
    }

    public function prepare($transactionType, Order $order, Card $card, Customer $customer): void
    {
        $this->transactionType = $transactionType;
        $this->order = $order;
        $this->card = $card;
        $this->customer = $customer;
    }

    /**
     * @throws RequestErrorException
     */
    public function getFormData(): array
    {
        $sessionToken = $this->getSessionToken();

        $gateway = $this->url . '/post/sale/' . $sessionToken;

        $cardParameters = $this->card->getCardRequestParameters();
        $orderParameters = $this->order->getOrderRequestParameters();

        $inputs = [];
        $inputs['cardOwner'] = $cardParameters['NAMEONCARD'];
        $inputs['pan'] = $cardParameters['CARDPAN'];
        $inputs['expiryMonth'] = explode('.', $cardParameters['CARDEXPIRY'])[0];
        $inputs['expiryYear'] = explode('.', $cardParameters['CARDEXPIRY'])[1];
        $inputs['cvv'] = $cardParameters['CARDCVV'];
        $inputs['installmentCount'] = $orderParameters['INSTALLMENT'];

        return [
            'gateway' => $gateway,
            'inputs' => $inputs
        ];
    }

    public function get3DFormData(): array
    {
        $sessionToken = $this->getSessionToken();

        $gateway = $this->url . '/post/sale3d/' . $sessionToken;

        $cardParameters = $this->card->getCardRequestParameters();
        $orderParameters = $this->order->getOrderRequestParameters();

        $inputs = [];
        $inputs['cardOwner'] = $cardParameters['NAMEONCARD'];
        $inputs['pan'] = $cardParameters['CARDPAN'];
        $inputs['expiryMonth'] = explode('.', $cardParameters['CARDEXPIRY'])[0];
        $inputs['expiryYear'] = explode('.', $cardParameters['CARDEXPIRY'])[1];
        $inputs['cvv'] = $cardParameters['CARDCVV'];
        $inputs['installmentCount'] = $orderParameters['INSTALLMENT'];

        $paymentFileds = '<input type="hidden" name="cardOwner" value="'.$cardParameters['NAMEONCARD'].'"/>
                                    <input type="hidden" name="pan" value="'.$cardParameters['CARDPAN'].'"/>
                                    <input type="hidden" name="expiryMonth" value="'.$inputs['expiryMonth'].'"/>
                                    <input type="hidden" name="expiryYear" value="'.$inputs['expiryYear'].'"/>
                                    <input type="hidden" name="cvv" value="'.$inputs['cvv'].'"/>';
        $paymentForm = '<form id="3dForm" action="' . $gateway . '" method="POST">' . $paymentFileds . '</form>';

        return [
            'gateway' => $gateway,
            'inputs' => $inputs,
            'sessionToken' => $sessionToken,
            'paymentForm' => $paymentForm
        ];
    }

    /**
     * @throws RequestErrorException
     */
    protected function getSessionToken()
    {
        $parametersToken['ACTION'] = 'SESSIONTOKEN';
        $parametersToken['SESSIONTYPE'] = 'PAYMENTSESSION';

        $accountParameters = $this->account->getMerchantRequestParameters();
        $customerParameters = $this->customer->getCustomerRequestParameters();
        $orderParameters = $this->order->getOrderRequestParametersForSessionToken();

        $parameters = array_merge($parametersToken, $accountParameters, $customerParameters, $orderParameters);

        $requestData = $this->convertToRequestData($parameters);

        $client = new HttpClient();
        $response = $client->makeRequest($this->url, $requestData);

        if ($response->responseCode === '00'){
            return $response->sessionToken;
        } else {
            throw new RequestErrorException($response->errorMsg, $response->responseCode);
        }
    }

    protected function convertToRequestData($parameters): string
    {
        $post_string = "";
        foreach ($parameters as $key => $value) {
            if (!empty($value)){
                $post_string .= "$key=" . urlencode($value) . "&";
            }
        }
        return rtrim($post_string, "& ");
    }
}