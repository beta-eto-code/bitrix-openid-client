<?php

namespace Bitrix\Openid\Client\Interfaces;

use Bitrix\Main\Result;
use Psr\Http\Message\ResponseInterface;

interface CredentialManagerInterface
{
    /**
     * @param ResponseInterface $response
     * @return CredentialInterface|null
     */
    public function createFromResponse(ResponseInterface $response): ?CredentialInterface;

    /**
     * @param mixed $id
     * @return CredentialInterface|null
     */
    public function load($id = null): ?CredentialInterface;

    /**
     * @param CredentialInterface $credential
     * @param mixed $id
     * @return Result
     * @psalm-suppress UndefinedClass
     */
    public function save(CredentialInterface $credential, $id = null): Result;

    /**
     * @param mixed $id
     * @return Result
     * @psalm-suppress UndefinedClass
     */
    public function clear($id = null): Result;
}
