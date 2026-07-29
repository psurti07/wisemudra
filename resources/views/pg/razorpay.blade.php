<!DOCTYPE html>
<html>
<head>
  <title>Razorpay - Payment Process</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<script type="text/javascript">   
  window.onload = function(){
      razorpayPayment();
  }
</script>

	<form action="{{ $postData['successURL'] }}" name="paymentForm" id="paymentForm" method="post">
	    @csrf
      <p>Please wait.......</p>
      <input type="hidden" name="razorpaykey" id="razorpaykey" value='{{ config('constant.RAZOR_KEY_ID') }}'/>
      <input type="hidden" name="applyid" id="applyid" value='{{ $postData['applyid'] }}'/>
      <input type="hidden" name="fullname" id="fullname" value='{{ $postData['fullname'] }}'/>
      <input type="hidden" name="mobile" id="mobile" value='{{ $postData['mobile'] }}'/>
      <input type="hidden" name="email" id="email" value='{{ $postData['email'] }}'/>
      <input type="hidden" name="orderamount" id="orderamount" value='{{ $postData['orderamount'] }}'/>
      <input type="hidden" name="orderid" id="orderid" value='{{ $postData['orderid'] }}' />
      <input type="hidden" name="description" id="description" value='{{ $postData['description'] }}'/>
      <input type="hidden" name="failURL" id="failURL" value='{{ $postData['failURL'] }}' />
      <input type="hidden" name="paymentid" id="paymentid" value='' />
  </form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script> 
<script type="text/javascript">
    function razorpayPayment() {
        var razorpaykey = document.getElementById('razorpaykey').value;
        var fullname = document.getElementById('fullname').value;
        var mobile = document.getElementById('mobile').value;
        var email = document.getElementById('email').value;
        var amount = document.getElementById('orderamount').value;
        var orderid = document.getElementById('orderid').value;
        var description = document.getElementById('description').value;
        var failURL = document.getElementById('failURL').value;

        var options = {
          "key": razorpaykey,
          "amount": amount * 100,
          "currency": "INR",
          "order_id": orderid,
          "name": "Wisemudra",
          "image": "https://wisemudra.com/assets/images/logo/favicon.ico",
          "description": description,
          "prefill": {
            "name": fullname,
            "email": email,
            "contact": mobile
          },
          "notify": {
            "sms": true,
            "email": true
          },
          "modal": {
            "ondismiss": function () {
                window.location.href = failURL;
            }
          },
          "handler": function (response) {
            if (response.razorpay_payment_id != "") {
              document.getElementById('paymentid').value = response.razorpay_payment_id;
              document.getElementById('paymentForm').submit();
            }
            else {
              setTimeout(function () {
                window.location.href = failURL;
              }, 1500);
            }
          },
          "notes": {
            "address": "Somewhere in India"
            },
            "theme": {

                "color": "#02a6fc"
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();

    }
</script>
</body>
</html>

