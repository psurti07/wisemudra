<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PhonePe</title>
</head>
<body>
    <form method="post" action="{{ route('phonepe') }}">
        @csrf
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount"><br/><br/><br/>
        <label for="amount">mobile</label>
        <input type="text" name="mobile" id="mobile">
        <button type="submit" name="submit-btn">Pay</button>
    </form>
</body>
</html>
