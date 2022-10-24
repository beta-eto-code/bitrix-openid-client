<?php

namespace Bitrix\Openid\Client;

use Bitrix\Main\Result;
use Bitrix\Openid\Client\Interfaces\CredentialInterface;
use Bitrix\Openid\Client\Interfaces\CredentialManagerInterface;
use Psr\Http\Message\ResponseInterface;

class SimpleCredentialManager implements CredentialManagerInterface
{
    private const DEFAULT_KEY = 'default';

    /**
     * @var CredentialInterface[]
     */
    private array $storage;

    /**
     * @var CredentialInterface
     */
    private $credentialClass;

    /**
     * SessionCredentialManager constructor.
     * @param CredentialInterface $credentialClass
     */
    public function __construct($credentialClass)
    {
        $this->credentialClass = $credentialClass;
        $this->storage = [];
    }

    /**
     * @param ResponseInterface $response
     * @return CredentialInterface|null
     */
    public function createFromResponse(ResponseInterface $response): ?CredentialInterface
    {
        return $this->credentialClass::initFromResponse($response);
    }

    /**
     * @param mixed $id
     * @return CredentialInterface|null
     */
    public function load($id = null): ?CredentialInterface
    {
        $key = $id ?? static::DEFAULT_KEY;
        $result = $this->storage[$key] ?? null;

        return $result instanceof CredentialInterface ? $result : null;
    }

    /**
     * @param CredentialInterface $credential
     * @param mixed $id
     * @return Result
     */
    public function save(CredentialInterface $credential, $id = null): Result
    {
        $key = $id ?? static::DEFAULT_KEY;
        $this->storage[$key] = $credential;

        return new Result();
    }

    /**
     * @param mixed $id
     * @return Result
     */
    public function clear($id = null): Result
    {
        $key = $id ?? static::DEFAULT_KEY;
        unset($this->storage[$key]);

        return new Result();
    }
}
