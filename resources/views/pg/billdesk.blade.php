<html>
    <head></head>
    <body>
        <form name="sdklaunch" id="sdklaunch" action="https://uat1.billdesk.com/u2/web/v1_2/embeddedsdk" method="POST">
            @csrf
            <input type="hidden" id="bdorderid" name="bdorderid" value="{{ $orderId }}">
            <input type="hidden" id="merchantid" name="merchantid" value="{{ env('MERCHANT_ID') }}">
            <input type="hidden" id="rdata" name="rdata" value="{{ $signed }}">
            <input type='submit' value='Complete your Payment' />
        </form>
        <script>
            //document.getElementById('sdklaunch').submit();
        </script>
    </body>
</html>
