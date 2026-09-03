<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256GCM;
use Jose\Component\Encryption\Algorithm\KeyEncryption\Dir;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Encryption\Serializer\CompactSerializer as JWESerializer;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\Serializer\CompactSerializer as JWSSerializer;

class BilldeskServices
{
    protected $encryptionKey;
    protected $signingKey;
    protected $keyId;
    protected $clientId;
    
    public function __construct()
    {
        $this->encryptionKey = env('ENC_PASS');
        $this->signingKey = env('SIGN_PASS');
        $this->keyId = env('KEY_ID');
        $this->clientId = env('CLIENT_ID');
    }
    
    public function createPayload(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
    public function encryptPayload(string $payload): string
    {
        $jwk = new JWK([
            'kty' => 'oct',
            'k' => rtrim(strtr(base64_encode($this->encryptionKey), '+/', '-_'), '='),
            'kid' => $this->keyId,
        ]);

        $jweBuilder = new JWEBuilder(
            new AlgorithmManager([new Dir()]),
            new AlgorithmManager([new A256GCM()]),
            new CompressionMethodManager([])
        );

        $jwe = $jweBuilder
            ->create()
            ->withPayload($payload)
            ->withSharedProtectedHeader([
                'alg' => 'dir',
                'enc' => 'A256GCM',
                'kid' => $this->keyId,
                'clientid' => $this->clientId,
            ])
            ->addRecipient($jwk)
            ->build();

        return (new JWESerializer())->serialize($jwe, 0);
    }
    
    public function signPayload(string $payload): string
    {
        $jwk = new JWK([
            'kty' => 'oct',
            'k' => rtrim(strtr(base64_encode($this->signingKey), '+/', '-_'), '='),
            'kid' => $this->keyId,
        ]);
    
        $jwsBuilder = new JWSBuilder(new AlgorithmManager([new HS256()]));
    
        $jws = $jwsBuilder
            ->create()
            ->withPayload($payload)
            ->addSignature($jwk, [
                'alg' => 'HS256',
                'kid' => $this->keyId,
                'clientid' => $this->clientId,
            ])
            ->build();
    
        return (new JWSSerializer())->serialize($jws, 0);
    }

    
    public function decryptPayload(string $encryptedJWE): string
    {
        $jwk = new JWK([
            'kty' => 'oct',
            'k' => rtrim(strtr(base64_encode($this->encryptionKey), '+/', '-_'), '='),
        ]);

        $serializer = new JWESerializer();
        $jwe = $serializer->unserialize($encryptedJWE);

        $decrypter = new JWEDecrypter(
            new AlgorithmManager([new Dir()]),
            new AlgorithmManager([new A256GCM()]),
            new CompressionMethodManager([])
        );

        if (!$decrypter->decryptUsingKey($jwe, $jwk, 0)) {
            throw new \RuntimeException('Decryption failed.');
        }

        return $jwe->getPayload();
    }
    
    public function verifySignature(string $signedJWS): string
    {
        $jwk = new JWK([
            'kty' => 'oct',
            'k' => rtrim(strtr(base64_encode($this->signingKey), '+/', '-_'), '='),
        ]);

        $serializer = new JWSSerializer();
        $jws = $serializer->unserialize($signedJWS);

        $verifier = new JWSVerifier(new AlgorithmManager([new HS256()]));

        if (!$verifier->verifyWithKey($jws, $jwk, 0)) {
            throw new \RuntimeException('Signature verification failed.');
        }

        return $jws->getPayload();
    }
    
}