<?php


namespace Bitrix\Openid\Client;


use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Main\Security\Random;
use Bitrix\Main\UserTable;
use Bitrix\Openid\Client\Interfaces\UserManagerInterface;
use CUser;
use Psr\Http\Message\ResponseInterface;

abstract class BaseUserManager implements UserManagerInterface
{
    abstract protected function makeUserFromResponse(ResponseInterface $response): ?User;

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        global $USER;
        return (bool)$USER->IsAuthorized();
    }

    /**
     * @param ResponseInterface $response
     * @return User|null
     */
    public function loadUser(ResponseInterface $response): ?User
    {
        $user = $this->makeUserFromResponse($response);
        if (empty($user)) {
            return null;
        }

        $userData = UserTable::getRow([
            'filter' => [
                '=LOGIN' => $user->login,
            ],
            'select' => [
                'ID',
            ],
        ]);

        $userId = (int)($userData['ID'] ?? 0);
        $importData = [
            'LOGIN' => $user->login,
            'NAME' => $user->name,
            'LAST_NAME' => $user->lastName,
            'SECOND_NAME' => $user->secondName,
            'EMAIL' => $user->email,
            'ACTIVE' => $user->isActive ? 'Y' : 'N',
        ];


        $userEntity = new CUser();
        if ($userId > 0) {
            $user->id = $userId;
            $userEntity->Update($userId, $importData);

            return $user;
        }

        $importData['PASSWORD'] = Random::getString(12, true);
        $id = (int)$userEntity->Add($importData);
        if ($id > 0) {
            $user->id = $id;
            return $user;
        }

        return null;
    }

    /**
     * @param User $user
     * @return Result
     */
    public function authorize(User $user): Result
    {
        $result = new Result();
        if ($this->isAuthorized()) {
            return $result->addError(new Error('Already authorized', 400));
        }

        if (empty($user->id)) {
            return $result->addError(new Error('Invalid user', 400));
        }

        $userData = UserTable::getRow([
            'filter' => [
                '=ID' => $user->id,
            ],
            'select' => [
                'ID',
            ],
        ]);

        if (empty($userData)) {
            return $result->addError(new Error('User not found', 404));
        }

        global $USER;
        $isSuccess = $USER->Authorize($user->id, true);
        if (!$isSuccess) {
            return $result->addError(new Error($USER->LAST_ERROR));
        }

        return $result;
    }
}