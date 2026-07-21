<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payu</title>
</head>
<body>
<form method="post" action="{{ route('payu.checkout') }}">
    @csrf
    <label for="product">Product</label>
    <input type="text" name="product" id="product"><br/><br/><br/>
    <label for="fullname">Fullname</label>
    <input type="text" name="fullname" id="fullname"><br/><br/><br/>
    <label for="email">Email</label>
    <input type="text" name="email" id="email"><br/><br/><br/>
    <label for="mobile">Mobile</label>
    <input type="text" name="mobile" id="mobile"><br/><br/><br/>
    <label for="amount">Amount</label>
    <input type="text" name="amount" id="amount"><br/><br/><br/>
    <button type="submit" name="submit-btn">Pay</button>
</form>
</body>
</html>
