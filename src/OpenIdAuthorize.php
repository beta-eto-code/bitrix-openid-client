<?php


namespace Bitrix\Openid\Client;


use Bitrix\Main\Application;
use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Openid\Client\Interfaces\OpenIdClientInterface;
use BitrixPSR7\ServerRequest;
use Bitrix\Openid\Client\Interfaces\OpenIdAuthorizeInterface;
use Bitrix\Openid\Client\Interfaces\UserManagerInterface;

class OpenIdAuthorize implements OpenIdAuthorizeInterface
{
    /**
     * @var OpenIdClientInterface
     */
    private $client;
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(OpenIdClientInterface $client, UserManagerInterface $userManager)
    {
        $this->client = $client;
        $this->userManager = $userManager;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->userManager->isAuthorized();
    }

    /**
     * @param null $id
     * @return Result
     */
    public function authorize($id = null): Result
    {
        if ($this->isAuthorized()) {
            return (new Result())->addError(new Error('Already authorized', 400));
        }

        $bxRequest = Application::getInstance()->getContext()->getRequest();
        $this->client->handle(new ServerRequest($bxRequest));
        $credential = $this->client->getCredential($id);
        if (!$credential) {
            $this->client->redirectToAuth();
        }

        $response = $this->client->getUserInfo($credential);
        $user = $this->userManager->loadUser($response);
        if (!($user instanceof User)) {
            return (new Result())->addError(new Error('User is not load'));
        }

        return $this->userManager->authorize($user);
    }
}