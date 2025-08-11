<?php

namespace WebsiteProtection\CaptchaHubVerifier;

use Illuminate\Support\Facades\Http;
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

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post($this->verifyUrl, [
                    'secret' => $secretKey,
                    'token'  => $token,
                ]);

            return $response->successful() && $response->json('success') === true;
        } catch (\Exception $e) {
            Log::error('Captcha Hub service is unavailable.', ['error' => $e->getMessage()]);
            // В случае недоступности сервиса, лучше пропустить проверку
            return true;
        }
    }
}