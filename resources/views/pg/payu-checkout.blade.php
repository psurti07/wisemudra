<!DOCTYPE html>
<html>
<head>
    <title>PayU - Payment Process</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body onload="document.frm1.submit()">
<form action="{{ $postData['action'] }}" name="frm1" method="post">
    <p>Please wait.......</p>
    <input type="hidden" name="key" value='{{ $postData['mkey'] }}'/>
    <input type="hidden" name="hash" value='{{ $postData['hash'] }}'/>
    <input type="hidden" name="txnid" value='{{ $postData['tid'] }}'/>
    <input type="hidden" name="amount" value='{{ $postData['amount'] }}'/>
    <input type="hidden" name="firstname" value='{{ $postData['name'] }}'/>
    <input type="hidden" name="email" value='{{ $postData['mailid'] }}'/>
    <input type="hidden" name="phone" value='{{ $postData['phoneno'] }}'/>
    <input type="hidden" name="productinfo" value='{{ $postData['productinfo'] }}'/>
    <input type="hidden" name="address1" value='{{ $postData['address'] }}'/>
    <input type="hidden" name="surl" value='{{ $postData['returnUrl'] }}'/>
    <input type="hidden" name="furl" value='{{ $postData['returnUrl'] }}'/>
    <input type="hidden" name="curl" value='{{ $postData['returnUrl'] }}'/>
    <input type="hidden" name="service_provider" value='64'/>
</form>
</body>
</html>
