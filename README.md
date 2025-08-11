# Captcha Hub Verifier for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/website-protection/cphub-verifier.svg?style=flat-square)](https://packagist.org/packages/website-protection/cphub-verifier)
[![Total Downloads](https://img.shields.io/packagist/dt/website-protection/cphub-verifier.svg?style=flat-square)](https://packagist.org/packages/website-protection/cphub-verifier)

Простой и удобный пакет для сервер-серверной проверки токенов от сервиса [Captcha Hub](https://cphub.ru) для Laravel.

## Установка

Вы можете установить пакет через Composer:

```bash
composer require website-protection/cphub-verifier
```

## Конфигурация

1.  Опубликуйте конфигурационный файл командой:

    ```bash
    php artisan vendor:publish --provider="WebsiteProtection\CaptchaHubVerifier\CaptchaHubVerifierServiceProvider" --tag="config"
    ```
    Это создаст файл `config/cphub-verifier.php`.

2.  Добавьте ваш секретный ключ в файл `.env`:

    ```env
    CPHUB_SECRET_KEY=ВАШ_СЕКРЕТНЫЙ_КЛЮЧ
    ```

## Использование

Пакет предоставляет удобный фасад `CaptchaHub` для проверки токена.

### Базовая проверка

В вашем контроллере используйте фасад для проверки токена, полученного из формы. Ключ будет автоматически взят из вашей конфигурации.

```php
use Illuminate\Http\Request;
use WebsiteProtection\CaptchaHubVerifier\Facades\CaptchaHub;
use Illuminate\Validation\ValidationException;

class FormController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->input('cphb-captcha-token');

        if (! CaptchaHub::verify($token)) {
            throw ValidationException::withMessages([
               'captcha' => 'Проверка на робота не пройдена.',
            ]);
        }

        // Успех! Логика обработки формы...
    }
}
```

### Проверка с другим ключом

Если у вас несколько конфигураций капчи, вы можете передать нужный `Secret Key` вторым аргументом, переопределив ключ по умолчанию.

```php
use WebsiteProtection\CaptchaHubVerifier\Facades\CaptchaHub;

$anotherSecretKey = 'ДРУГОЙ_СЕКРЕТНЫЙ_КЛЮЧ';
$token = $request->input('cphb-captcha-token');

if (CaptchaHub::verify($token, $anotherSecretKey)) {
    // Успех
}
```

## Лицензия

The MIT License (MIT). Пожалуйста, смотрите [Файл Лицензии](LICENSE.md) для получения дополнительной информации.