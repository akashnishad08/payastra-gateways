<?php

namespace Payastra\Gateways;

class PaymentGateway
{
    public function process($amount)
    {
        return "Processing payment of ₹" . $amount;
    }
}
