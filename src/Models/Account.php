<?php

namespace melih360\ParatikaPosPhp\Models;

class Account
{
    protected string $merchant;
    protected string $merchantUser;
    protected string $merchantPassword;
    protected bool $testMode;

    public function __construct()
    {
        $this->merchant = config('paratika.merchant');
        $this->merchantUser = config('paratika.merchantUser');
        $this->merchantPassword = config('paratika.merchantPassword');
        $this->testMode = config('paratika.testMode', false);
    }

    public function getIsTestMode(): bool
    {
        return $this->testMode;
    }

    public function getMerchantRequestParameters(): array
    {
        $merchantParameters = [];

        $merchantParameters['MERCHANT'] = $this->merchant;
        $merchantParameters['MERCHANTUSER'] = $this->merchantUser;
        $merchantParameters['MERCHANTPASSWORD'] = $this->merchantPassword;

        return $merchantParameters;
    }
}