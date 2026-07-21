@php
    $buyerEmail = trim($postData['buyerEmail']);
    $buyerPhone = trim($postData['buyerPhone']);
    $buyerFirstName = trim($postData['buyerFirstName']);
    $buyerLastName = trim($postData['buyerLastName']);
    $buyerAddress = trim($postData['buyerAddress']);
    $amount = trim($postData['amount']);
    $buyerCity = trim($postData['buyerCity']);
    $buyerState = trim($postData['buyerState']);
    $buyerPinCode = trim($postData['buyerPinCode']);
    $buyerCountry = trim($postData['buyerCountry']);
    $orderid = trim($postData['orderid']); //Your System Generated Order ID
    // $hiddenmod = trim($_POST['directindexvar']);
    $currency = trim($postData['currency']);
    $isocurrency = trim($postData['isocurrency']);
    $mercid = trim($postData['mercid']);

    $username = trim($postData['username']);
    $password = trim($postData['password']);
    $secret = trim($postData['secret']);

    //$this->load->view('airpay_validate', $postData);
    $_POST = $postData;

    $alldata = $buyerEmail . $buyerFirstName . $buyerLastName . $buyerAddress . $buyerCity . $buyerState . $buyerCountry . $amount . $orderid;
    $privatekey = air_encrypt($username . ":|:" . $password, $secret);
    $keySha256 = air_encryptSha256($username . "~:~" . $password);
    $checksum = air_calculateChecksumSha256($alldata . date('Y-m-d'), $keySha256);

    $hiddenmod = "";
@endphp


			<form action="{{ $url }}" method="post" name="frm1">
			    @csrf
                <input type="hidden" name="privatekey" value="{{ $privatekey }}">
                <input type="hidden" name="mercid" value="{{ env('AIRPAY_MERCHENT_ID') }}">
				<input type="hidden" name="orderid" value="{{ $orderid }}">
 		        <input type="hidden" name="currency" value="{{ $currency }}">
		        <input type="hidden" name="isocurrency" value="{{ $isocurrency }}">
				<!-- <input type="hidden" name="arpyVer" value="3"> -->
				<input type="hidden" name="chmod" value="{{ $postData['hiddenmod'] }}">

				@php
				    air_outputForm($checksum);

				@endphp
			</form>
