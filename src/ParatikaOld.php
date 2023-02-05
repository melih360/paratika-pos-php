<?php

namespace Umuttaymaz\ParatikaPhp;

class ParatikaOld
{
    protected mixed $config;

    public function __construct(
        protected string $merchant,
        protected string $merchantUser,
        protected string $merchantPassword,
        protected ?bool  $testMode = true,
    )
    {
        $this->config = require __DIR__ . '/../config/paratika.php';
    }

    public function payment()
    {
        $sessionToken = $this->getSessionToken();

        $parametersMap["SESSIONTOKEN"] = $sessionToken['token'];
        $parametersMap["ACTION"] = 'SALE';
        $parametersMap["AMOUNT"] = '1200';
        $parametersMap['CURRENCY'] = 'TRY';
        $parametersMap['MERCHANTPAYMENTID'] = uniqid('MPID-', true);
        $parametersMap = $this->addMerchantAuthParams($parametersMap);
        $parametersMap = $this->addCardAndCustomerParams($parametersMap);
        $parametersMap = $this->addBillToAndShipToParams($parametersMap);

        return $parametersMap;
    }

    protected function getSessionToken()
    {
        $parametersMap = array();
        $parametersMap['ACTION'] = 'SESSIONTOKEN';
        $parametersMap['MERCHANTPAYMENTID'] = uniqid('MPID-', true);
        $parametersMap['AMOUNT'] = '1200';
        $parametersMap['CURRENCY'] = 'TRY';
        $parametersMap['SESSIONTYPE'] = 'PAYMENTSESSION';
        $parametersMap['RETURNURL'] = 'https://test.paratika.com.tr/paratika/index.jsp';
        $parametersMap = $this->addMerchantAuthParams($parametersMap);
        $parametersMap = $this->addCardAndCustomerParams($parametersMap);
        $parametersMap = $this->addBillToAndShipToParams($parametersMap);
        $parametersMap = $this->addOrderItemsParams($parametersMap);

        $requestData = $this->convertToRequestData($parametersMap);

        $tokenResponse = $this->createRequest($requestData);

        switch ($tokenResponse->responseCode)
        {
            case '00':
                return [
                    'status' => 'success',
                    'code' => $tokenResponse->responseCode,
                    'token' => $tokenResponse->sessionToken
                ];
            case '99':
            case '98':
                return [
                    'status' => 'error',
                    'code' => $tokenResponse->responseCode,
                    'message' => $tokenResponse->responseMsg,
                    'detail' => $tokenResponse
                ];
        }
    }

    protected function createRequest($requestData)
    {
        $request = curl_init(); // create a new cURL resource
        curl_setopt($request, CURLOPT_URL, $this->getUrl()); // initiate curl object
        curl_setopt($request, CURLOPT_HEADER, false); // exclude header info from response
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true); // return the response as a string when executed
        curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', "Expect:"));
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // stop cURL from verifying peer's certificate
        curl_setopt($request, CURLOPT_POSTFIELDS, $requestData); // the data to post in HTTP post operation
        $post_response = curl_exec($request); // execute the given cURL session
        curl_close($request); // close the cURL session and free all resources

        return json_decode($post_response);
    }

    protected function convertToRequestData($parametersMap): string
    {
        $post_string = "";
        foreach ($parametersMap as $key => $value) {
            $post_string .= "$key=" . urlencode($value) . "&";
        }
        return rtrim($post_string, "& ");
    }

    protected function addMerchantAuthParams($parametersMap): array
    {
        $parametersMap['MERCHANT'] = $this->merchant;
        $parametersMap['MERCHANTUSER'] = $this->merchantUser;
        $parametersMap['MERCHANTPASSWORD'] = $this->merchantPassword;
        return $parametersMap;
    }

    private function addCardAndCustomerParams($parametersMap)
    {
        $parametersMap['CUSTOMER'] = uniqid('MPID-', true);
        $parametersMap['CUSTOMERNAME'] = 'Aydonat Aydınlar';
        $parametersMap['CUSTOMEREMAIL'] = 'mghUzjPn@email.com';
        $parametersMap['CUSTOMERIP'] = '127.0.0.1';
        $parametersMap['CUSTOMERPHONE'] = '+903120000011';
        $parametersMap['CUSTOMERBIRTHDAY'] = '19-04-1969';
        $parametersMap['CUSTOMERUSERAGENT'] = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0';
        $parametersMap['NAMEONCARD'] = 'Aydonat Aydınlar';
        $parametersMap['CARDPAN'] = '4022774022774026';
        $parametersMap['CARDEXPIRY'] = '12.2030';
        $parametersMap['CARDCVV'] = '000';
        return $parametersMap;
    }

    private function addBillToAndShipToParams($parametersMap)
    {
        $parametersMap['BILLTOADDRESSLINE'] = 'Çöişüğı Plaza, Ostim No:83/9, Yenimahalle';
        $parametersMap['BILLTOCITY'] = 'Ankara';
        $parametersMap['BILLTOCOUNTRY'] = 'Türkiye';
        $parametersMap['BILLTOPOSTALCODE'] = '06000';
        $parametersMap['BILLTOPHONE'] = '+903120000001';
        $parametersMap['SHIPTOADDRESSLINE'] = 'Plaza ÇÖİŞÜĞI, Ostim No:83/9, Yenimahalle';
        $parametersMap['SHIPTOCITY'] = 'Ankara';
        $parametersMap['SHIPTOCOUNTRY'] = 'Türkiye';
        $parametersMap['SHIPTOPOSTALCODE'] = '06000';
        $parametersMap['SHIPTOPHONE'] = '+903120000001';
        return $parametersMap;
    }

    private function addOrderItemsParams($parametersMap)
    {
        // first item
        $item = array();
        $item["code"] = "T00D3AITCC";
        $item["name"] = "Galaxy S8+";
        $item["quantity"] = "1";
        $item["description"] = "The Samsung Galaxy S8 is Android smartphone produced by Samsung Electronics as part of the Samsung Galaxy S series.";
        $item["amount"] = "1000";
        // second item
        $item2 = array();
        $item2["code"] = "T00F2IONA";
        $item2["name"] = "HP - 14\" Chromebook";
        $item2["quantity"] = "1";
        $item2["description"] = "Intel Celeron - 4GB Memory - 16GB eMMC Flash Memory - Silver.";
        $item2["amount"] = "200";
        // put two items inside another array
        $oItems = array($item, $item2);
        // convert to json format
        $items = json_encode($oItems);
        // add to parametersMap by applying urlencode to items first
        $parametersMap['ORDERITEMS'] = urlencode($items);
        return $parametersMap;
    }

    protected function getUrl()
    {
        $env = $this->testMode ? 'test' : 'prod';

        return 'https://test.paratika.com.tr/paratika/api/v2';
        //return $this->config['test'][$env];
    }
}