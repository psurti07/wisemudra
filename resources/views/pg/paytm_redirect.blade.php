<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Paytm...</title>
</head>
<body onload="document.forms[0].submit();">
    <p>Redirecting to Paytm. Please wait...</p>

    <form method="POST" action="{{ $txn_url }}?mid={{ $mid }}&orderId={{ $orderId }}">
        <input type="hidden" name="txnToken" value="{{ $txnToken }}">
    </form>
</body>
</html>
