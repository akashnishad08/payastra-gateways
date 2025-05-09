<?php
require '../../vendor/autoload.php'; // Load Composer autoloader

use Payastra\Gateways\RazorpayGateway;

// Initialize RazorpayGateway class with your Razorpay keys
$keyId = 'rzp_test_9TB3asShG3RvdV';  // Use your Razorpay Test key ID
$keySecret = 'zrpWBMrytnHq5UMUeVikNgfn';  // Use your Razorpay Test key Secret

$razorpayGateway = new RazorpayGateway($keyId, $keySecret);

// Amount to be charged (in INR, e.g., 10 INR = 1000 paise)
$amount = 1; // Amount in paise

// Create a Razorpay order
$order = $razorpayGateway->createOrder($amount);

if ($order) {
    echo json_encode([
        'order_id' => $order['id'],  // Send the Razorpay order ID to frontend
        'amount' => $order['amount'],
        'currency' => $order['currency']
    ]);
} else {
    echo 'Error creating Razorpay order.';
}
