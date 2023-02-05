<?php

namespace Umuttaymaz\ParatikaPhp\Models;

class Order
{
    protected array $billingInformation = [];
    protected array $shippingInformation = [];
    protected array $orderItems = [];

    public function __construct(
        protected string $order_id,
        protected string $amount,
        protected string $currency,
        protected string $returnURL
    )
    {

    }

    public function addBillingInformation($addressLine, $city, $country, $postalCode, $phone): void
    {
        $billingInformation = [
            'BILLTOADDRESSLINE' => $addressLine,
            'BILLTOCITY' => $city,
            'BILLTOCOUNTRY' => $country,
            'BILLTOPOSTALCODE' => $postalCode,
            'BILLTOPHONE' => $phone,
        ];

        $this->billingInformation = $billingInformation;
    }

    public function addShippingInformation($addressLine, $city, $country, $postalCode, $phone): void
    {
        $shippingInformation = [
            'SHIPTOADDRESSLINE' => $addressLine,
            'SHIPTOCITY' => $city,
            'SHIPTOCOUNTRY' => $country,
            'SHIPTOPOSTALCODE' => $postalCode,
            'SHIPTOPHONE' => $phone,
        ];

        $this->shippingInformation = $shippingInformation;
    }

    public function addOrderItem($code, $name, $description, $quantity, $amount): void
    {
        $orderItem = [
            'code' => $code,
            'name' => $name,
            'description' => $description,
            'quantity' => $quantity,
            'amount' => $amount
        ];

        $this->orderItems[] = $orderItem;
    }

    public function getOrderRequestParameters(): array
    {
        $order = [
            'MERCHANTPAYMENTID' => $this->order_id,
            'AMOUNT' => $this->amount,
            'CURRENCY' => $this->currency,
            'RETURNURL' => $this->returnURL,
            'ORDERITEMS' => urlencode(json_encode($this->orderItems))
        ];

        return array_merge($this->billingInformation, $this->shippingInformation, $order);
    }
}