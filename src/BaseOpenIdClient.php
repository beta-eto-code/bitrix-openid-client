<?php

namespace Bitrix\Openid\Client;

use Bitrix\Main\Result;
use Bitrix\Openid\Client\Exceptions\RequestException;
use Bitrix\Openid\Client\Interfaces\CredentialInterface;
use Bitrix\Openid\Client\Interfaces\CredentialManagerInterface;
use Bitrix\Openid\Client\Interfaces\OpenIdClientInterface;
use Bitrix\Openid\Client\Interfaces\OpenIdHandlerInterface;
use Bitrix\Openid\Client\Interfaces\RefreshCredentialInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class BaseOpenIdClient implements OpenIdClientInterface
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;
    /**
     * @var OpenIdConfig
     */
    protected $config;
    /**
     * @var OpenIdHandlerInterface
     */
    private $handler;
    /**
     * @var CredentialManagerInterface
     */
    private $credentialManager;

    public function __construct(
        CredentialManagerInterface $credentialManager,
        ClientInterface $httpClient,
        OpenIdHandlerInterface $handler,
        OpenIdConfig $config
    ) {
        $this->credentialManager = $credentialManager;
        $this->httpClient = $httpClient;
        $this->handler = $handler;
        $this->config = $config;
    }

    abstract protected function getRedirectAuthUrl(): string;
    abstract protected function requestToken(): ResponseInterface;
    abstract protected function requestRefreshToken(RefreshCredentialInterface $credential): ?ResponseInterface;
    abstract protected function requestUserInfo(CredentialInterface $credential): ?ResponseInterface;

    /**
     * @param ServerRequestInterface $request
     * @param null $id
     * @return void
     */
    public function handle(ServerRequestInterface $request, $id = null)
    {
        $this->handler->handle($request, $this, $id);
    }

    public function redirectToAuth()
    {
        LocalRedirect($this->getRedirectAuthUrl(), true);
    }

    /**
     * @param null $id
     * @return CredentialInterface|null
     */
    public function getCredential($id = null): ?CredentialInterface
    {
        $credential = $this->credentialManager->load($id);
        if ($credential instanceof CredentialInterface) {
            return $credential;
        }

        $response = $this->requestToken();
        if (!$this->isValidResponse($response)) {
            return null;
        }

        $credential = $this->credentialManager->createFromResponse($response);
        if ($credential instanceof CredentialInterface) {
            $this->credentialManager->save($credential, $id);
        }

        return $credential;
    }

    /**
     * @param mixed $id
     * @return Result
     */
    public function clear($id = null): Result
    {
        return $this->credentialManager->clear($id);
    }

    public function refreshCredential(RefreshCredentialInterface $credential, $id = null): ?CredentialInterface
    {
        $response = $this->requestRefreshToken($credential);
        if (!$this->isValidResponse($response)) {
            return null;
        }

        $credential = $this->credentialManager->createFromResponse($response);
        if ($credential instanceof CredentialInterface) {
            $this->credentialManager->save($credential, $id);
        }

        return $credential;
    }

    public function getUserInfo(CredentialInterface $credential): ?ResponseInterface
    {
        return $this->requestUserInfo($credential);
    }

    /**
     * @param ResponseInterface|null $response
     * @param int $expectedStatus
     * @return bool
     */
    private function isValidResponse(?ResponseInterface $response, int $expectedStatus = 200): bool
    {
        if (empty($response)) {
            return false;
        }

        return $response->getStatusCode() === $expectedStatus;
    }

    public function setHandler(OpenIdHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return OpenIdConfig
     */
    public function getConfig(): OpenIdConfig
    {
        return $this->config;
    }
}
