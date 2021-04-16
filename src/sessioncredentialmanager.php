<?php


namespace Bitrix\Openid\Client;


use Bitrix\Main\Application;
use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Main\Session\SessionInterface;
use Bitrix\Openid\Client\Interfaces\CredentialInterface;
use Bitrix\Openid\Client\Interfaces\CredentialManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class SessionCredentialManager implements CredentialManagerInterface
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string
     */
    private $key;

    /**
     * @var CredentialInterface|string
     */
    private $credentialClass;

    /**
     * SessionCredentialManager constructor.
     * @param string|CredentialInterface $credentialClass
     * @param string $key
     * @param SessionInterface|null $session
     */
    public function __construct(string $credentialClass, string $key, SessionInterface $session = null)
    {
        $this->credentialClass = $credentialClass;
        $this->key = $key;
        $this->session = $session ?? Application::getInstance()->getSession();
    }

    /**
     * @param null $id
     * @return CredentialInterface|null
     */
    public function load($id = null): ?CredentialInterface
    {
        $data = $this->session->get($this->key);
        if (empty($data)) {
            return null;
        }

        return $this->credentialClass::import($data);
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
            $this->session->set($this->key, $data);
        } catch (Throwable $exception) {
            return $result->addError(new Error($exception->getMessage(), $exception->getCode()));
        }

        return $result;
    }

    /**
     * @param ResponseInterface $response
     * @return CredentialInterface|null
     */
    public function createFromResponse(ResponseInterface $response): ?CredentialInterface
    {
        return $this->credentialClass::initFromResponse($response);
    }
}