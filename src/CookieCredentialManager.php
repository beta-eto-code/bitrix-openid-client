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
     * @param string|CredentialInterface $credentialClass
     * @param string $key
     */
    public function __construct(string $credentialClass, string $key)
    {
        $this->credentialClass = $credentialClass;
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
     * @param null $id
     * @return CredentialInterface|null
     */
    public function load($id = null): ?CredentialInterface
    {
        $cookieData = $_COOKIE[$this->key.$id] ?? null;
        if (empty($cookieData)) {
            return null;
        }

        return $this->credentialClass::import($cookieData);
    }

    /**
     * @param CredentialInterface $credential
     * @param null $id
     * @return Result
     */
    public function save(CredentialInterface $credential, $id = null): Result
    {
        $result = new Result();
        try {
            $data = $credential->export();
            $server = Application::getInstance()->getContext()->getServer();
            setcookie($this->key.$id, $data, time()+(60*60*24*2), '/', $server->getHttpHost());
        } catch (\Throwable $e) {
            return $result->addError(new Error($e->getMessage()));
        }

        return $result;
    }

    /**
     * @param null $id
     * @return Result
     */
    public function clear($id = null): Result
    {
        unset($_COOKIE[$this->key.$id]);
        $server = Application::getInstance()->getContext()->getServer();
        setcookie($this->key.$id, null, -1, '/', $server->getHttpHost());

        return new Result();
    }
}