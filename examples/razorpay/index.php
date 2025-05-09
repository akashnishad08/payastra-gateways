<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
</head>
<body>

    <h1>Razorpay Payment</h1>
    <button id="payButton">Pay Now</button>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('payButton').onclick = function () {
            // Request order ID from the server
            fetch('create_order.php')
                .then(response => response.json())
                .then(data => {
                    if (data.order_id) {
                        var options = {
                            key: 'rzp_test_9TB3asShG3RvdV',  // Your Razorpay key ID
                            amount: data.amount,  // Amount in paise
                            currency: data.currency,
                            name: 'Your Company',
                            description: 'Order Payment',
                            order_id: data.order_id,  // Set the order ID from backend
                            callback_url: 'http://localhost/payastra/examples/razorpay/payment_callback.php',  // Your callback URL

                            handler: function (response) {
                                // Success callback
                                alert('Payment Successful: ' + response.razorpay_payment_id);
                                window.location.href = 'payment_success.php';
                            },

                            prefill: {
                                name: 'John Doe',
                                email: 'johndoe@example.com',
                                contact: '9999999999'
                            },

                            notes: {
                                address: 'Razorpay Corporate Office'
                            }
                        };

                        var rzp = new Razorpay(options);
                        rzp.open();
                    } else {
                        alert('Error: Could not fetch order ID.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        };
    </script>

</body>
</html>
