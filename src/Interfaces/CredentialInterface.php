<?php

namespace Bitrix\Openid\Client\Interfaces;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface CredentialInterface
{
    /**
     * @param ResponseInterface $response
     * @return CredentialInterface
     */
    public static function initFromResponse(ResponseInterface $response): CredentialInterface;

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function loadCredential(RequestInterface $request): RequestInterface;

    /**
     * @param string $data
     * @return CredentialInterface|null
     */
    public static function import(string $data): ?CredentialInterface;

    /**
     * @return int
     */
    public function getTTL(): int;

    /**
     * @return bool
     */
    public function isExpired(): bool;

    /**
     * @return string
     */
    public function export(): string;
}
