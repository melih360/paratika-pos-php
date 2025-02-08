<?php

namespace melih360\ParatikaPosPhp\Models;

class Card
{
    public function __construct(
        protected string $cardHolderName,
        protected string $cardNumber,
        protected string $cardExpirationYear,
        protected string $cardExpirationMonth,
        protected string $cardCvv
    )
    {

    }

    public function getCardRequestParameters(): array
    {
        $cardParameters = [];

        $cardParameters['NAMEONCARD'] = $this->cardHolderName;
        $cardParameters['CARDPAN'] = $this->formatNumber($this->cardNumber);
        $cardParameters['CARDEXPIRY'] = $this->getExpireDate($this->cardExpirationMonth, $this->cardExpirationYear);
        $cardParameters['CARDCVV'] = $this->cardCvv;

        return $cardParameters;
    }

    private function formatNumber($number): array|string|null
    {
        return preg_replace('/\s+/', '', $number);
    }

    private function getExpireDate($expireMonth, $expireYear): string
    {
        $expireYear = str_pad($expireYear, 2, '0', STR_PAD_LEFT);
        $expireYear = str_pad($expireYear, 4, '20', STR_PAD_LEFT);
        $expireMonth = str_pad($expireMonth, 2, '0', STR_PAD_LEFT);

        return "{$expireMonth}.{$expireYear}";
    }

}