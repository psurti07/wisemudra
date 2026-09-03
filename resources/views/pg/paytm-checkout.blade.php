<!DOCTYPE html>

<html>

<head>

  <title>PayTM - Payment Process</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body onload="document.frm1.submit()">

  <form action="{{ $url }}" name="frm1" method="post">
        @csrf
      <p>Please wait.......</p>

      <input type="hidden" name="MID" value="{{  $postData['MID'] }}"/>

      <input type="hidden" name="ORDER_ID" value="{{  $postData['ORDER_ID'] }}"/>

      <input type="hidden" name="CUST_ID" value="{{  $postData['CUST_ID'] }}"/>

      <input type="hidden" name="EMAIL" value="{{  $postData['EMAIL'] }}"/>

      <input type="hidden" name="INDUSTRY_TYPE_ID" value="{{  $postData['INDUSTRY_TYPE_ID'] }}"/>

      <input type="hidden" name="CHANNEL_ID" value="{{  $postData['CHANNEL_ID'] }}"/>

      <input type="hidden" name="TXN_AMOUNT" value="{{  $postData['TXN_AMOUNT'] }}"/>

      <input type="hidden" name="WEBSITE" value="{{  $postData['WEBSITE'] }}"/>

      <input type="hidden" name="CALLBACK_URL" value="{{  $postData['CALLBACK_URL'] }}"/>

      <input type="hidden" name="CHECKSUMHASH" value="{{  $checksum }}">

  </form>

</body>

</html>
