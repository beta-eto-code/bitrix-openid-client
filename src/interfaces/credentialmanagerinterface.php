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
     * @param null $id
     * @return CredentialInterface|null
     */
    public function load($id = null): ?CredentialInterface;

    /**
     * @param CredentialInterface $credential
     * @param null $id
     * @return Result
     */
    public function save(CredentialInterface $credential, $id = null): Result;
}