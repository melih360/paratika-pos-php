<?php

namespace Umuttaymaz\ParatikaPhp\Models;

class Customer
{
    public function __construct(
        protected string  $user_id,
        protected ?string  $user_name = null,
        protected ?string  $user_email = null,
        protected ?string  $user_ip = null,
        protected ?string  $user_phone = null,
        protected ?string $user_birthday = null,
        protected ?string $user_agent = null
    )
    {
    }

    public function getCustomerRequestParameters(): array
    {
        $customerParameters = [];

        $customerParameters['CUSTOMER'] = $this->user_id;
        $customerParameters['CUSTOMERNAME'] = $this->user_name;
        $customerParameters['CUSTOMEREMAIL'] = $this->user_email;
        $customerParameters['CUSTOMERIP'] = $this->user_ip;
        $customerParameters['CUSTOMERPHONE'] = $this->user_phone;
        $customerParameters['CUSTOMERBIRTHDAY'] = $this->user_birthday;
        $customerParameters['CUSTOMERUSERAGENT'] = $this->user_agent;

        return $customerParameters;
    }
}