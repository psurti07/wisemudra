<!DOCTYPE html>
<html>
<head>
    <title>Zaakpay - Payment Process</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body onload="document.frm1.submit()">

    <form action="{{ $curlurl }}" name="frm1" method="post">
        <p>Please wait.......</p>
        <input type="hidden" name="merchantIdentifier" value='{{ $zaakpayPostData ['merchantIdentifier'] }}'/>
        <input type="hidden" name="orderId" value='{{ $zaakpayPostData ['orderId'] }}'/>
        <input type="hidden" name="returnUrl" value='{{ $zaakpayPostData ['returnUrl'] }}'/>
        <input type="hidden" name="currency" value='{{ $zaakpayPostData ['currency'] }}'/>
        <input type="hidden" name="amount" value='{{ $zaakpayPostData ['amount'] }}'/>
        <input type="hidden" name="buyerEmail" value='{{ $zaakpayPostData ['buyerEmail'] }}'/>
        <input type="hidden" name="buyerPhoneNumber" value='{{ $zaakpayPostData ['buyerPhoneNumber'] }}'/>
        <input type="hidden" name="buyerFirstName" value='{{ $zaakpayPostData ['buyerFirstName'] }}'/>
        <input type="hidden" name="buyerLastName" value=''/>
        <input type="hidden" name="buyerAddress" value=''/>
        <input type="hidden" name="buyerCity" value=''/>
        <input type="hidden" name="buyerState" value=''/>
        <input type="hidden" name="buyerCountry" value='{{ $zaakpayPostData ['buyerCountry'] }}'/>
        <input type="hidden" name="buyerPincode" value=''/>
        <input type="hidden" name="productDescription" value='{{ $zaakpayPostData ['productDescription'] }}'/>
        <input type="hidden" name="checksum" value='{{ $checksum }}'/>
    </form>

</body>
</html>
