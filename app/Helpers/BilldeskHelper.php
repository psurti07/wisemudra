<?php

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256GCM;
use Jose\Component\Encryption\Algorithm\KeyEncryption\Dir;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Core\JWK;
use Jose\Component\Encryption\Serializer\CompactSerializer;
/*use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Algorithm\HS256*/;

/* this for encrypt */
if(!function_exists('bdEncrypt')){
    function bdEncrypt($postData){
        try{
            // Required variables
            $encryptionKey = env('ENC_PASS'); // 32-byte AES key
            $keyId = env('KEY_ID');
            $clientId = env('CLIENT_ID');
            $responseString = $postData;
            
            // Managers
            $keyEncryptionAlgorithmManager = new AlgorithmManager([new Dir()]);
            $contentEncryptionAlgorithmManager = new AlgorithmManager([new A256GCM()]);
            $compressionManager = new CompressionMethodManager([]); // ✅ FIXED
            
            // Key
            $jwk = new JWK([
                'kty' => 'oct',
                'k' => rtrim(strtr(base64_encode($encryptionKey), '+/', '-_'), '='),
                'kid' => $keyId,
            ]);
            
            // Build JWE
            $jweBuilder = new JWEBuilder(
                $keyEncryptionAlgorithmManager,
                $contentEncryptionAlgorithmManager,
                $compressionManager
            );
            
            $jwe = $jweBuilder->create()
                ->withPayload($responseString)
                ->withSharedProtectedHeader([
                    'alg' => 'dir',
                    'enc' => 'A256GCM',
                    'kid' => $keyId,
                    'clientid' => $clientId,
                ])
                ->addRecipient($jwk)
                ->build();
            
            $serializer = new CompactSerializer();
            $jweEncryptedData = $serializer->serialize($jwe, 0);
            
            return $jweEncryptedData;
        } catch(\Exception $e){
            Log::info('An error occured in billdesk encrypt method - '. $e->getMessage());
            dd('check log');
        }
    }
}

/* this for decrypt */
if(!function_exists('bdDecrypt')){
    function bdDecrypt($encryptPayLoad){
        try{
            // Step 1: Setup variables
            $encryptedJWE = $encryptPayLoad;
            $encryptionKey = env('ENC_PASS');
            
            // Step 2: Setup algorithms
            $keyEncryptionAlgorithmManager = new AlgorithmManager([
                new Dir(),
            ]);
            
            $contentEncryptionAlgorithmManager = new AlgorithmManager([
                new A256GCM(),
            ]);
            
            $compressionManager = new CompressionMethodManager([]); // No compression used
            
            // Step 3: Setup the decryption key (same as encryption)
            $jwk = new JWK([
                'kty' => 'oct',
                'k' => rtrim(strtr(base64_encode($encryptionKey), '+/', '-_'), '='), // same as in encryption
            ]);
            
            // Step 4: Parse the encrypted string (deserialize)
            $serializer = new CompactSerializer();
            $jwe = $serializer->unserialize($encryptedJWE);
            
            // Step 5: Decrypt the payload
            $jweDecrypter = new JWEDecrypter(
                $keyEncryptionAlgorithmManager,
                $contentEncryptionAlgorithmManager,
                $compressionManager
            );
            
            $success = $jweDecrypter->decryptUsingKey($jwe, $jwk, 0);
            
            if (!$success) {
                throw new \RuntimeException('Decryption failed. Invalid key or corrupted payload.');
            }
            
            // Step 6: Get the decrypted payload
            $decryptedPayload = $jwe->getPayload();
            
            return $decryptedPayload;
        } catch(\Exception $e){
            Log::info('An error occured in bdDecrypt method - ' . $e->getMessage());
            dd('check log');
        }
    }
}

/* signature verification */
if(!function_exists('signatureVerify')){
    function signatureVerify($postData){
        // Inputs
        $signingKey = env('SIGN_PASS'); // Same as Java's signingKey
        $keyId = env('KEY_ID');
        $clientId = env('CLIENT_ID');
        $requestPayload = $postData;
        
        // Step 1: Create the JWK (shared secret)
        $jwk = new JWK([
            'kty' => 'oct',
            'k' => rtrim(strtr(base64_encode($signingKey), '+/', '-_'), '='),
            'kid' => $keyId,
        ]);
        
        // Step 2: Setup algorithm manager
        $algorithmManager = new AlgorithmManager([
            new HS256(), // or HS384 / HS512
        ]);
        
        // Step 3: Create JWS Builder
        $jwsBuilder = new JWSBuilder($algorithmManager);
        
        // Step 4: Build JWS
        $jws = $jwsBuilder
            ->create()
            ->withPayload($requestPayload)
            ->withSharedProtectedHeader([
                'alg' => 'HS256',           // Match your jwsAlgorithm
                'kid' => $keyId,
                'clientid' => $clientId,    // ✅ custom header
            ])
            ->addSignature($jwk)
            ->build();
        
        // Step 5: Serialize (compact JWS)
        $serializer = new CompactSerializer();
        $jwsSignedString = $serializer->serialize($jws, 0); // index 0 = first signature
        
        return $jwsSignedString;
    }
}