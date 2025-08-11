<?php

namespace WebsiteProtection\CaptchaHubVerifier\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool verify(?string $token, ?string $secretKeyOverride = null)
 */
class CaptchaHub extends Facade
{
    protected static function getFacadeAccessor(): string {
        return 'cphub-verifier';
    }
}