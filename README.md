# OpenId client for Bitrix

Пример использования:

```php
use BitrixPSR18\Client;
use Bitrix\Openid\Client\SessionCredentialManager;
use Bitrix\Openid\Client\OpenIdAuthorize;
use Bitrix\Openid\Client\OpenIdConfig;
use Bitrix\Openid\Client\AuthCodeResolveHandler;
use Bitrix\Openid\Client\Interfaces\OpenIdClientInterface;


$httpClient = new Client();                         // HTTP клиент для отправки запросов
$credentialManager = new SessionCredentialManager(  // менеджер данных авторизации
    SomeCredentialImplementation::class,            // некоторый декоратор запросов для заполнения данных авторизации
    'session_key'                                   // ключ сесии где будут хранится данные для авторизации
);
$handler = new AuthCodeResolveHandler();            // обработчик для получения кода авторизации
$config = new OpenIdConfig(                         // настройки OpenId клиента
    'https://someapp.com/oauth/authorize',          // страница авторизации приложения
    'https://someapp.com/oauth/token',              // URL для запроса доступов
    'http://mylocation.com/auth/',                  // страница на которую будет произведен редирект при успешной авторизации приложения
    'client_id',                                    // идентификатор приложения
    'client_secret'                                 // ключ приложения
);

$openIdClient = new SomeOpenIdClient(               // реализация OpenId клиента - OpenIdClientInterface
    $credentialManager,
    $httpClient,
    $handler,
    $config
);
$userManager = new SomeUserManagerImplementation(); // менеджер пользователей
$openidAuthorize = new OpenIdAuthorize($openIdClient, $userManager);
$openidAuthorize->authorize();
```