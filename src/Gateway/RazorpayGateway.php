<?php

namespace Payastra\Gateways;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

/**
 * Razorpay Gateway class to handle Razorpay order creation and signature verification
 * with added improvements for flexibility, error handling, and documentation.
 */
class RazorpayGateway
{
    /**
     * Razorpay API instance
     * 
     * @var \Razorpay\Api\Api
     */
    protected $api;

    /**
     * Constructor to initialize Razorpay API with provided credentials.
     *
     * @param string $keyId     Razorpay API Key ID
     * @param string $keySecret Razorpay API Secret Key
     */
    public function __construct(string $keyId, string $keySecret)
    {
        if (empty($keyId) || empty($keySecret)) {
            throw new \InvalidArgumentException('Razorpay Key ID and Key Secret cannot be empty.');
        }

        $this->api = new Api($keyId, $keySecret);
    }

    /**
     * Create an order on Razorpay.
     * 
     * @param float  $amount    The order amount in INR (use the smallest unit, e.g., paise for INR)
     * @param string $receipt   Optional: Receipt ID (e.g., order ID)
     * @param string $currency  Currency of the order, default is 'INR'
     * @return \Razorpay\Api\Model\Order The Razorpay order object
     * @throws \Exception If the order creation fails
     */
    public function createOrder(float $amount, string $receipt = '', string $currency = 'INR')
    {
        try {
            $orderData = [
                'amount'   => $amount * 100, // Amount in paise
                'currency' => strtoupper($currency),
                'receipt'  => $receipt ?? uniqid('rcpt_')
            ];

            return $this->api->order->create($orderData);
        } catch (\Exception $e) {
            // Log the error if necessary and rethrow
            throw new \Exception('Error creating Razorpay order: ' . $e->getMessage());
        }
    }

    /**
     * Verify the payment signature received from Razorpay.
     * 
     * @param string $orderId    Razorpay order ID
     * @param string $paymentId  Razorpay payment ID
     * @param string $signature  Razorpay payment signature
     * @return bool True if signature is valid, false otherwise
     */
    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        try {
            $attributes = [
                'razorpay_order_id'   => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature'  => $signature
            ];

            // Verify the signature using Razorpay's utility method
            $this->api->utility->verifyPaymentSignature($attributes);

            return true; // Signature is valid
        } catch (SignatureVerificationError $e) {
            // Log the error if necessary
            error_log('Signature verification failed: ' . $e->getMessage());
            return false; // Invalid signature
        } catch (\Exception $e) {
            // Handle other exceptions
            error_log('Error verifying signature: ' . $e->getMessage());
            return false;
        }
    }
}
