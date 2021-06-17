<?php


namespace Bitrix\Openid\Client\Interfaces;

use Bitrix\Main\Result;
use Bitrix\Openid\Client\OpenIdConfig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface OpenIdClientInterface
{
    /**
     * @return mixed
     */
    public function redirectToAuth();

    /**
     * @param OpenIdHandlerInterface $handler
     * @return mixed
     */
    public function setHandler(OpenIdHandlerInterface $handler);

    /**
     * @param ServerRequestInterface $request
     * @param null $id
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, $id = null);

    /**
     * @param null $id
     * @return CredentialInterface|null
     */
    public function getCredential($id = null): ?CredentialInterface;

    /**
     * @param RefreshCredentialInterface $credential
     * @param null $id
     * @return CredentialInterface|null
     */
    public function refreshCredential(RefreshCredentialInterface $credential, $id = null): ?CredentialInterface;

    /**
     * @param mixed $id
     * @return Result
     */
    public function clear($id = null): Result;

    /**
     * @param CredentialInterface $credential
     * @return ResponseInterface|null
     */
    public function getUserInfo(CredentialInterface $credential): ?ResponseInterface;

    /**
     * @return OpenIdConfig
     */
    public function getConfig(): OpenIdConfig;
}