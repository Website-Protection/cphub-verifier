<?php

namespace WebsiteProtection\CaptchaHubVerifier;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Verifier
{
    protected ?string $defaultSecretKey;
    protected string $verifyUrl;

    public function __construct(string $verifyUrl, ?string $defaultSecretKey) {
        $this->verifyUrl = $verifyUrl;
        $this->defaultSecretKey = $defaultSecretKey;
    }

    /**
     * Проверяет токен капчи.
     *
     * @param string|null $token
     * @param string|null $secretKeyOverride (для переопределения ключа)
     * @return bool
     */
    public function verify(?string $token, ?string $secretKeyOverride = null): bool {
        $secretKey = $secretKeyOverride ?? $this->defaultSecretKey;

        if(!$token || !$secretKey) {
            return false;
        }

        $client = new Client(['timeout' =>5]);
        try {
            $response = $client->post($this->verifyUrl, [
                'form_params' => [
                    'secret' => $secretKey,
                    'token'  => $token
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $result = json_decode($response->getBody()->getContents());
                return $result && $result->success === true;
            }
        } catch (\Exception $e) {
            Log::error('Captcha Hub service is unavailable.', ['error' => $e->getMessage()]);
            // В случае недоступности сервиса, лучше пропустить проверку
            return true;
        }

//        try {
//            $response = Http::asForm()
//                ->timeout(5)
//                ->post($this->verifyUrl, [
//                    'secret' => $secretKey,
//                    'token'  => $token,
//                ]);
//
//            return $response->successful() && $response->json('success') === true;
//        } catch (\Exception $e) {
//            Log::error('Captcha Hub service is unavailable.', ['error' => $e->getMessage()]);
//            // В случае недоступности сервиса, лучше пропустить проверку
//            return true;
//        }

    }
}