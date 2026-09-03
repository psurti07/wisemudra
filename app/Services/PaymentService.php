<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Exceptions\APIException;

class PaymentService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('payment');
    }

    public function orderStatus(string $orderId): array
    {
        return $this->callApi("GET", "/orders/{$orderId}", null, 'ORDER_STATUS');
    }

    public function orderSession(array $params): array
    {
        $this->ensureParams($params);

        $params['payment_page_client_id'] ?? $this->config['payment_page_client_id'];

        return $this->callApi("POST", "/session", $params, 'ORDER_SESSION', 'application/json');
    }

    public function refund(array $params): array
    {
        $this->ensureParams($params);

        return $this->callApi("POST", "/refunds", $params, 'ORDER_REFUND', 'application/x-www-form-urlencoded');
    }

    public function validateHmacSha256(array $params, ?string $secret = null): bool
    {
        $secret ?? $this->config['response_key'];
        if (empty($secret)) return false;

        $signature = $params['signature'] ?? '';
        $algorithm = $params['signature_algorithm'] ?? '';

        $filtered = array_filter($params, function($k) {
            return $k !== 'signature' && $k !== 'signature_algorithm';
        }, ARRAY_FILTER_USE_KEY);


        $query = http_build_query($filtered);
        $encoded = urlencode(rtrim($query, '&'));

        $hash = base64_encode(hash_hmac('sha256', $encoded, $secret, true));

        if (urldecode($hash) === urldecode($signature)) {
            return true;
        }

        retry: Log::channel($this->config['logging_channel'])->info(
            "[HMAC Mismatch] computed=".urldecode($hash)." expected=".urldecode($signature)
        );

        return false;
    }

    protected function callApi(string $method, string $path, ?array $data, string $tag, string $contentType = 'application/json'): array
    {
        $url = $this->config['base_url'] . $path;
        $headers = [
            'version'          => $this->config['api_version'],
            'x-merchantid'     => $this->config['merchant_id'],
            'Content-Type'     => $contentType,
        ];
        $options = ['headers' => $headers];

        if ($this->config['api_key']) {
            $options['auth'] = [$this->config['api_key'], ''];
        }
        if ($this->config['ca_path']) {
            $options['verify'] = $this->config['ca_path'];
        }

        if ($this->config['enable_logging']) {
            Log::channel($this->config['logging_channel'])->info("[$tag] Request: {$method} {$url}", ['data' => $data]);
        }

        $response = match ($method) {
            'GET'  => Http::withOptions($options)->get($url, $data),
            'POST' => Http::withOptions($options)->post($url, in_array($contentType, ['application/json']) ? $data : http_build_query($data)),
            default => throw new APIException(-1, 'INVALID_METHOD', 'INVALID_METHOD', "Unsupported HTTP method: {$method}")
        };

        if ($response->successful()) {
            $body = $response->json();
            if ($this->config['enable_logging']) {
                Log::channel($this->config['logging_channel'])->info("[$tag] Response: {$response->body()}");
            }
            return $body;
        }

        $json = $response->json();
        $status      = $json['status'] ?? null;
        $error_code  = $json['error_code'] ?? null;
        $error_msg   = $json['error_message'] ?? $response->body();

        if ($this->config['enable_logging']) {
            Log::channel($this->config['logging_channel'])->error("[$tag] Error: HTTP {$response->status()} - {$response->body()}");
        }

        throw new APIException($response->status(), $status, $error_code, $error_msg);
    }

    protected function ensureParams(?array $params): void
    {
        if (empty($params)) {
            throw new APIException(-1, 'INVALID_PARAMS', 'INVALID_PARAMS', 'Params is empty');
        }
    }
}
