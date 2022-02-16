<?php

namespace Bitrix\Openid\Client;

use Bitrix\Main\Application;
use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Main\Session\SessionInterface;
use Bitrix\Main\Web\Cookie;
use Bitrix\Openid\Client\Interfaces\CredentialInterface;
use Bitrix\Openid\Client\Interfaces\CredentialManagerInterface;
use Psr\Http\Message\ResponseInterface;

class CookieCredentialManager implements CredentialManagerInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var CredentialInterface
     */
    private $credentialClass;

    /**
     * @var int
     */
    private $defaultTTL;

    /**
     * @param CredentialInterface $credentialClass
     * @param string $key
     * @param int|null $defaultTTL
     */
    public function __construct($credentialClass, string $key, int $defaultTTL = null)
    {
        $this->credentialClass = $credentialClass;
        $this->defaultTTL = $defaultTTL ?? (60 * 60 * 24 * 2);
        $this->key = $key;
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
        $cookieData = $_COOKIE[$this->key . ($id ?? '')] ?? null;
        if (empty($cookieData)) {
            return null;
        }

        return $this->credentialClass::import($cookieData);
    }

    /**
     * @param CredentialInterface $credential
     * @param mixed $id
     * @return Result
     * @psalm-suppress UndefinedClass
     */
    public function save(CredentialInterface $credential, $id = null): Result
    {
        $result = new Result();
        try {
            $data = $credential->export();
            $server = Application::getInstance()->getContext()->getServer();

            $credentialTTL = $credential->getTTL();
            $expiredAt = $credentialTTL > 0 ? $credentialTTL + time() : $this->defaultTTL + time();
            setcookie($this->key . ($id ?? ''), $data, $expiredAt, '/', $server->getHttpHost());
        } catch (\Throwable $e) {
            return $result->addError(new Error($e->getMessage()));
        }

        return $result;
    }

    /**
     * @param mixed $id
     * @return Result
     * @psalm-suppress UndefinedClass
     */
    public function clear($id = null): Result
    {
        unset($_COOKIE[$this->key . $id]);
        $server = Application::getInstance()->getContext()->getServer();
        setcookie($this->key . $id, '', -1, '/', $server->getHttpHost());

        return new Result();
    }
}
