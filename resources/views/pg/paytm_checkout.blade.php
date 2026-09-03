<!DOCTYPE html>
<html>
<head>
    <title>Paytm Checkout</title>
</head>
<body>
    <h1>Pay with Paytm</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('paytm.initiate') }}">
        @csrf
        <label>Order ID:</label>
        <input type="text" name="order_id" value="{{ uniqid('ORD') }}" required><br><br>

        <label>Amount (INR):</label>
        <input type="number" step="0.01" name="amount" required><br><br>

        <label>Customer ID:</label>
        <input type="text" name="cust_id" value="CUST001" required><br><br>

        <label>Email:</label>
        <input type="email" name="email"><br><br>

        <label>Mobile:</label>
        <input type="text" name="mobile"><br><br>

        <button type="submit">Pay Now</button>
    </form>
</body>
</html>
