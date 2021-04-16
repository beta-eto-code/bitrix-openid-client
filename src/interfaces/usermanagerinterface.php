<?php


namespace Bitrix\Openid\Client\Interfaces;


use Bitrix\Main\Result;
use Bitrix\Openid\Client\User;
use Psr\Http\Message\ResponseInterface;

interface UserManagerInterface
{
    /**
     * @param ResponseInterface $response
     * @return User|null
     */
    public function loadUser(ResponseInterface $response): ?User;

    /**
     * @param User $user
     * @return Result
     */
    public function authorize(User $user): Result;
}