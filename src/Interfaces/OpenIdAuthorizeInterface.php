<?php

namespace Bitrix\Openid\Client\Interfaces;

use Bitrix\Main\Result;

interface OpenIdAuthorizeInterface
{
    /**
     * @param mixed $id
     * @return Result
     * @psalm-suppress UndefinedClass
     */
    public function authorize($id = null): Result;

    /**
     * @return bool
     */
    public function isAuthorized(): bool;
}
