<?php

namespace Umuttaymaz\ParatikaPhp\Models;

class Account
{
    public function __construct(
        protected string $merchant,
        protected string $merchantUser,
        protected string $merchantPassword,
        protected bool  $testMode = false
    )
    {

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