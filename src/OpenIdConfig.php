<?php


namespace Bitrix\Openid\Client;


use Psr\Http\Message\RequestInterface;

class OpenIdConfig
{
    /**
     * URL страницы с авторизацией
     * @var string
     */
    public $loginUrl;
    /**
     * URL для запроса токена
     * @var string
     */
    public $tokenUrl;
    /**
     * Идентификатор приложения
     * @var string
     */
    public $clientId;
    /**
     * Ключ приложения
     * @var string
     */
    public $clientSecret;
    /**
     * Код авторизации полученный с сервера
     * @var string
     */
    public $code;

    /**
     * URL для передачи кода авторизации
     * @var string
     */
    public $redirectUrl;
    /**
     * Запрашиваемый уровень доступа
     * @var string
     */
    public $scope;

    /**
     * @var RequestInterface|null
     */
    public $userInfoRequest;

    /**
     * @var RequestInterface|null
     */
    public $refreshCredentialRequest;

    public function __construct(
        string $loginUrl,
        string $tokenUrl,
        string $redirectUrl,
        string $clientId,
        string $clientSecret
    )
    {
        $this->loginUrl = $loginUrl;
        $this->tokenUrl = $tokenUrl;
        $this->clientId = $clientId;
        $this->redirectUrl = $redirectUrl;
        $this->clientSecret = $clientSecret;
        $this->scope = 'openid';
    }

    /**
     * @param string $code
     */
    public function setAuthCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return bool
     */
    public function hasAuthCode(): bool
    {
        return !empty($this->code);
    }
}