<?php
require 'vendor/autoload.php';  // Load Composer autoloader

use Payastra\Gateways\RazorpayGateway;

// Initialize RazorpayGateway with your keys
$keyId = 'rzp_test_9TB3asShG3RvdV';  // Razorpay Key ID
$keySecret = 'zrpWBMrytnHq5UMUeVikNgfn';  // Razorpay Key Secret

$razorpayGateway = new RazorpayGateway($keyId, $keySecret);

// Get the payment details from Razorpay
$paymentId = $_POST['razorpay_payment_id'];
$orderId = $_POST['razorpay_order_id'];
$signature = $_POST['razorpay_signature'];

// Verify the payment signature
$isVerified = $razorpayGateway->verifySignature($orderId, $paymentId, $signature);

if ($isVerified) {
    // Payment was successful
    header('Location: payment_success.php');
} else {
    // Payment verification failed
    header('Location: payment_failed.php');
}
?>
