<?php
require 'vendor/autoload.php';

use Payastra\Gateways\PaymentGateway;

$gateway = new PaymentGateway();
echo $gateway->process(999);
