<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment...</title>
    <script src="https://payments.open.money/layer"></script>
</head>
<body onload="triggerLayer();">

    <form action="{{ route('api.loan.assistant.offer2Response') }}" method="post" style="display:none" name="layer_payment_int_form">
        @csrf
        <input type="hidden" name="layer_pay_token_id" value="{{ $token_id }}">
        <input type="hidden" name="tranid" value="{{ $tranid }}">
        <input type="hidden" name="layer_order_amount" value="{{ $amount }}">
        <input type="hidden" id="layer_payment_id" name="layer_payment_id" value="">
        <input type="hidden" id="fallback_url" name="fallback_url" value="">
        <input type="hidden" name="hash" value="{{ $hash }}">
    </form>

    <script>
        var layer_params = {
            payment_token_id: "{{ $token_id }}",
            accesskey: "{{ $accesskey }}"
        };

        function triggerLayer() {
            Layer.checkout(
                {
                    token: layer_params.payment_token_id,
                    accesskey: layer_params.accesskey
                },
                function (response) {
                    if (response && response.payment_id) {
                        document.getElementById('layer_payment_id').value = response.payment_id;
                    }
                    document.layer_payment_int_form.submit();
                },
                function (err) {
                    alert("Payment Error: " + err.message);
                }
            );
        }
    </script>
</body>
</html>
