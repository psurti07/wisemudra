<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class CipherPayController extends Controller
{
    /*private $mainurl;
    private $key;
    private $partnerid;
    private $headerJson;
    private $publicKey;
    private $privateKey;
    private $aesKey;
    private $aesIv;
    private $publicKeyHeader;
    private $partnerToken;
    private $lifetime;

    */



    /*public function __construct()
    {*/
    private static $mainurl = "https://api.cipherpay.in/api/v3/";
    private static $key = "";         // token
    private static $partnerid = "20221427";         // 2022XXXX
    private static $headerJson = '{"partnerId":"CP00321","headerToken":"ZzuXpXV9gP-1gRUECuQKy-lJkd9-m1HIV-NvoMaAU7Jo"}';     //header json
    private static $publicKey = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzL9F6/s07nsgSULSEyRB
PCg+H8Ek7HvTW4VUo7yqU+B6cOVs7caSjHHYt/+36dVETrA37OyDfrMVzCdxIAla
AJzkIWFwXxUrvm8JQna3fhR6zbPi3+Thv2f1qpgFz4DkYpHlz7/tEjVxekFAk33u
4xvsH18UH5RB81QJI0jKwqcLZUSFX8uj0rhEkFgSac/1Fn7cZVw8goTPf6TzDr2M
BEoS4yshrBsn92jSsdUn9WPJItFkGezvMFA4bubC3XEfhO6W5WA+yJxjdfDvNgoA
Ch0feNO6sYlwBPBf0pInoCagcpmW8BXdXR/SXsR8n4yOQJ5aKYSf2/gqM5I1zyIi
YwIDAQAB
-----END PUBLIC KEY-----";     //body key
    private static $privateKey = "-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQChwF7R17Pi5p3h
OY/ehgapY85FvrLpqRzrfFK6BTAypkQvnzPaykOI+xBr89A4s59gdCyUkTNN1F5/
/JwWIBv9xLsmx9ZXtLapNJJNwksXjHydbNaOak4ptvv4/zLf+CiW/zROGHmoeoXd
XvLmha0LS0M0CQtnQPCcI55BTWExdQmmga3QY5kUOl8bQLaRRqic1H1TTHgTzz+w
aLP7Vu8KaviE0Sc2KH4AKzRBXfE5mVFN2iL4NqSE0w9xb4pozDmL32ZoOJ6uRfXO
dvH6VTyjEUApvpgaCqF30NUhPOjxlOEBJyqlKmH6sByzA1KieGie8X+A5ehacYy6
Bv5zcFOrAgMBAAECggEAPw+fSANnC/KqIclNyE0LYtXY8QMQgJ1ge4SMQh7MtCpz
UfepAUcy/kAXnma/SqPo4nNYgBF95X2C3DYRamTZVN3yswNdEvOO9TfcRDmYChXI
7Z0lpv9V+thusxxXas82j+vuKfZL0/30m6ItY+dA2DLe//X4vqgoRk9ynvX6iwt9
4DvXWaJIRpFpkMeJpA+wFquHac+cJLnZZj8G+ZpQpvFRUs7sfjw3zUl8y7LVn3mC
zEo6lmJYaqBf3sKOrCnZPrRdH9Y/4JkrnUfBUmhi28Aa87m89Wsv61JEIJHKwAYu
a94TwavVAI9ZLu6VqIaBl1IUPGfsthputfIWFcBnjQKBgQDQnLh91styoi+dM0yJ
J7Jm6gxT+770EapsEJBw28c/LXX8u97XO35Ty5Pw663pXMNten9QZpbPsYnjq//l
apg3SqfZGaer/95s+wz0GFnjCiPlDxm2Q6p3EBRWg70tY4K/olE/P3TyTlwKfTzF
77q2kH0a7kAcBD2aDEPLbsXZjwKBgQDGfpbt7Cl7apDQuQl9SS7mLs42XSDbI3QN
uISp0MfyvWa4hgy5uNcfFXkJ6/3wKenH2n+R32PwAhfqcEWsjOYb7cojGsMl3Ju5
J5+He2+8xIwAJ6hG4t+UISsCQFwTU5ajGc6iWoQmMTEZ4Ih3+h9UAZ+GlCix809t
jr1Sl0T+JQKBgQCynU01qaB+YTFlZpPkZ1HP3ht6GPVxYmLJrhEOII9jn5gDMhRl
srHCK29a+1/njB5j8VtqyrvbzsYiYpVyp6b2yHwYXWf709Ns+jMoGGV2CKudJyW7
sgoVcXYIcTmb0DUVwXPRNJL8GG2kKYDMdSsnv2TulwnbMyJPcKrnVsweLwKBgQCo
ND3SAH5mhzeQqDzSXmHPzXoRt3lQOgruVZ6WCMZnfPi/BVljSK+DN78KGWFnUx04
rn/MLXGSwTNjByEDx6J3qFnSxar5Oqj7jggx1vgpDqVUvEZtS3QLItA/aCqedgcA
z627BtlVQ/pH423BvcMufPGiKYsSwQxd2se0ZVuhwQKBgQDBe4psE5kz9CtO9ddb
9n/6hBRdG3YE0ZT+2PeeF+G8MnPRpKYm+r6/w6qtTrheFOBOetMhLy+kGMwWR1Xz
+CxFY2HTYD6uLgpNr3W0+f6NjHJiHFKKlF1hVOplf9Toven58H6WdhlTwEaD0JbT
F3b+8WxBHnqsfnXtlomwq+RTcw==
-----END PRIVATE KEY-----";
    private static $aesKey = '';
    private static $aesIv = '';
    private static $publicKeyHeader = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArmPnNPRUIMjUXqVT0i6V
ebaesn17MIhMoMyFXu0gIwSd5/LM7p0Gt1faWlXvl/nUnvdajCWScrgxyIGUVIwM
gYQnxBFhCA+i3WcI3CjVAZNQv0VVbNsGqjFRqLhkaxTRKWZeZbQM6GOWeJ0o3S9Q
oP+8R2xQX5iCeDk/VIq1L9gw/DIJV+V4RSspEbujOEAnUXtAvLZXPJQzTonECzuJ
OQJOqmtThgaH9cablNiIzlFCe6ir5T0tgOSt1VPjQaiBAfaIdYnrF5KccPE5S0SW
C74RXau1WOWg4gs68fAXquL+79mMX+LUSI/YwT/wh068lh851sgz51Ci1KLtk+E+
dwIDAQAB
-----END PUBLIC KEY-----';       //header key

    private static $partnerToken = 'Q1AwMDMyMTokMnkkMTIkWDd2UXZUNFJhcUZMZE1qM1V5d2lHTzVSa1ZWSm1Rc2NOS0hGalNvZDYwT0dzS3Y2ZG5IVUs='; //partner Token
    /*private static $lifetime = config('session.lifetime');*/
    /*}*/

    public function InitiateCollect()
    {
        $request = array(
            "method" => "POST",
            "url" => "payin/initiate-collect",
            "parameter" => [
                "sender_vpa" => "nikjaiswal33-4@okaxis",
                "sender_name" => "Vicky Jaiswal",
                "sender_mobile" => "+918530537178",
                "receiver_vpa" => "cpy.wisemudra@finobank",
                "amount" => "10",
                "remarks" => "Initiate Collect",
                "refid" => rand(1000, 9999),
                "expiry" => "15"
            ]
        );
        $response = $this->hit($request);
        $response = $this->finalResponse($response);
        Log::info('final Response - ' . json_encode($response));
        return $response;
    }

    /* transaction status  */
    public function StatusEnquiry(Request $request)
    {
        $data = array(
            "method" => "POST",
            "url" => "payin/status-enquiry",
            "parameter" => [
                "refid" => Session::get('refid')
            ]
        );

        $response1 = $this->hit($data);
        $response = $this->finalResponse($response1);
        Session::put('cipherResponse', $response);
        Session::save();
        //Log::info('status Enquiry - '. json_encode($response));
        //Log::info('txnid - '. $response['data']['txnid']);
        $paymentMessage = cipherPaymentStatus($response['data']['status']);
        //Log::info($paymentMessage);

        $url = url()->previous();
        $path = parse_url($url, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $secondLastSegment = $segments[count($segments) - 2];

        $actualReturnUrl = $secondLastSegment === 'self-apply' ? 'https://wisemudra.com/self-apply/premium-offer-response' : 'https://Wisemudra.com/loan-agent/ultra-saver-offer-response';

        $redirectUrl = $paymentMessage === 'Transaction Successfull' ? $actualReturnUrl : 'https://wisemudra.com/cipher-payment-failed/';
        Log::info($redirectUrl);
        return response()->json(array('type' => 'SUCCESS', 'message' => $paymentMessage, 'redirectUrl' => $redirectUrl, 'data' => $response));
    }

    /* Dynamic qr function call */
    public function DynamicQr()
    {
        $refId = rand(1000, 9999);
        $request = array(
            "method" => "POST",
            "url" => "payin/dynamic-qr",
            "parameter" => [
                //'receiver_vpa' => "cpy.WIMDRA@fin",
                'receiver_vpa' => "cpy.wisemudra@finobank",
                'amount' => "299", // amount
                'remarks' => "Dynamic QR", // remarks
                'refid' => $refId, //refrence id
                'expiry' => "2", //in minutes
                'type' => "QR"
            ]
        );
        Session::forget('refid');
        Session::put('refid', $refId);
        Session::save();
        Log::info(json_encode($request));
        $response = $this->hit($request);
        $response = $this->finalResponse($response);
        Log::info('final Response - ' . json_encode($response));
        //return response(QrCode::size(200)->generate($response['qr']));
        return view('pg.cipherQR', compact('response'));
        //return $response;
    }

    /* hit function call */
    public function hit($reqData)
    {
        $url = self::$mainurl . $reqData['url'];
        //Log::info('url - '. $url);
        /*Log::info('ipaddress - '. Request::ip());
        Log::info('ipaddress2 - '. $_SERVER['REMOTE_ADDR']);*/
        $num = time();
        $reqData['jwt'] = getjwttoken();
        //Log::info('jwt - '. $reqData['jwt']);
        //$this->writelog("REQUEST" . $num, $reqData);
        if (!empty($reqData['parameter'])) {
            $parameter = json_encode($reqData['parameter']);
        } else {
            $parameter = "";
        }
        //Log::info('parameter - '. $parameter);
        $info = $this->finalRequest($parameter);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $reqData['method'],
            CURLOPT_POSTFIELDS => json_encode($info['payload']),
            CURLOPT_HTTPHEADER => array(
                "Token: " . $reqData['jwt'],
                "Auth: " . $info['Auth'],
                "Key:" . $info['Key'],
                "cache-control: no-cache",
                "content-type: application/json",
                "User-Agent: PostmanRuntime/7.29.2"
            ),
        ));
        $response = curl_exec($curl);
        Log::info('curl Response - ' . $response);
        if (curl_errno($curl)) {
            $resp = array("errorCode" => "PAYSPRINT-001", "error_code" => curl_errno($curl), "message" => curl_error($curl), "errorMessage" => "Response not found, please try again later.");
        } else {
            $resp = $this->response($response);
        }
        //$this->writelog("RESPONSE" . $num, $resp);
        return $resp;
    }

    /* final request function call */
    private function finalRequest($parameters = "")
    {
        $salt = bin2hex(openssl_random_pseudo_bytes(8));
        $data = $this->generateAesKey($salt);
        $key = $data[0];
        $iv = $data[1];
        $cipher = 'aes-128-cbc';
        //Log::info('salt key - '. $salt);
        //Log::info('key - '. $key);
        //Log::info('iv - '. $iv);

        if ($parameters != "") {
            $encrypted = openssl_encrypt(json_encode($parameters), $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $encrypted = base64_encode($encrypted);
        }
        //Log::info('encrypted - '. $encrypted);
        $encryptedSalt = $this->rsaEncrypt($salt, self::$publicKey);
        $encryptedHeader = $this->rsaEncrypt(self::$headerJson, self::$publicKeyHeader);
        //Log::info('encryptedSalt - '. $encryptedSalt);
        //Log::info('encryptedHeader - '. $encryptedHeader);
        $request = [
            'Auth' => $encryptedHeader,
            'Key' => $encryptedSalt,
            'payload' => $parameters ? ['requestData' => $encrypted] : null,
        ];
        //Log::info('final Request - '. json_encode($request));
        return $request;
    }

    /* response function call */
    public function response($response)
    {
        $res = json_decode($response, TRUE);
        return $res;
    }

    /* final response function call */
    public function finalResponse($response)
    {
        $responseData = $response['returnData'] ?? NULL;
        if (!$responseData) {
            Log::info("finalResponse null", [$responseData]);
        }
        $encrypted = base64_decode($responseData);
        $decrypted = openssl_decrypt($encrypted, 'aes-128-cbc', self::$aesKey, OPENSSL_RAW_DATA, self::$aesIv);
        $decrypted = json_decode($decrypted, true);
        return $decrypted;
    }

    /* generate AESKey */
    public function generateAesKey($salt)
    {
        $salt = hex2bin($salt);
        $passphrase = 'CipherPay API Payout';
        $iterationCount = 10000;
        $keySize = 128;
        $hashAlgorithm = 'sha1';
        $key = openssl_pbkdf2($passphrase, $salt, $keySize / 8, $iterationCount, $hashAlgorithm);
        self::$aesKey = $key;
        self::$aesIv = bin2hex($salt);
        return [$key, bin2hex($salt)];
    }

    /* encrypt the header and bodey key with salt */
    public function rsaEncrypt($data, $publicKey)
    {
        $publicKey = openssl_get_publickey($publicKey);
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return base64_encode($encrypted);
    }

    /*public function consumeCallback($headerKey, $requestData, $privateKey)
    {
        $key = base64_decode($headerKey);
        $requestData = base64_decode($requestData);
        $privateKey = openssl_pkey_get_private($privateKey);
        openssl_private_decrypt($key, $salt, $privateKey);
        $aesKey = $this->generateAesKey($salt)[0];
        $output = openssl_decrypt($requestData, 'AES-128-CBC', $aesKey, OPENSSL_RAW_DATA, $salt);
        return $output;
    }*/

    public function CipherResponse(Request $request)
    {
        $data = $request->all();
        $headerKey = $request->header('key');
        $requestData = $data['requestData'];
        $salt = bin2hex(openssl_random_pseudo_bytes(8));

        $key = base64_decode($headerKey); // get header from request
        $requestData = base64_decode($requestData);

        $privateKey = openssl_pkey_get_private(self::$privateKey);
        openssl_private_decrypt($key, $salt, $privateKey);
        $aesKey = $this->generateAesKey($salt)[0];
        $output = openssl_decrypt($requestData, 'AES-128-CBC', $aesKey, OPENSSL_RAW_DATA, $salt);
        Log::info($output);
        return $output;
    }

    public function payCPRes(Request $request)
    {
        /*$data = $request->all();
        $headerKey = $request->header('key');
        $requestData = $data['requestData'];
        $salt = bin2hex(openssl_random_pseudo_bytes(8));

        $key = base64_decode($headerKey); // get header from request
        $requestData = base64_decode($requestData);

        $privateKey = openssl_pkey_get_private($this->privateKey);
        openssl_private_decrypt($key, $salt, $privateKey);
        $aesKey = $this->generateAesKey($salt)[0];
        $output = openssl_decrypt($requestData, 'AES-128-CBC', $aesKey, OPENSSL_RAW_DATA, $salt);*/
        Log::info('callback function - ' . json_encode($request->all()));
        Log::info('function call');
        //Log::info($output);
        return true;
    }

    public function paymentFailed(Request $request)
    {
        dd('Payment Failed');
    }

    public function paymentSuccess(Request $request)
    {
        Log::info("payment_log");
        Log::info(json_encode(Session::get('cipherResponse')));
        dd('Payment Success');
    }
}
