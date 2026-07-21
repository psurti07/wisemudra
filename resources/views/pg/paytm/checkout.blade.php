<!DOCTYPE html>
<html>
<head>
    <title>Paytm Checkout</title>
    <script src="https://securegw-stage.paytm.in/merchantpgpui/checkoutjs/merchants/{{ config('paytm.merchant_id') }}.js"></script>
</head>
<body>
    <h1>Redirecting to Paytm...</h1>
    <script>
        var config = {
            "root": "",
            "flow": "DEFAULT",
            "data": {
                "orderId": "{{ $orderId }}",
                "token": "{{ $txnToken }}",
                "tokenType": "TXN_TOKEN",
                "amount": "{{ $amount }}",
            },
            "handler": {
                "notifyMerchant": function(eventName,data){
                    console.log("eventName => ",eventName," data => ",data);
                }
            }
        };

        if (window.Paytm && window.Paytm.CheckoutJS) {
            window.Paytm.CheckoutJS.init(config).then(function(){
                window.Paytm.CheckoutJS.invoke();
            }).catch(function(error){
                console.error("Paytm init error:", error);
            });
        }
    </script>
</body>
</html>
