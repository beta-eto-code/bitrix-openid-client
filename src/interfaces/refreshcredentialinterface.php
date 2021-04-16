<?php


namespace Bitrix\Openid\Client\Interfaces;


use Psr\Http\Message\RequestInterface;

interface RefreshCredentialInterface
{
    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function loadRefreshCredential(RequestInterface $request): RequestInterface;
}