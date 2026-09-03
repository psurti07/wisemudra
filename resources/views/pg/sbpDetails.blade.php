<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sab Paisa</title>
</head>
<body>
    <form method="post" action="{{ route('subpaisa-send') }}">
        @csrf
        <label for="payer_name">Payer Name</label>
        <input type="text" name="payer_name" id="payer_name"><br/><br/><br/>
        <label for="payer_email">Payer Email</label>
        <input type="text" name="payer_email" id="payer_email"><br/><br/><br/>
        <label for="payer_mobile">Payer Mobile</label>
        <input type="text" name="payer_mobile" id="payer_mobile" maxlength="10" minlength="10"><br/><br/><br/>
        <label for="payer_address">Payer Address</label>
        <input type="text" name="payer_address" id="payer_address" value="Somewhere in India"><br/><br/><br/>
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount" value="1"><br/><br/><br/>
        <button type="submit" name="submit-btn">Pay</button>
    </form>
</body>
</html>
